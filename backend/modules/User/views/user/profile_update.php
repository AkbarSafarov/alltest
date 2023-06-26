<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = Yii::t('app', 'Редактирование профиля');
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'save_buttons' => ['save'],
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'nickname' => 'text_input',
                'full_name' => 'text_input',
                [
                    'name' => 'gender',
                    'type' => 'select',
                    'data' => ArrayHelper::getColumn($model->enums->genders(), 'label'),
                ],
                [
                    'name' => 'birth_date',
                    'type' => 'text_input',
                    'options' => [
                        'data-toggle' => 'datepicker',
                    ],
                ],
                [
                    'name' => 'phone',
                    'type' => 'text_input',
                    'container_options' => [
                        'hintOptions' => [
                            'hint' => Yii::t('app', 'Необходимый формат: {format}', [
                                'format' => '998XXYYYZZZZ',
                            ]),
                        ],
                    ],
                ],
                'address' => 'text_input',
                [
                    'name' => 'new_password',
                    'type' => 'text_input',
                    'options' => [
                        'type' => 'password',
                    ],
                ],
            ],
        ],
    ],
]);
