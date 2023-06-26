<?php

namespace backend\modules\User\enums;

use Yii;
use yii\helpers\ArrayHelper;


class UserCourseEnums
{
    public static function types()
    {
        return [
            null => [
                'label' => Yii::t('app', 'Все курсы пользователя'),
            ],
            'linear' => [
                'label' => Yii::t('app', 'Линейные курсы'),
            ],
            'exam' => [
                'label' => Yii::t('app', 'Тестовые задания'),
            ],
            'archive' => [
                'label' => Yii::t('app', 'Архивные курсы'),
            ],
        ];
    }
}