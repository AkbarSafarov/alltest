<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Moderation\widgets\ModerationStatusTabsWidget;

use backend\modules\Library\models\LibraryTestSubject;
use backend\modules\Library\models\LibraryTestCategory;

$this->title = Yii::t('app', 'Библиотека тестов');
$this->params['breadcrumbs'][] = ['label' => $this->title];

$subjects = LibraryTestSubject::find()->where(['id' => $searchModel->subject_id])->asArray()->all();
$categories = LibraryTestCategory::find()->where(['id' => $searchModel->category_id])->asArray()->all();

if ($table_buttons['change_status']) {
    array_unshift($table_buttons['change_status'], Html::tag(
        'h5',
        Yii::t('app', 'Измеенение статуса'),
        ['class' => 'bg-light p-2 m-0']
    ));
}

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <?= ModerationStatusTabsWidget::widget([
            'model' => $searchModel,
            'available_statuses' => ['development', 'sent_for_moderation', 'rejected', 'active'],
        ]) ?>
        
        <?= $this->render('_search', ['model' => $searchModel]) ?>
        
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
            'buttons' => ArrayHelper::merge($table_buttons['change_status'], [
                Html::tag('h5', Yii::t('app', 'Действия'), ['class' => 'bg-light p-2 m-0']),
                'delete' => $searchModel->state == 'archive' ? [
                    'label' =>  'Восстановить все',
                    'icon_class' => 'fas fa-trash-restore',
                    'action' => ['delete'],
                    'data-trigger' => 'delete-confirmation-button',
                    'css_class' => 'info',
                ] : [
                    'label' =>  'Удалить все',
                    'icon_class' => 'fas fa-trash',
                    'action' => ['delete'],
                    'data-trigger' => 'delete-confirmation-button',
                    'css_class' => 'danger',
                ],
            ]),
            'columns' => [
                [
                    'class' => 'backend\widgets\grid\CheckboxColumn',
                ],
                [
                    'class' => 'backend\widgets\grid\ActionColumn',
                    'template' => '{delete}',
                    'visibleButtons' => [
                        'delete' => function($model) {
                            $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('library/test/delete');
                            $condition_2 = Yii::$app->services->permissions->isDeletable($model);
                            return $condition_1 && $condition_2;
                        },
                    ],
                ],
                [
                    'attribute' => 'id',
                    'headerOptions' => [
                        'style' => 'width: 100px;'
                    ],
                ],
                [
                    'attribute' => 'question',
                    'format' => 'raw',
                    'value' => function ($model) use ($searchModel) {
                        $condition_1 = Yii::$app->services->permissions->isAllowedByRoute('library/test/update');
                        $condition_2 = Yii::$app->services->permissions->isUpdatable($model);
                        $condition_3 = $searchModel->state != 'archive';
                        return $condition_1 && $condition_2 && $condition_3 ? Html::a($model->question, ['update', 'id' => $model->id]) : $model->question;
                    },
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'subject_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'subject_id',
                        'data' => ArrayHelper::map($subjects, 'id', 'name'),
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'multiple' => true,
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl(['items-list/library-test-subjects', 'show_deleted' => true]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'enableSorting' => false,
                    'value' => fn ($model) => ArrayHelper::getValue($model->subject, 'name'),
                ],
                [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'category_id',
                        'data' => ArrayHelper::map($categories, 'id', 'name'),
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'multiple' => true,
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl([
                                    'items-list/library-test-categories',
                                    'subject_id' => $searchModel->subject_id,
                                    'show_deleted' => true,
                                ]),
                                'dataType' => 'json',
                                'delay' => 300,
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ]
                    ]),
                    'value' => fn ($model) => ArrayHelper::getValue($model->category, 'name'),
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'input_type',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'input_type',
                        'data' => ArrayHelper::getColumn($searchModel->enums->inputTypes(), 'label'),
                        'options' => ['placeholder' => ' '],
                        'pluginOptions' => [
                            'multiple' => true,
                            'allowClear' => true,
                        ]
                    ]),
                    'value' => fn ($model) => ArrayHelper::getValue($model->enums->inputTypes(), "$model->input_type.label"),
                    'enableSorting' => false,
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
