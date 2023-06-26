<?php

use yii\helpers\Html;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Языки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                'code' => 'text_input',
                'is_active' => 'checkbox',
                'is_main' => 'checkbox',
                [
                    'name' => 'image',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'image',
                    ],
                ],
            ],
        ],
    ],
]);
