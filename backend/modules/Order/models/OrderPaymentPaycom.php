<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;


class OrderPaymentPaycom extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_payment_paycom}}';
    }
    
    public function rules()
    {
        return [
            [['order_id', 'paycom_transaction_id', 'paycom_time', 'paycom_time_datetime', 'create_time', 'amount', 'state'], 'required'],
            [['order_id'], 'exist', 'targetClass' => Order::className(), 'targetAttribute' => 'id'],
            [['paycom_transaction_id'], 'string', 'max' => 25],
            [['paycom_time_datetime', 'create_time', 'perform_time', 'cancel_time'], 'date', 'format' => 'php: Y-m-d H:i:s'],
            [['paycom_time', 'amount', 'state', 'reason'], 'integer'],
        ];
    }
    
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
