<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'username' => 'text_input',
                'nickname' => 'text_input',
                [
                    'name' => 'role',
                    'type' => 'select',
                    'data' => ArrayHelper::getColumn($model->enums->roles(), 'label'),
                ],
                [
                    'name' => 'new_password',
                    'type' => 'text_input',
                    'options' => [
                        'type' => 'password',
                    ],
                ],
            ],
        ],
        
        'profile' => [
            'label' => Yii::t('app', 'Профиль'),
            'attributes' => [
                [
                    'name' => 'image',
                    'type' => 'files',
                    'multiple' => false,
                    'options' => [
                        'pluginOptions' => [
                            'initialPreviewConfig' => [['key' => $model->image, 'downloadUrl' => $model->image]],
                        ],
                    ],
                    'widget_options' => [
                        'delete_url' => Yii::$app->urlManager->createUrl([
                            'user/user/file-delete', 'id' => $model->id, 'attr' => 'image'
                        ]),
                    ],
                ],
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
                [
                    'name' => 'parent_phone',
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
            ],
        ],
    ],
]);
