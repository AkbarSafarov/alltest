<?php

namespace frontend\controllers\payment;

use Yii;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use backend\modules\Order\models\Order;
use backend\modules\Order\models\OrderPaymentPaycom;


class PaycomController extends Controller
{
    public $enableCsrfValidation = false;
    
    public $post_data = [];
    public $result = [];
    public $order;
    
    public function actionIndex()
    {
        if (!in_array($_SERVER['HTTP_X_REAL_IP'], ['195.158.31.134', '195.158.31.10'])) {
            return null;
        }
        
        $this->post_data = file_get_contents("php://input");
        $this->post_data = json_decode($this->post_data, JSON_UNESCAPED_UNICODE);
        
        $authorization_key = str_replace('Basic ', '', getallheaders()['Authorization']);
        $authorization_key = str_replace('Paycom:', '', base64_decode($authorization_key));
        
        $this->result['jsonrpc'] = '2.0';
        $this->result['id'] = $this->post_data['id'];
        
        if ($authorization_key != Yii::$app->params['payment_connections']['paycom']['secret_pk']) {
            return $this->errorAuthorization();
        }
        
        $method = lcfirst($this->post_data['method']);
        $order_id = ArrayHelper::getValue($this->post_data, 'params.account.order_id');
        $this->order = Order::findOne($order_id);
        
        if ($this->order) {
            if ($this->post_data['params']['amount'] != $this->order->checkout_price * 100) {
                return $this->errorIncorrectAmount();
            }
            
            switch ($this->order->status) {
                case 'created':
                    return $this->{$method}();
                default:
                    return $this->errorIsPaid();
            }
        } else {
            if (in_array($method, ['checkTransaction', 'performTransaction', 'cancelTransaction'])) {
                return $this->{$method}();
            } else {
                return $this->errorRecordNotFound();
            }
        }
    }
    
    protected function checkPerformTransaction()
    {
        $this->result['result'] = [
            'allow' => true
        ];
        
        return $this->result;
    }
    
    protected function createTransaction()
    {
        $time = intval($this->post_data['params']['time'] / 1000);
        $payment = OrderPaymentPaycom::find()->where(['paycom_transaction_id' => $this->post_data['params']['id']])->one();
        
        if ($this->order->paymentPaycom && $this->order->paymentPaycom->paycom_transaction_id != $this->post_data['params']['id']) {
            return $this->errorHasPayment();
        }
        
        if ($payment) {
            if ($payment->state == 1) {
                if ($time - 43200 > $payment->paycom_time) {
                    $payment->updateAttributes(['state' => -1, 'reason' => 4]);
                    return $this->errorNotAvailable();
                } else {
                    $this->result['result'] = [
                        'create_time' => strtotime($payment->create_time) * 1000,
                        'transaction' => $payment->paycom_transaction_id,
                        'state' => 1,
                    ];
                }
            }
            
            return $this->result;
        } else {
            $payment = new OrderPaymentPaycom;
            $payment->order_id = $this->order->id;
            $payment->paycom_transaction_id = $this->post_data['params']['id'];
            $payment->paycom_time = $time;
            $payment->paycom_time_datetime = date('Y-m-d H:i:s', $time);
            $payment->create_time = date('Y-m-d H:i:s');
            $payment->amount = $this->post_data['params']['amount'];
            $payment->state = 1;
            
            if ($payment->save()) {
                $this->result['result'] = [
                    'create_time' => strtotime($payment->create_time) * 1000,
                    'transaction' => $payment->paycom_transaction_id,
                    'state' => 1,
                ];
            } else {
                return $this->errorNotAvailable();
            }
        }
        
        return $this->result;
    }
    
    protected function performTransaction()
    {
        $time = time();
        $payment = OrderPaymentPaycom::find()->where(['paycom_transaction_id' => $this->post_data['params']['id']])->one();
        
        if ($payment) {
            if ($payment->state == 1) {
                if ($time - 43200 > $payment->paycom_time) {
                    $payment->updateAttributes(['state' => -1, 'reason' => 4]);
                    return $this->errorNotAvailable();
                } else {
                    $payment->updateAttributes(['state' => 2, 'perform_time' => date('Y-m-d H:i:s')]);
                    
                    $payment->order->updateAttributes(['status' => 'paid_paycom']);
                    $payment->order->service->createUserCourses();
                    
                    $this->result['result'] = [
                        'perform_time' => strtotime($payment->perform_time) * 1000,
                        'transaction' => $payment->paycom_transaction_id,
                        'state' => 2,
                    ];
                }
            } elseif ($payment->state == 2) {
                $this->result['result'] = [
                    'perform_time' => strtotime($payment->perform_time) * 1000,
                    'transaction' => $payment->paycom_transaction_id,
                    'state' => 2,
                ];
            } else {
                return $this->errorNotAvailable();
            }
        } else {
            return $this->errorPaymentNotFound();
        }
        
        return $this->result;
    }
    
