<?php

namespace backend\modules\Analytics\enums;

use Yii;


class AnalyticsEnums
{
    public static function months($from, $to)
    {
        $from = strtotime(date('Y-m-01', strtotime($from)));
        $to = strtotime(date('Y-m-01', strtotime($to)));
        
        while ($from <= $to) {
            $result[] = date('m.Y', $from);
            $from = strtotime('+1 month', $from);
        }
        
        return $result ?? [];
    }
    
    public static function colors()
    {
        return ['#7e57c2', '#4fc6e1', '#1abc9c', '#f7b84b', '#f1556c', '#323a46', '#6c757d'];
    }
    
    public static function orderPrices()
    {
        return [
            'total_price' => [
                'label' => Yii::t('app', 'Предварительная сумма'),
                'css_class' => 'primary',
            ],
            'checkout_price' => [
                'label' => Yii::t('app', 'Итоговая сумма'),
                'css_class' => 'success',
            ],
            'total_quantity' => [
                'label' => Yii::t('app', 'Итоговое количество продуктов'),
                'css_class' => 'warning',
            ],
        ];
    }
}
