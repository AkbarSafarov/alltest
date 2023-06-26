<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Новости');
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
                    'class' => 'backend\widgets\grid\CheckboxColumn',
                ],
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
                    ]
                ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) use ($searchModel) {
                        return $searchModel->state != 'archive' ? Html::a($model->name, ['update', 'id' => $model->id]) : $model->name;
                    },
                ],
                [
                    'attribute' => 'slug',
                    'format' => 'raw',
                    'value' => fn ($model) => Html::a(
                        $model->slug,
                        Yii::$app->urlManagerFrontend->createUrl(['blog/view', 'slug' => $model->slug]),
                        ['target' => '_blank']
                    ),
                ],
                [
                    'attribute' => 'published_at_from',
                    'label' => Yii::t('app', 'Опубликовано'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        $result[] = $model->getAttributeLabel('published_at_from') . ": $model->published_at_from";
                        $result[] = $model->getAttributeLabel('published_at_to') . ": $model->published_at_to";
                        
                        return implode('<br>', $result);
                    },
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'data-toggle' => 'datepicker',
                    ]
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
