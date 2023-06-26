<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;

use backend\modules\User\widgets\UserRoleTabsWidget;

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <?= UserRoleTabsWidget::widget([
            'model' => $searchModel,
            'roles' => Yii::$app->user->identity->role == 'admin' ? ['admin', 'moderator', 'teacher', 'student'] : ['teacher', 'student'],
        ]) ?>
        
        <?= $this->render('search/index', ['model' => $searchModel]) ?>
        
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
            
            <?= Yii::$app->services->permissions->isAllowedByRoute('user/user/create') ? Html::a(
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
                    'attribute' => 'nickname',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'role',
                    'filter' => ArrayHelper::getColumn($searchModel->enums->roles(), 'label'),
                    'value' => fn ($model) => ArrayHelper::getValue($model->enums->roles(), "$model->role.label"),
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
                    'attribute' => 'last_activity',
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'data-toggle' => 'datepicker',
                    ],
                ],
                'updated_at',
            ],
        ]); ?>
    </div>
</div>
