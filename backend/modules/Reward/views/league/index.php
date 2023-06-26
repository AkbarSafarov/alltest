<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;

$this->title = Yii::t('app', 'Лиги');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function($model) {
                return ['data-id' => $model->id];
            },
            'columns' => [
                [
                    'header' => false,
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::tag(
                            'div',
                            Html::tag('i', null, ['class' => 'fas fa-arrows-alt']),
                            [
                                'class' => 'btn btn-primary table-sorter',
                            ]
                        );
                    },
                ],
                [
                    'header' => false,
                    'format' => 'raw',
                    'filter' => false,
                    'value' => fn ($model) => Html::img(Yii::$app->services->image->thumb($model->icon, [50, 50], 'resize')),
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
                    'value' => fn ($model) => Html::a($model->name, ['update', 'id' => $model->id]),
                ],
                'key',
                'updated_at',
            ],
        ]); ?>
    </div>
</div>

<?= $this->render('@backend/modules/Reward/views/particles/sort_script', ['route' => ['reward/league/sort']]) ?>
