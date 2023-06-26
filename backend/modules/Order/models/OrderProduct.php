<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\Order\services\OrderProductService;
use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;


class OrderProduct extends ActiveRecord
{
    public $checkout_price;
    public $has_promocode;
    
    public static function tableName()
    {
        return '{{%order_product}}';
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'order_id' => Yii::t('app', 'Заказ'),
            'model_class' => Yii::t('app', 'Продукт'),
            'model_id' => Yii::t('app', 'Продукт'),
            
            'total_price' => Yii::t('app', 'Предварительная цена'),
            'discount_price' => Yii::t('app', 'Стоимость скидки'),
            'promocode_price' => Yii::t('app', 'Стоимость промокода'),
            'checkout_price' => Yii::t('app', 'Итоговая стоимость'),
        ];
    }
    
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
    public function getDiscount()
    {
        return $this->hasOne(OrderDiscount::className(), ['id' => 'discount_id']);
    }
    
    public function getProduct()
    {
        return $this->hasOne("\backend\modules\Course\models\\$this->model_class", ['id' => 'model_id']);
    }
    
    public function afterFind()
    {
        $this->checkout_price = $this->total_price - $this->discount_price - $this->promocode_price;
        return parent::afterFind();
    }
    
    public function beforeSave($insert)
    {
        $product = $this->product;
        
        $this->model_json = $product->attributes;
        $this->total_price = $product->price;
        
        OrderProductService::applyDiscountAndPromocode($this);
        
        return parent::beforeSave($insert);
    }
}
