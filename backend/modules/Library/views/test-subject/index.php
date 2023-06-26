<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\User\models\User;

$this->title = Yii::t('app', 'Предметы');
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
                [
                    'attribute' => 'teachers_tmp',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'teachers_tmp',
                        'data' => [$searchModel->teachers_tmp => ArrayHelper::getValue(User::findOne($searchModel->teachers_tmp), 'username')],
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl(['items-list/users', 'role' => 'teacher', 'show_deleted' => true]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'value' => fn ($model) => implode('<br>', ArrayHelper::getColumn($model->teachers, 'username')),
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
