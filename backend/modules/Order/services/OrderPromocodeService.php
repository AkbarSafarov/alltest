<?php

namespace backend\modules\Order\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;


class OrderPromocodeService extends ActiveService
{
    public function checkAvailability()
    {
        //        Is active
        
        if (Yii::$app->services->trash->isDeleted($this->model)) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Промокод не найден'));
            return false;
        }
        
        //        Max activations
        
        if ($this->model->max_activations && $this->model->used_activations >= $this->model->max_activations) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Промокод не найден'));
            return false;
        }
        
        //        Dates
        
        if ($this->model->date_from) {
            $date_from = date('Y-m-d', strtotime($this->model->date_from));
            $date_to = date('Y-m-d', strtotime($this->model->date_to));
            
            if ($this->model->date_from && (date('Y-m-d') < $date_from || date('Y-m-d') > $date_to)) {
                Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Промокод не найден'));
                return false;
            }
        }
        
        //        Max quantity
        
//        $cart_products_quantity = ArrayHelper::getValue(Yii::$app->services->cart->cart, 'total.quantity', 0);
//        
//        if ($this->model->max_products && $this->model->max_products < $cart_products_quantity) {
//            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Данный промокод может быть использован только для {quantity} продуктов', [
//                'quantity' => $this->model->max_products,
//            ]));
//            
//            return false;
//        }
        
        //        Available products
        
//        $types = [
//            'Course' => 'courses',
//            'CoursePackage' => 'packages',
//        ];
//        
//        foreach ($types as $model_class => $relation) {
//            if ($available_products = $this->model->{$relation}) {
//                $cart_products = Yii::$app->services->cart->products;
//                
//                foreach ($available_products as $available_product) {
//                    ArrayHelper::remove($cart_products, "$model_class-$available_product->id");
//                }
//                
//                if ($cart_products) {
//                    Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Промокод не распространяется на продукты: {products}', [
//                        'products' => implode(', ', ArrayHelper::getColumn($cart_products, 'model_json.name')),
//                    ]));
//                    
//                    return false;
//                }
//            }
//        }
        
        return true;
    }
}