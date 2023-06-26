<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;

use backend\modules\Order\models\OrderPromocode;


class UserCart extends ActiveRecord
{
    public $service = false;
    
    public static function tableName()
    {
        return '{{%user_cart}}';
    }
    
    public function getPromocode()
    {
        return $this->hasOne(OrderPromocode::className(), ['id' => 'promocode_id']);
    }
    
    public function getProducts()
    {
        return $this->hasMany(UserCartProduct::className(), ['user_id' => 'user_id'])->orderBy('created_at');
    }
    
    public function beforeSave($insert)
    {
        $this->user_id = $this->user_id ?: Yii::$app->user->id;
        return parent::beforeSave($insert);
    }
}
