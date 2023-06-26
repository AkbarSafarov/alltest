<?php

namespace backend\modules\Order\enums;

use Yii;


class OrderProductEnums
{
    public static function modelClasses()
    {
        return [
            'Course' => [
                'label' => Yii::t('app', 'Курс'),
            ],
            'CoursePackage' => [
                'label' => Yii::t('app', 'Пакет курсов'),
            ],
        ];
    }
}