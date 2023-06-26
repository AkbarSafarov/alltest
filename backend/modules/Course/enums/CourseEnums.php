<?php

namespace backend\modules\Course\enums;

use Yii;


class CourseEnums
{
    public static function types()
    {
        return [
            'linear' => [
                'label' => Yii::t('app', 'Линейный курс'),
                'group_name' => Yii::t('app', 'Обучающие курсы'),
            ],
            'exam' => [
                'label' => Yii::t('app', 'Экзамен'),
                'group_name' => Yii::t('app', 'Тестовые задания'),
            ],
        ];
    }
}
