<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Промокоды'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                'percent' => 'text_input',
                'description' => 'text_area',
                [
                    'name' => 'key',
                    'type' => 'text_input',
                    'container_options' => [
                        'hintOptions' => [
                            'hint' => Yii::t('app', 'Оставьте поле пустым для применения случайного значения'),
                        ],
                    ],
                ],
            ],
        ],
        'filters' => [
            'label' => Yii::t('app', 'Фильтры'),
            'attributes' => [
                'max_activations' => 'text_input',
                [
                    'name' => 'used_activations',
                    'type' => 'text_input',
                    'options' => [
                        'disabled' => true,
                    ],
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
                        'data-toggle' => 'datepicker',
                    ],
                ],
                [
                    'name' => 'courses_tmp',
                    'type' => 'select2_ajax',
                    'data' => ArrayHelper::map($model->courses, 'id', 'name'),
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/courses', 'type' => 'active', 'id' => $model->id]),
                    ],
                    'options' => [
                        'options' => [
                            'multiple' => true,
                            'value' => ArrayHelper::getColumn($model->courses, 'id'),
                        ],
                    ]
                ],
                [
                    'name' => 'packages_tmp',
                    'type' => 'select2_ajax',
                    'data' => ArrayHelper::map($model->packages, 'id', 'name'),
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/course-packages', 'type' => 'active', 'id' => $model->id]),
                    ],
                    'options' => [
                        'options' => [
                            'multiple' => true,
                            'value' => ArrayHelper::getColumn($model->packages, 'id'),
                        ],
                    ]
                ],
                'max_products' => 'text_input',
            ],
        ],
    ],
]);
