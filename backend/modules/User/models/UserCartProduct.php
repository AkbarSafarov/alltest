<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;


class UserCartProduct extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_cart_product}}';
    }
    
    public function getProduct()
    {
        return $this->hasOne("\backend\modules\Course\models\\$this->model_class", ['id' => 'model_id']);
    }
    
    public function beforeSave($insert)
    {
        $this->user_id = $this->user_id ?: Yii::$app->user->id;
        $this->model_json = $this->product->attributes;
        $this->price = $this->product->price;
        $this->checkout_price = $this->product->service->realPrice();
        
        return parent::beforeSave($insert);
    }
}
