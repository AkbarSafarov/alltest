<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;

$this->title = Yii::t('app', 'Промокоды');
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
                'percent',
                'key',
                [
                    'attribute' => 'max_activations',
                    'label' => Yii::t('app', 'Активации'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        $result[] = $model->getAttributeLabel('max_activations') . ": $model->max_activations";
                        $result[] = $model->getAttributeLabel('used_activations') . ": $model->used_activations";
                        
                        return implode('<br>', $result);
                    },
                ],
                [
                    'attribute' => 'date_from',
                    'label' => Yii::t('app', 'Даты'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        $result[] = $model->getAttributeLabel('date_from') . ": $model->date_from";
                        $result[] = $model->getAttributeLabel('date_to') . ": $model->date_to";
                        
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
