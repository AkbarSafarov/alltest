<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'has_seo_meta' => true,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                'slug' => 'text_input',
                [
                    'name' => 'published_at_from',
                    'type' => 'text_input',
                    'options' => [
                        'data-toggle' => 'datetimepicker',
                    ],
                ],
                [
                    'name' => 'published_at_to',
                    'type' => 'text_input',
                    'options' => [
                        'data-toggle' => 'datetimepicker',
                    ],
                ],
                [
                    'name' => 'image',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'image',
                    ],
                ],
                'short_description' => 'text_area',
                'full_description' => 'tinymce',
            ],
        ],
    ],
]);
