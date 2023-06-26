<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\widgets\crud\UpdateWidget;
use backend\modules\Library\widgets\LibraryAttachmentTemplateWidget;
use backend\modules\Library\widgets\LibraryAttachmentTestWidget;
use backend\modules\Library\widgets\LibraryAttachmentTestPackWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Курсы'), 'url' => ['/course/course/index']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Структура курса "{course}"', ['course' => $model->course->name]),
    'url' => ['index', 'course_id' => $model->course->id]
];

$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'form_options' => [
        'options' => ['enctype' => 'multipart/form-data'],
    ],
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                $model->status == 'active' ? Html::tag(
                    'div',
                    Html::tag('i', null, ['class' => 'mdi mdi-alert-outline me-2']) . Yii::t('app', 'moderation_active_content_update_alert'),
                    ['class' => 'alert alert-warning']
                ) : null,
                'name' => 'text_input',
                [
                    'name' => 'type_id',
                    'type' => 'select',
                    'data' => ArrayHelper::map($types, 'id', 'name'),
                ],
                'description' => 'tinymce',
            ],
        ],
        
        'content' => [
            'label' => Yii::t('app', 'Контент'),
            'attributes' => [
                LibraryAttachmentTemplateWidget::widget(['model' => $model]),
            ],
        ],
        
        'tests_custom' => [
            'label' => Yii::t('app', 'Тесты'),
            'attributes' => [
                LibraryAttachmentTestWidget::widget(['model' => $model]),
            ],
        ],
        
        'tests_pack' => [
            'label' => Yii::t('app', 'Наборы тестов'),
            'attributes' => [
                [
                    'name' => 'default_time_for_test',
                    'type' => 'text_input',
                    'container_options' => ['options' => ['class' => 'mb-3']],
                ],
                LibraryAttachmentTestPackWidget::widget(['model' => $model]),
            ],
        ],
    ],
]);
