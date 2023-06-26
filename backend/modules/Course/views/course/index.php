<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\User\models\User;

$this->title = Yii::t('app', 'Курсы');
$this->params['breadcrumbs'][] = ['label' => $this->title];

$teachers = User::find()->where(['id' => $searchModel->teachers_tmp])->asArray()->all();

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <?= $this->render('_search', ['model' => $searchModel]) ?>
        
        <div class="text-sm-end float-end">
            <?= Yii::$app->services->permissions->isAllowedByRoute('integration/check/course-certificate-file') ? Html::a(
                Html::tag('i', null, ['class' => 'fas fa-file-alt me-2']) . Yii::t('app', 'Протестировать сертификат'),
                '#',
                [
                    'class' => 'btn btn-warning m-0',
                    'data-sr-trigger' => 'ajax-button',
                    'data-sr-url' => Yii::$app->urlManager->createUrl(['integration/check/course-certificate-file']),
                    'data-sr-wrapper' => '#main-modal',
                    'data-sr-callback' => "$('#main-modal').modal('show');",
                ]
            ) : null ?>
            
            <?= Yii::$app->services->permissions->isAllowedByRoute('course/course/create') ? Html::a(
                Html::tag('i', null, ['class' => 'fas fa-plus-square me-2']) . Yii::t('app', 'Создание'),
                ['create'],
                ['class' => 'btn btn-info m-0']
            ) : null ?>
        </div>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'backend\widgets\grid\ActionColumn',
                    'template' => '{view} {update} {preview} {publish} {clone} {delete}',
                    'buttons' => [
                        'preview' => function($url, $model, $key) {
                            return Html::a(
                                Html::tag('i', null, ['class' => 'mdi mdi-link-variant']),
                                ['preview/course', 'id' => $model->id],
                                [
                                    'class' => 'action-icon',
                                    'target' => '_blank',
                                    'title' => Yii::t('app', 'Предпросмотр'),
                                    'data' => [
                                        'toggle' => 'tooltip',
                                        'pjax' => 0,
                                    ],
                                ]
                            );
                        },
                        'publish' => function($url, $model, $key) {
                            return Html::a(
                                Html::tag('i', null, ['class' => 'mdi mdi-file-send']),
                                ['publish', 'id' => $model->id],
                                [
                                    'class' => 'action-icon',
                                    'title' => Yii::t('app', 'Опубликовать'),
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => Yii::t('app', 'Вы уверены?'),
                                        'toggle' => 'tooltip',
                                        'pjax' => 0,
                                    ],
                                ]
                            );
                        },
                        'clone' => function($url, $model, $key) {
                            return Html::a(
                                Html::tag('i', null, ['class' => 'mdi mdi-content-copy']),
                                ['clone', 'id' => $model->id],
                                [
                                    'class' => 'action-icon',
                                    'title' => Yii::t('app', 'Клонировать'),
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => Yii::t('app', 'Вы уверены?'),
                                        'toggle' => 'tooltip',
                                        'pjax' => 0,
                                    ],
                                ]
                            );
                        },
                    ],
                    'visibleButtons' => [
                        'preview' => function($model) use ($searchModel) {
                            return in_array($searchModel->state, ['development', 'active']);
                        },
                        'update' => function($model) use ($searchModel) {
                            $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('course/course/update');
                            $condition_2 = Yii::$app->services->permissions->isUpdatable($model);
                            return $condition_1 && $condition_2;
                        },
                        'publish' => function($model) use ($searchModel) {
                            return in_array($searchModel->state, ['active']);
                        },
                        'delete' => function($model) use ($searchModel) {
                            $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('course/course/delete');
                            $condition_2 = Yii::$app->services->permissions->isDeletable($model);
                            return $condition_1 && $condition_2;
                        },
                    ],
                ],
                [
                    'header' => false,
                    'format' => 'raw',
                    'filter' => false,
                    'value' => fn ($model) => Html::img(Yii::$app->services->image->thumb($model->preview_image, [50, 50], 'resize')),
                    'headerOptions' => [
                        'style' => 'width: 65px;'
                    ],
                ],
                [
                    'attribute' => 'id',
                    'format' => 'raw',
                    'headerOptions' => [
                        'style' => 'width: 100px;'
                    ],
                ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function($model) use ($searchModel) {
                        $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('course/unit/index');
                        $condition_2 = in_array($searchModel->state, ['development', 'active']);
                        return $condition_1 && $condition_2 ? Html::a($model->name, ['unit/index', 'course_id' => $model->id]) : $model->name;
                    },
                ],
                [
                    'attribute' => 'slug',
                    'format' => 'raw',
                    'value' => fn ($model) => Html::a(
                        $model->slug,
                        Yii::$app->urlManagerFrontend->createUrl(['course/course-view', 'slug' => $model->slug]),
                        ['target' => '_blank']
                    ),
                ],
                [
                    'attribute' => 'type',
                    'format' => 'raw',
                    'filter' => ArrayHelper::getColumn($searchModel->enums->types(), 'label'),
                    'value' => fn ($model) => ArrayHelper::getValue($model->enums->types(), "$model->type.label"),
                ],
                [
                    'attribute' => 'teachers_tmp',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'teachers_tmp',
                        'data' => ArrayHelper::map($teachers, 'id', 'username'),
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'multiple' => true,
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
                [
                    'attribute' => 'language_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'language_id',
                        'data' => ArrayHelper::map($languages, 'id', 'name'),
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'multiple' => true,
                            'allowClear' => true,
                        ]
                    ]),
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
                'price:decimal',
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
