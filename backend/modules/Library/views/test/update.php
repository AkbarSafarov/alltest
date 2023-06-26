<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

use backend\modules\Library\models\LibraryTestCategory;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Библиотека тестов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

if ($model->status == 'active') {
    echo Html::tag(
        'div',
        Html::tag('i', null, ['class' => 'mdi mdi-alert-outline me-2']) . Yii::t('app', 'moderation_active_content_update_alert'),
        ['class' => 'alert alert-warning']
    );
}

echo UpdateWidget::widget([
    'model' => $model,
    'save_buttons' => ['save_create', 'save_update', 'save'],
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                [
                    'name' => 'subject_id',
                    'type' => 'select2_ajax',
                    'data' => [$model->subject_id => ArrayHelper::getValue($model->subject, 'name')],
                    'options' => [
                        'pluginOptions' => [
                            'allowClear' => false,
                        ],
                        'options' => [
                            'data-sr-trigger' => 'ajax-change',
                            'data-sr-url' => Yii::$app->urlManager->createUrl([
                                'library/test-category/filtered-by-subject',
                                'category_ids' => [$model->category_id],
                            ]),
                            'data-sr-wrapper' => '#librarytest-category_id',
                        ],
                    ],
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/library-test-subjects']),
                    ],
                ],
                [
                    'name' => 'category_id',
                    'type' => 'select',
                    'options' => [
                        'data-toggle' => 'select2',
                        'prompt' => '',
                    ],
                ],
                [
                    'name' => 'input_type',
                    'type' => 'select',
                    'data' => ArrayHelper::getColumn($model->enums->inputTypes(), 'label'),
                    'options' => [
                        'data-sr-trigger' => 'ajax-change',
                        'data-sr-url' => Yii::$app->urlManager->createUrl(['library/test/options']),
                        'data-sr-wrapper' => '#librarytest-options-wrapper',
                    ],
                ],
                'question' => 'tinymce',
                
                Html::beginTag('div', ['id' => 'librarytest-options-wrapper']),
                $model->input_type ? [
                    'name' => false,
                    'type' => 'render',
                    'view' => "update/options/$model->input_type",
                    'params' => [
                        'name_prefix' => 'LibraryTest[options]',
                        'sequence_file_input_urls' => [
                            'sort' => ['library/test/file-sort', 'id' => $model->id],
                            'delete' => ['library/test/file-delete', 'id' => $model->id],
                        ],
                    ],
                ] : null,
                
                Html::label(Html::error($model, 'options', ['tag' => false]), null, ['class' => 'd-block mb-2 text-danger']),
                Html::endTag('div'),
            ],
        ],
    ],
]);

?>


<?= $this->registerJs("
    $('#librarytest-subject_id').trigger('change');
    " . ($model->isNewRecord ? "$('#librarytest-input_type').trigger('change');" : null)
) ?>
