<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;


class OrderPaymentClick extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_payment_click}}';
    }
    
    public function rules()
    {
        return [
            [['order_id', 'click_trans_id', 'service_id', 'click_paydoc_id', 'amount', 'status', 'sign_time', 'sign_string'], 'required'],
            [['order_id'], 'exist', 'targetClass' => Order::className(), 'targetAttribute' => 'id'],
            [['click_trans_id', 'service_id', 'click_paydoc_id', 'amount', 'status'], 'integer'],
            [['sign_time', 'prepare_time', 'complete_time'], 'date', 'format' => 'php: Y-m-d H:i:s'],
            [['sign_string'], 'string', 'max' => 100],
        ];
    }
    
    static function getErrorType($key)
    {
        $errors = [
            '0' => ['error' => '0', 'error_note' => 'Success'],
            '-1' => ['error' => '-1', 'error_note' => 'SIGN CHECK FAILED!'],
            '-2' => ['error' => '-2', 'error_note' => 'Incorrect parameter amount'],
            '-3' => ['error' => '-3', 'error_note' => 'Action not found'],
            '-4' => ['error' => '-4', 'error_note' => 'Already paid'],
            '-5' => ['error' => '-5', 'error_note' => 'Order does not exist'],
            '-6' => ['error' => '-6', 'error_note' => 'Transaction does not exist'],
            '-7' => ['error' => '-7', 'error_note' => 'Failed to update order'],
            '-8' => ['error' => '-8', 'error_note' => 'Error in request from click'],
            '-9' => ['error' => '-9', 'error_note' => 'Transaction cancelled'],
        ];
        
        return $errors[$key];
    }
    
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
