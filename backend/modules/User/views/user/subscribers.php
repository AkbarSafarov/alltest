<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Course\models\Course;

$this->title = Yii::t('app', 'Подписчики');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <?= $this->render('search/subscribers', ['model' => $searchModel]) ?>
        
        <div class="text-sm-end float-end">
            <?= Yii::$app->services->permissions->isAllowedByRoute('integration/export/users-xls') ? Html::a(
                Html::tag('i', null, ['class' => 'fas fa-file-excel me-2']) . Yii::t('app', 'Экспорт в Excel'),
                ['/integration/export/users-xls', 'user_search_params' => $user_search_params],
                [
                    'target' => '_blank',
                    'class' => 'btn btn-warning m-0'
                ]
            ) : null ?>
            
            <?= Html::button(
                Html::tag('i', null, ['class' => 'fas fa-envelope me-2']) . Yii::t('app', 'Отправить сообщение'),
                [
                    'class' => 'btn btn-success m-0',
                    'data-sr-trigger' => 'ajax-button',
                    'data-sr-url' => Yii::$app->urlManager->createUrl(['user/message/create', 'user_search_params' => $user_search_params]),
                    'data-sr-wrapper' => '#main-modal',
                    'data-sr-callback' => "$('#main-modal').modal('show');",
                ]
            ) ?>
        </div>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'backend\widgets\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'visibleButtons' => [
                        'update' => function($model) use ($searchModel) {
                            $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('user/user/update');
                            $condition_2 = $searchModel->state == 'active';
                            return $condition_1 && $condition_2;
                        },
                    ],
                ],
                [
                    'header' => false,
                    'format' => 'raw',
                    'filter' => false,
                    'value' => fn ($model) => Html::img(Yii::$app->services->image->thumb($model->image, [50, 50], 'resize', 'profile')),
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
                    'attribute' => 'username',
                    'format' => 'raw',
                    'value' => function ($model) use ($searchModel) {
                        $condition = Yii::$app->services->permissions->isAllowedByRoute('user/user/view');
                        return $condition ? Html::a($model->username, ['view', 'id' => $model->id]) : $model->username;
                    },
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'full_name',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'phone',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'search_course_id',
                    'label' => $searchModel->getAttributeLabel('search_course_id'),
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'search_course_id',
                        'data' => [$searchModel->search_course_id => ArrayHelper::getValue(Course::findOne($searchModel->search_course_id), 'name')],
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
                    'value' => function($model) {
                        foreach ($model->courses as $course) {
                            $result[] = Html::tag('b', $course->course_json['name']) . ' - ' . $course->demo_datetime_to;
                        }
                        
                        return implode('<br>', $result ?? []);
                    },
                ],
                [
                    'attribute' => 'last_activity',
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'data-toggle' => 'datepicker',
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
