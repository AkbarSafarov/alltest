<?php

namespace backend\modules\User\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\User\models\UserCart;
use backend\modules\User\models\UserCartProduct;
use backend\modules\Order\models\Order;
use backend\modules\Order\models\OrderPromocode;
use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;


class UserCartService
{
    public $cart;
    public $products;
    public $total_quantity;
    public $total_price;
    public $checkout_price;
    
    public function __construct()
    {
        $this->cart = UserCart::find()->with(['products'])->where(['user_id' => Yii::$app->user->id])->one();
        $this->products = $this->cart ? ArrayHelper::index($this->cart->products, fn($value) => $value['model_class'] . '-' . $value['model_id']) : [];
        $this->total_quantity = count($this->products);
        $this->total_price = array_sum(ArrayHelper::getColumn($this->products, 'price'));
        $this->checkout_price = array_sum(ArrayHelper::getColumn($this->products, 'checkout_price'));
    }
    
    public function change($product, $type)
    {
        if (!isset($this->products["$type-$product->id"])) {
            $cart_product = new UserCartProduct();
            $cart_product->model_class = $type;
            $cart_product->model_id = $product->id;
            $cart_product->save();
        } else {
            $this->products["$type-$product->id"]->delete();
        }
        
        $this->fixProducts();
    }
    
    public function changePromocode($promocode_id = null)
    {
        $this->cart->promocode_id = $promocode_id;
        $this->cart->save();
        
        $this->fixProducts();
    }
    
    public function fixProducts()
    {
        if ($promocode = $this->cart->promocode) {
            if ($promocode->service->checkAvailability()) {
                $promocode_courses = ArrayHelper::getColumn($promocode->courses, 'id');
                $promocode_packages = ArrayHelper::getColumn($promocode->packages, 'id');
                $cart_promocode_products_quantity = 0;
            } else {
                $this->cart->updateAttributes(['promocode_id' => null]);
                $promocode = null;
            }
        }
        
        foreach ($this->products as $cart_product) {
            $product_class = "\backend\modules\Course\models\\$cart_product->model_class";
            $product = $product_class::find()->active()->andWhere(['id' => $cart_product->model_id])->one();
            
            if (!$product) {
                $cart_product->delete();
                continue;
            }
            
            $discount_percent = ArrayHelper::getValue($product, 'activeDiscount.percent', 0);
            
            if ($promocode && (!$promocode->max_products || $cart_promocode_products_quantity < $promocode->max_products)) {
                switch ($cart_product->model_class) {
                    case 'Course':
                        $promocode_percent = !$promocode_courses || in_array($cart_product->model_id, $promocode_courses) ? $promocode->percent : 0;
                        break;
                    case 'CoursePackage':
                        $promocode_percent = !$promocode_packages || in_array($cart_product->model_id, $promocode_packages) ? $promocode->percent : 0;
                        break;
                }
                
                $cart_promocode_products_quantity += (bool)$promocode_percent;
            } else {
                $promocode_percent = 0;
            }
            
            $percent = max($promocode_percent, $discount_percent);
            $checkout_price = $product->price - ($product->price * $percent / 100);
            
            $cart_product->updateAttributes([
                'checkout_price' => $checkout_price,
                'has_promocode' => $promocode_percent && $promocode_percent == $percent,
            ]);
        }
        
        return $this->__construct();
    }
    
    public function createOrder()
    {
        $this->fixProducts();
        
        $order = new Order();
        $order->promocode_id = $this->cart->promocode_id;
        
        foreach ($this->products as $cart_product) {
            $order_products[] = [
                'model_class' => $cart_product->model_class,
                'model_id' => $cart_product->model_id,
                'has_promocode' => $cart_product->has_promocode,
            ];
            
            $cart_product->delete();
        }
        
        $order->products_tmp = $order_products ?? [];
        
        if (!$order->save()) {
            Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Произошла ошибка'));
            return false;
        }
        
        $order->afterFind();
        
        return $order;
    }
}
