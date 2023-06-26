<?php

use yii\helpers\Html;
use backend\widgets\grid\GridView;

$this->title = Yii::t('app', 'Языки');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'header' => false,
                    'format' => 'raw',
                    'filter' => false,
                    'value' => fn ($model) => Html::img(Yii::$app->services->image->thumb($model->image, [25, 25], 'resize')),
                    'headerOptions' => [
                        'style' => 'width: 40px;'
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
                'code',
                'is_active:boolean',
                'is_main:boolean',
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
