<?php

use yii\helpers\Html;
use backend\widgets\grid\GridView;

$this->title = Yii::t('app', 'Страницы');
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
                        Yii::$app->urlManagerFrontend->createUrl(['site/page', 'slug' => $model->slug]),
                        ['target' => '_blank']
                    ),
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
