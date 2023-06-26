<?php

namespace backend\modules\Library\enums;

use Yii;


class LibraryTemplateEnums
{
    public static function inputTypes()
    {
        return [
            'text_input' => [
                'label' => Yii::t('app', 'Текстовое поле'),
            ],
            'text_area' => [
                'label' => Yii::t('app', 'Текстовая область'),
            ],
            'text_editor' => [
                'label' => Yii::t('app', 'Текстовый редактор'),
            ],
            'file_manager' => [
                'label' => Yii::t('app', 'Файловый менеджер'),
            ],
            'file_input' => [
                'label' => Yii::t('app', 'Загрузка файлов'),
            ],
        ];
    }
}
