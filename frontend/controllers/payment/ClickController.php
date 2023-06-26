<?php

namespace frontend\controllers\payment;

use Yii;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use backend\modules\Order\models\Order;
use backend\modules\Order\models\OrderPaymentClick;


class ClickController extends Controller
{
    public $enableCsrfValidation = false;
    
    public $post_data = [];
    public $order;
    public $payment;
    
    public function beforeAction($action)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parse_str(file_get_contents("php://input"), $this->post_data);
        
        return parent::beforeAction($action);
    }
    
    public function actionPrepare()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if ($error = $this->checkErrors(0)) {
            return $error;
        }
        
        if ($this->post_data['amount'] != $this->order->checkout_price) {
            return OrderPaymentClick::getErrorType('-2');
        }
        
        if ($this->order->status != 'created') {
            return OrderPaymentClick::getErrorType('-5');
        }
        
        $this->payment = new OrderPaymentClick;
        $this->payment->order_id = $this->order->id;
        $this->payment->click_trans_id = $this->post_data['click_trans_id'];
        $this->payment->service_id = $this->post_data['service_id'];
        $this->payment->click_paydoc_id = $this->post_data['click_paydoc_id'];
        $this->payment->amount = $this->post_data['amount'];
        $this->payment->status = 0;
        $this->payment->sign_time = $this->post_data['sign_time'];
        $this->payment->sign_string = $this->post_data['sign_string'];
        $this->payment->prepare_time = date('Y-m-d H:i:s');
        
        if ($this->payment->save()) {
            return [
                'click_trans_id' => $this->payment->click_trans_id,
                'merchant_trans_id' => (int)$this->payment->order_id,
                'merchant_prepare_id' => $this->payment->id,
                'error' => 0,
                'error_note' => 'Success',
            ];
        } else {
            return OrderPaymentClick::getErrorType('-8');
        }
    }
    
    public function actionComplete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if ($error = $this->checkErrors(1)) {
            return $error;
        }
        
        if (!$this->payment || $this->post_data['merchant_prepare_id'] != $this->payment->id) {
            return OrderPaymentClick::getErrorType('-6');
        }
        
        if ($this->post_data['amount'] != $this->payment->amount) {
            return OrderPaymentClick::getErrorType('-2');
        }
        
        $this->payment->status = 1;
        $this->payment->complete_time = date('Y-m-d H:i:s');
        
        if ($this->payment->save()) {
            if (!$this->order->updateAttributes(['status' => 'paid_click'])) {
                return OrderPaymentClick::getErrorType('-7');
            }
            
            $this->order->service->createUserCourses();
            
            return [
                'click_trans_id' => $this->payment->click_trans_id,
                'merchant_trans_id' => (int)$this->payment->order_id,
                'merchant_confirm_id' => $this->payment->id,
                'error' => 0,
                'error_note' => 'Success',
            ];
        } else {
            return OrderPaymentClick::getErrorType('-7');
        }
    }
    
    protected function checkErrors($action)
    {
        $sign_string = md5(
            $this->post_data['click_trans_id'].
            $this->post_data['service_id'].
            Yii::$app->params['payment_connections']['click']['secret_key'].
            $this->post_data['merchant_trans_id'].
            ($action == 1 ? $this->post_data['merchant_prepare_id'] : null).
            $this->post_data['amount'].
            $this->post_data['action'].
            $this->post_data['sign_time']
        );
        
        if ($this->post_data['sign_string'] != $sign_string) {
            return OrderPaymentClick::getErrorType('-1');
        }
        
        if ($this->post_data['amount'] < 100 || $this->post_data['amount'] > 99999999) {
            return OrderPaymentClick::getErrorType('-2');
        }
        
        if ($this->post_data['action'] != $action) {
            return OrderPaymentClick::getErrorType('-3');
        }
        
        if (!($this->order = Order::findOne($this->post_data['merchant_trans_id']))) {
            return OrderPaymentClick::getErrorType('-5');
        }
        
        $this->payment = OrderPaymentClick::find()->where(['click_trans_id' => $this->post_data['click_trans_id']])->one();
        
        if ($this->payment) {
            if ($this->payment->status == 1) {
                return OrderPaymentClick::getErrorType('-4');
            } elseif ($this->payment->status == -1) {
                return OrderPaymentClick::getErrorType('-9');
            }
        }
        
        if ($this->post_data['error'] < 0) {
            $this->payment ? $this->payment->updateAttributes(['status' => -1]) : null;
            return OrderPaymentClick::getErrorType('-9');
        }
        
        return false;
    }
}
