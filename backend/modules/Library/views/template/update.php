<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Библиотека шаблонов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                [
                    'name' => 'category_id',
                    'type' => 'select2_ajax',
                    'data' => [$model->category_id => ArrayHelper::getValue($model->category, 'name')],
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/library-template-categories']),
                    ],
                ],
                [
                    'name' => 'language_id',
                    'type' => 'select',
                    'data' => ArrayHelper::map($languages, 'id', 'name'),
                    'options' => [
                        'prompt' => Yii::t('app', 'Общий'),
                    ],
                ],
                [
                    'name' => 'image',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'image',
                    ],
                ],
                [
                    'name' => 'content',
                    'type' => 'tinymce',
                    'container_options' => [
                        'hintOptions' => [
                            'hint' => Html::tag('span', Yii::t('app', 'Типы полей:'), ['class' => 'd-block h5']) . implode(
                                '<br>',
                                array_map(
                                    fn ($value, $key) => $value['label'] . " - $key",
                                    $model->enums->inputTypes(),
                                    array_keys($model->enums->inputTypes())
                                )
                            ) . Html::tag(
                                'span',
                                Yii::t('app', 'Пример: {example}', ['example' => '{Заголовок:text_input}']),
                                ['class' => 'd-block h5 mt-3']
                            ),
                        ],
                    ],
                ],
            ],
        ],
    ],
]);
