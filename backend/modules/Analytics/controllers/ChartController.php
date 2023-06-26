<?php

namespace backend\modules\Analytics\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use backend\modules\Analytics\enums\AnalyticsEnums;
use backend\modules\Order\models\Order;
use backend\modules\Order\models\OrderProduct;


class ChartController extends Controller
{
    public function actionOrders(array $filters = [[]])
    {
        $enums = new AnalyticsEnums();
        $months = $enums->months(Order::find()->min('created_at'), date('Y-m-d'));
        $months = array_map(fn($value) => 0, array_flip($months));
        $colors = $enums->colors();
        $order_prices = $enums->orderPrices();
        
        $filters = array_values($filters);
        
        foreach ($filters as $key => $filter) {
            $price_type = ArrayHelper::getValue($filter, 'price_type');
            $price_type = in_array($price_type, ['total_price', 'checkout_price']) ? $price_type : 'checkout_price';
            
            $date_from = ArrayHelper::getValue($filter, 'date_from');
            $date_to = ArrayHelper::getValue($filter, 'date_to');
            $status = ArrayHelper::getValue($filter, 'status', ['paid_paycom', 'paid_click']);
            $discount_id = ArrayHelper::getValue($filter, 'discount_id');
            $promocode_id = ArrayHelper::getValue($filter, 'promocode_id');
            $course_id = ArrayHelper::getValue($filter, 'course_id');
            $package_id = ArrayHelper::getValue($filter, 'package_id');
            
            $orders = OrderProduct::find()
                ->joinWith([
                    'order' => fn($query) => $query->byState(false),
                ], false)
                ->select([
                    'DATE_FORMAT(order.created_at, "%m.%Y") as month',
                    'SUM(order_product.total_price) as total_price',
                    'SUM(order_product.discount_price) as discount_price',
                    'SUM(order_product.promocode_price) as promocode_price',
                    'SUM(order_product.total_price) - SUM(order_product.discount_price) - SUM(order_product.promocode_price) as checkout_price',
                    'COUNT(order_product.id) as total_quantity',
                ])
                ->andFilterWhere([
                    'order.status' => $status,
                    'order_product.discount_id' => $discount_id,
                    'order_product.promocode_id' => $promocode_id,
                    "IF(model_class = 'Course', order_product.model_id, false)" => $course_id,
                    "IF(model_class = 'CoursePackage', order_product.model_id, false)" => $package_id,
                ])
                ->andFilterWhere([
                    'and',
                    ['>=', 'order.created_at', $date_from ? date('Y-m-d H:i', strtotime($date_from)) : null],
                    ['<=', 'order.created_at', $date_to ? date('Y-m-d H:i', strtotime($date_to)) : null],
                ])
                ->orderBy('order.created_at')
                ->groupBy('month')
                ->indexBy('month')
                ->asArray()->all();
            
            foreach (array_keys($order_prices) as $order_price_key) {
                $price_groups[$key][$order_price_key] = array_sum(ArrayHelper::getColumn($orders, $order_price_key));
            }
            
            $datasets[$key] = [
                'label' => Yii::t('app', 'Запрос №{number}', ['number' => $key + 1]),
                'backgroundColor' => $colors[$key % 7],
                'data' => array_values(array_merge($months, ArrayHelper::getColumn($orders, $price_type))),
                'totalQuantity' => array_values(array_merge($months, ArrayHelper::getColumn($orders, 'total_quantity'))),
            ];
        }
        
        return $this->render('orders', [
            'price_groups' => $price_groups,
            'datasets' => $datasets ?? [],
            'months' => array_keys($months),
            'enums' => $enums,
            'filters' => $filters,
        ]);
    }
    
    public function actionAddFilter($key)
    {
        return $this->renderAjax('_filter', [
            'enums' => new AnalyticsEnums(),
            'key' => $key,
            'filter' => [],
        ]);
    }
}
