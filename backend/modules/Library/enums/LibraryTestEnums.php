<?php

namespace backend\modules\Library\enums;

use Yii;


class LibraryTestEnums
{
    public static function inputTypes()
    {
        return [
            'radio' => [
                'label' => Yii::t('app', 'Единственный ответ'),
            ],
            'checkbox' => [
                'label' => Yii::t('app', 'Множественный ответ'),
            ],
            'text_area' => [
                'label' => Yii::t('app', 'Текстовая область'),
            ],
            'sequence' => [
                'label' => Yii::t('app', 'Последовательность'),
            ],
            'match' => [
                'label' => Yii::t('app', 'Установление соответствия'),
            ],
        ];
    }
}
