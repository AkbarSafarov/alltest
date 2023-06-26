<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пакеты курсов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                'price' => 'text_input',
                [
                    'name' => 'image',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'image',
                    ],
                ],
                [
                    'name' => 'language_id',
                    'type' => 'select',
                    'data' => ArrayHelper::map($languages, 'id', 'name'),
                ],
                [
                    'name' => 'date_from',
                    'type' => 'text_input',
                    'options' => [
                        'data-toggle' => 'datepicker',
                    ],
                ],
                [
                    'name' => 'date_to',
                    'type' => 'text_input',
                    'options' => [
                        'disabled' => true,
                    ],
                ],
                'optimal_time' => 'text_input',
                'students_start_quantity' => 'text_input',
                [
                    'name' => 'courses_tmp',
                    'type' => 'select2_ajax',
                    'data' => ArrayHelper::map($model->courses, 'id', 'name'),
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/courses', 'type' => 'active']),
                    ],
                    'options' => [
                        'options' => [
                            'multiple' => true,
                            'value' => ArrayHelper::getColumn($model->courses, 'id'),
                        ],
                    ],
                ],
                'description' => 'tinymce',
            ],
        ],
    ],
]);
