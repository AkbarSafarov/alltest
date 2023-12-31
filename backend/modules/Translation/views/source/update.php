<?php

use yii\helpers\Html;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->message]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Переводы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

foreach ($model->service->activeTranslations() as $t) {
    $attributes[] = [
        'name' => "translations_tmp[$t->language]",
        'type' => 'text_area',
        'container_options' => [
            'template' => "{beginLabel} {$t->lang->name} {endLabel} {input}{hint}{error}",
        ],
        'options' => [
            'value' => $t->translation,
        ],
    ];
}

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => $attributes ?? [],
        ],
    ],
]);
