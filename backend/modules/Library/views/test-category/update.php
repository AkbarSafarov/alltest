<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Темы предметов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                [
                    'name' => 'subject_id',
                    'type' => 'select2_ajax',
                    'data' => [$model->subject_id => ArrayHelper::getValue($model->subject, 'name')],
                    'container_options' => $model->is_default ? [
                        'options' => [
                            'class' => 'd-none',
                        ],
                    ] : [],
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/library-test-subjects']),
                    ],
                ],
            ],
        ],
    ],
]);
