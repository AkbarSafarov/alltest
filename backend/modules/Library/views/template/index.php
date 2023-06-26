<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Библиотека шаблонов');
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
                    'template' => '{delete}',
                ],
                [
                    'header' => false,
                    'format' => 'raw',
                    'filter' => false,
                    'value' => fn ($model) => Html::img(Yii::$app->services->image->thumb($model->image, [50, 50], 'resize')),
                    'headerOptions' => [
                        'style' => 'width: 65px;'
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
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'category_id',
                        'data' => [$searchModel->category_id => ArrayHelper::getValue($searchModel->category, 'name')],
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl(['items-list/library-template-categories', 'show_deleted' => true]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'value' => fn ($model) => ArrayHelper::getValue($model->category, 'name'),
                ],
                [
                    'attribute' => 'language_id',
                    'format' => 'raw',
                    'filter' => ArrayHelper::map($languages, 'id', 'name'),
                    'value' => fn ($model) => ArrayHelper::getValue($model->language, 'name', Yii::t('app', 'Общий')),
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
