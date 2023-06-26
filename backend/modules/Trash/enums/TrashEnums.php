<?php

namespace backend\modules\Trash\enums;

use Yii;


class TrashEnums
{
    public static function states()
    {
        return [
            'development' => [
                'label' => Yii::t('app', 'В разработке'),
                'background' => '#f8f9fa',
                'color' => '#191d1d',
            ],
            'active' => [
                'label' => Yii::t('app', 'Активные'),
                'background' => '#067a7d',
                'color' => '#fff',
            ],
            'outdated' => [
                'label' => Yii::t('app', 'Окончившиеся'),
                'background' => '#067a7d',
                'color' => '#fff',
            ],
            'archive' => [
                'label' => Yii::t('app', 'Удалённые'),
                'background' => '#067a7d',
                'color' => '#fff',
            ],
        ];
    }
}
