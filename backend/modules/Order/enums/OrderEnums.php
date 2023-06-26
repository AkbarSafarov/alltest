<?php

namespace backend\modules\Order\enums;

use Yii;


class OrderEnums
{
    public static function statuses()
    {
        return [
            'created' => [
                'label' => Yii::t('app', 'Оплата отклонена'),
            ],
            'paid' => [
                'label' => Yii::t('app', 'Получен бесплатно'),
            ],
            'paid_paycom' => [
                'label' => Yii::t('app', 'Оплачен (Payme)'),
            ],
            'paid_click' => [
                'label' => Yii::t('app', 'Оплачен (Click)'),
            ],
        ];
    }
}