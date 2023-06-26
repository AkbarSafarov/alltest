<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

return [
    ['template' => Html::tag('li', Yii::t('app_menu', 'Меню'), ['class' => 'menu-title'])],
    [
        'label' => Yii::t('app_menu', 'Курсы'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-bookshelf']),
        'url' => ['/course/course/index'],
    ],
    [
        'label' => Yii::t('app_menu', 'Тесты'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-format-list-checks']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Тесты'), 'url' => ['/library/test/index']],
            ['label' => Yii::t('app_menu', 'Темы предметов'), 'url' => ['/library/test-category/index']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'Библиотека'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-image-multiple']),
        'items' => [
            ['template' => Html::tag(
                'div',
                Html::a(Yii::t('app_menu', 'Файловый менеджер'), 'javascript:void(0)', ['class' => 'yii2-elfinder-select-button']),
                ['data-toggle' => 'elfinder']
            )],
        ],
    ],
];
