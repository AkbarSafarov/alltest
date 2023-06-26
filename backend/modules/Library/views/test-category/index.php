<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Темы предметов');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <div class="text-sm-end float-end">
            <?= Html::a(
                Html::tag('i', null, ['class' => 'fas fa-plus-square me-2']) . Yii::t('app', 'Создание'),
                ['create'],
                ['class' => 'btn btn-info m-0']
            ) ?>
        </div>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'backend\widgets\grid\ActionColumn',
                    'template' => '{import} {export} {delete}',
                    'buttons' => [
                        'import' => function($url, $model, $key) {
                            return Html::a(
                                Html::tag('i', null, ['class' => 'mdi mdi-file-import']),
                                ['/integration/import/library-test-category', 'id' => $model->id],
                                [
                                    'class' => 'action-icon',
                                    'target' => '_blank',
                                    'title' => Yii::t('app', 'Импортировать'),
                                    'data-toggle' => 'tooltip',
                                    'data-pjax' => 0,
                                    'data-sr-trigger' => 'ajax-button',
                                    'data-sr-url' => Yii::$app->urlManager->createUrl(['integration/import/library-test-category', 'id' => $model->id]),
                                    'data-sr-wrapper' => '#main-modal',
                                    'data-sr-callback' => "$('#main-modal').modal('show');",
                                ]
                            );
                        },
                        'export' => function($url, $model, $key) {
                            return Html::a(
                                Html::tag('i', null, ['class' => 'mdi mdi-file-export']),
                                ['/integration/export/library-test-category', 'id' => $model->id],
                                [
                                    'class' => 'action-icon',
                                    'title' => Yii::t('app', 'Экспортировать'),
                                    'data' => [
                                        'toggle' => 'tooltip',
                                        'pjax' => 0,
                                    ],
                                ]
                            );
                        },
                    ],
                    'visibleButtons' => [
                        'import' => function($model) {
                            return Yii::$app->services->permissions->isAllowedByRoute('integration/import/library-test-category');
                        },
                        'export' => function($model) {
                            return Yii::$app->services->permissions->isAllowedByRoute('integration/export/library-test-category');
                        },
                        'delete' => function($model) {
                            $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('library/test-category/delete');
                            $condition_2 = Yii::$app->services->permissions->isDeletable($model);
                            return $condition_1 && $condition_2;
                        },
                    ],
                ],
                [
                    'attribute' => 'id',
                    'headerOptions' => [
                        'style' => 'width: 100px;'
                    ],
                ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) use ($searchModel) {
                        return $searchModel->state != 'archive' ? Html::a($model->name, ['update', 'id' => $model->id]) : $model->name;
                    },
                ],
                [
                    'attribute' => 'subject_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'subject_id',
                        'data' => [$searchModel->subject_id => ArrayHelper::getValue($searchModel->subject, 'name')],
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl(['items-list/library-test-subjects', 'show_deleted' => true]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'value' => fn ($model) => ArrayHelper::getValue($model->subject, 'name'),
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
