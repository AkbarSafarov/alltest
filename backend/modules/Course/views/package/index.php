<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Course\models\Course;

$this->title = Yii::t('app', 'Пакеты курсов');
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
                    'visibleButtons' => [
                        'delete' => function($model) use ($searchModel) {
                            $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('course/package/delete');
                            $condition_2 = Yii::$app->services->permissions->isDeletable($model);
                            return $condition_1 && $condition_2;
                        },
                    ],
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
                'price:decimal',
                [
                    'attribute' => 'language_id',
                    'format' => 'raw',
                    'filter' => ArrayHelper::map($languages, 'id', 'name'),
                    'value' => fn ($model) => ArrayHelper::getValue($model->language, 'name'),
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
                [
                    'attribute' => 'courses_tmp',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'courses_tmp',
                        'data' => [$searchModel->courses_tmp => ArrayHelper::getValue(Course::findOne($searchModel->courses_tmp), 'name')],
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl(['items-list/courses', 'show_deleted' => true]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'value' => fn ($model) => implode('<br>', ArrayHelper::getColumn($model->courses, 'name')),
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
