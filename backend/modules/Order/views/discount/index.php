<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;

$this->title = Yii::t('app', 'Скидки');
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
                [
                    'attribute' => 'packages_tmp',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'packages_tmp',
                        'data' => [$searchModel->packages_tmp => ArrayHelper::getValue(CoursePackage::findOne($searchModel->packages_tmp), 'name')],
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl(['items-list/course-packages', 'show_deleted' => true]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'value' => fn ($model) => implode('<br>', ArrayHelper::getColumn($model->packages, 'name')),
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