    private function cancelTransaction()
    {
        $payment = OrderPaymentPaycom::find()->where(['paycom_transaction_id' => $this->post_data['params']['id']])->one();
        
        if ($payment) {
            if ($payment->state == 1) {
                $payment->updateAttributes([
                    'cancel_time' => date('Y-m-d H:i:s'),
                    'state' => -1,
                    'reason' => $this->post_data['params']['reason'],
                ]);
                
                $this->result['result'] = [
                    'cancel_time' => strtotime($payment->cancel_time) * 1000,
                    'transaction' => $payment->paycom_transaction_id,
                    'state' => $payment->state,
                ];
            } elseif ($payment->state == 2) {
                $payment->updateAttributes([
                    'cancel_time' => date('Y-m-d H:i:s'),
                    'state' => -2,
                    'reason' => $this->post_data['params']['reason'],
                ]);
                
                $this->result['result'] = [
                    'cancel_time' => strtotime($payment->cancel_time) * 1000,
                    'transaction' => $payment->paycom_transaction_id,
                    'state' => $payment->state,
                ];
            } else {
                $this->result['result'] = [
                    'cancel_time' => strtotime($payment->cancel_time) * 1000,
                    'transaction' => $payment->paycom_transaction_id,
                    'state' => $payment->state,
                ];
            }
        } else {
            return $this->errorPaymentNotFound();
        }
        
        return $this->result;
    }
    
    protected function checkTransaction()
    {
        $payment = OrderPaymentPaycom::find()->where(['paycom_transaction_id' => $this->post_data['params']['id']])->one();
        
        if ($payment) {
            $this->result['result'] = [
                'create_time' => strtotime($payment->create_time) * 1000,
                'perform_time' => strtotime($payment->perform_time) * 1000,
                'cancel_time' => strtotime($payment->cancel_time) * 1000,
                'transaction' => $payment->paycom_transaction_id,
                'state' => $payment->state,
                'reason' => $payment->reason,
            ];
        } else {
            return $this->errorPaymentNotFound();
        }
        
        return $this->result;
    }
    
    protected function errorAuthorization()
    {
        $this->result['error'] = [
            'code' => -32504,
            'message' => [
                'ru' => 'Неверные данные для авторизации',
                'uz' => 'Неверные данные для авторизации',
                'en' => 'Неверные данные для авторизации'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorRecordNotFound()
    {
        $this->result['error'] = [
            'code' => -31050,
            'message' => [
                'ru' => 'Запись не найдена',
                'uz' => 'Запись не найдена',
                'en' => 'Запись не найдена'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorIncorrectAmount()
    {
        $this->result['error'] = [
            'code' => -31001,
            'message' => [
                'ru' => 'Неверная сумма',
                'uz' => 'Неверная сумма',
                'en' => 'Неверная сумма'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorPaymentNotFound()
    {
        $this->result['error'] = [
            'code' => -31003,
            'message' => [
                'ru' => 'Транзакция не найдена',
                'uz' => 'Транзакция не найдена',
                'en' => 'Транзакция не найдена'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorNotAvailable()
    {
        $this->result['error'] = [
            'code' => -31008,
            'message' => [
                'ru' => 'Невозможно выполнить операцию',
                'uz' => 'Невозможно выполнить операцию',
                'en' => 'Невозможно выполнить операцию'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorHasPayment()
    {
        $this->result['error'] = [
            'code' => -31052,
            'message' => [
                'ru' => 'Невозможно выполнить операцию',
                'uz' => 'Невозможно выполнить операцию',
                'en' => 'Невозможно выполнить операцию'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorIsPaid()
    {
        $this->result['error'] = [
            'code' => -31051,
            'message' => [
                'ru' => 'Заказ уже оплачен',
                'uz' => 'Заказ уже оплачен',
                'en' => 'Заказ уже оплачен'
            ]
        ];
        
        return $this->result;
    }
    
    protected function errorCancelPayment()
    {
        $this->result['error'] = [
            'code' => -31007,
            'message' => [
                'ru' => 'Невозможно отменить транзакцию',
                'uz' => 'Невозможно отменить транзакцию',
                'en' => 'Невозможно отменить транзакцию'
            ]
        ];
        
        return $this->result;
    }
}
