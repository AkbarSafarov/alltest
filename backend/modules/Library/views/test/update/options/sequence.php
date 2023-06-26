<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use yii\web\JsExpression;

$answers = ArrayHelper::getValue($model->options, 'answers', []);

echo $form->field($model, 'options[answers]', [
    'template' => '{label}{hint}{input}',
    'options' => ['class' => null],
])->widget(FileInput::className(), [
    'options' => [
        'accept' => 'image/*',
        'name' => "{$name_prefix}[answers][]",
        'id' => Html::getInputId($model, ($model->id ?? 0) . "options[answers]"),
        'multiple' => true,
        'value' => false,
    ],
    'pluginOptions' => array_merge(Yii::$app->params['fileInput_plugin_options'], [
        'deleteUrl' => Yii::$app->urlManager->createUrl($sequence_file_input_urls['delete']),
        'initialPreview' => $answers,
        'initialPreviewConfig' => ArrayHelper::getColumn($answers, fn ($value) => ['key' => $value, 'downloadUrl' => $value]),
    ]),
    'pluginEvents' => [
        'filesorted' => new JsExpression("function(event, params) {
            $.post('" . Yii::$app->urlManager->createUrl($sequence_file_input_urls['sort']) . "', {sort: params});
        }"),
    ],
])->label(Yii::t('app', 'Правильная последовательность'));
