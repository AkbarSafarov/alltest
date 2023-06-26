<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\User;


class Order extends ActiveRecord
{
    public $products_tmp;
    
    public $checkout_price;
    
    public static function tableName()
    {
        return '{{%order}}';
    }
    
    public function behaviors()
    {
        return [
            'relations_one_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'oneMany',
                'attributes' => [
                    'products_tmp' => [
                        'model' => new OrderProduct(),
                        'relation' => 'products',
                        'attributes' => [
                            'main' => 'order_id',
                            'relational' => ['model_class', 'model_id', 'has_promocode'],
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['products_tmp'], 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'student_id' => Yii::t('app', 'Студент'),
            'status' => Yii::t('app', 'Статус'),
            'total_price' => Yii::t('app', 'Предварительная сумма'),
            'discount_price' => Yii::t('app', 'Сумма скидки'),
            
            'promocode_id' => Yii::t('app', 'Промокод'),
            'promocode_price' => Yii::t('app', 'Сумма по промокоду'),
            
            'key' => Yii::t('app', 'Секретный ключ'),
            'created_at' => Yii::t('app', 'Создано'),
            
            'products_tmp' => Yii::t('app', 'Продукты'),
            'checkout_price' => Yii::t('app', 'Итоговая сумма'),
        ];
    }
    
    public function getStudent()
    {
        return $this->hasOne(User::className(), ['id' => 'student_id']);
    }
    
    public function getPromocode()
    {
        return $this->hasOne(OrderPromocode::className(), ['id' => 'promocode_id']);
    }
    
    public function getProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id'])->orderBy('sort');
    }
    
    public function getPaymentPaycom()
    {
        return $this->hasOne(OrderPaymentPaycom::className(), ['order_id' => 'id']);
    }
    
    public function getPaymentClick()
    {
        return $this->hasOne(OrderPaymentClick::className(), ['order_id' => 'id']);
    }
    
    public function afterFind()
    {
        $this->id = sprintf('%07d', $this->id);
        $this->checkout_price = $this->total_price - $this->discount_price - $this->promocode_price;
        
        return parent::afterFind();
    }
    
    public function beforeSave($insert)
    {
        $this->student_id = Yii::$app->user->id;
        $this->key = Yii::$app->services->string->randomize();
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        //        Prices
        
        parent::afterSave($insert, $changedAttributes);
        
        $this->refresh();
        $this->updateAttributes([
            'total_price' => array_sum(ArrayHelper::getColumn($this->products, 'total_price', [])),
            'discount_price' => array_sum(ArrayHelper::getColumn($this->products, 'discount_price', [])),
            'promocode_price' => array_sum(ArrayHelper::getColumn($this->products, 'promocode_price', [])),
        ]);
        
        //        Promocode
        
        if ($this->promocode && $this->promocode->max_activations) {
            if ($this->promocode->used_activations === null) {
                $this->promocode->updateAttributes(['used_activations' => 1]);
            } else {
                $this->promocode->updateCounters(['used_activations' => 1]);
            }
        }
    }
}
