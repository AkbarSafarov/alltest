<?php

namespace backend\modules\Order\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;

use backend\modules\Order\models\OrderDiscount;


class OrderProductService extends ActiveService
{
    public static function applyDiscountAndPromocode($model)
    {
        $discount = OrderDiscount::find()
            ->joinWith(['productsRef'], false)
            ->andWhere([
                'and',
                ['order_discount_product_ref.model_class' => $model->model_class],
                ['order_discount_product_ref.model_id' => $model->model_id],
                ['<=', 'order_discount.date_from', date('Y-m-d')],
                ['>=', 'order_discount.date_to', date('Y-m-d')],
            ])
            ->one();
        
        $discount_price = $discount ? $model->total_price * ($discount->percent / 100) : 0;
        $promocode_price = $model->total_price * (ArrayHelper::getValue($model, 'order.promocode.percent', 0) / 100);
        
        if ($model->has_promocode) {
            $model->promocode_id = $model->order->promocode_id;
            $model->promocode_price = $promocode_price;
        } elseif ($discount_price < $promocode_price) {
            $model->discount_id = $discount->id;
            $model->discount_price = $model->total_price * ($discount->percent / 100);
        }
    }
}