<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use backend\modules\User\models\UserCourse;

$this->title = Yii::t('app', 'Просмотр: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Курсы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

$students_query = UserCourse::find()
    ->joinWith(['user'], false)
    ->andWhere(['user_course.course_id' => $model->id])
    ->select(['user.id', 'user.username'])
    ->groupBy('user.id')
    ->asObject();

$form = new ActiveForm();

?>

<div class="card">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-sm-8 offset-sm-4">
                <div class="text-sm-end">
                    <?php
                        if (
                            Yii::$app->services->permissions->isAllowedByRoute('integration/import/course') ||
                            Yii::$app->services->permissions->isAllowedByRoute('integration/export/course')
                        ) {
                            echo Html::button(
                                Html::tag('i', null, ['class' => 'fas fa-file-archive me-2']) . Yii::t('app', 'Импортировать/экспортировать'),
                                [
                                    'class' => 'btn btn-primary mb-2 me-1',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#course-integration-modal',
                                ]
                            );
                        }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="accordion custom-accordion">
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-information">
                            <?= Yii::t('app', 'Информация') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-information" class="collapse show">
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table m-0'],
                            'attributes' => [
                                'name',
                                [
                                    'attribute' => 'slug',
                                    'format' => 'raw',
                                    'value' => fn($model) => Html::a(
                                        $model->slug,
                                        Yii::$app->urlManagerFrontend->createUrl(['course/course-view', 'slug' => $model->slug]),
                                        ['target' => '_blank']
                                    ),
                                ],
                                [
                                    'attribute' => 'type',
                                    'value' => fn($model) => ArrayHelper::getValue($model->enums->types(), "$model->type.label"),
                                ],
                                [
                                    'attribute' => 'teachers_tmp',
                                    'format' => 'raw',
                                    'value' => fn($model) => implode('<br>', ArrayHelper::getColumn($model->teachers, 'username')),
                                ],
                                [
                                    'attribute' => 'language_id',
                                    'value' => fn($model) => ArrayHelper::getValue($model->language, 'name'),
                                ],
                                [
                                    'label' => Yii::t('app', 'Даты'),
                                    'value' => fn($model) => "$model->date_from - $model->date_to",
                                ],
                                'author',
                                'price:decimal',
                                'optimal_time',
                                'demo_time',
                                'students_start_quantity:decimal',
                                'passing_percent',
                                [
                                    'attribute' => 'certificate_file',
                                    'format' => 'raw',
                                    'value' => fn($model) => $model->certificate_file ? Html::a(
                                        Yii::t('app', 'Ссылка'),
                                        $model->certificate_file,
                                        [
                                            'class' => 'btn-link',
                                            'target' => '_blank',
                                        ]
                                    ) : null,
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-description">
                            <?= Yii::t('app', 'Описание') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-description" class="collapse show">
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table m-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'preview_image',
                                    'format' => 'raw',
                                    'value' => fn($model) => $model->preview_image ? Html::img(
                                        Yii::$app->services->image->thumb($model->preview_image, [300, 300], 'resize'),
                                    ) : null,
                                ],
                                [
                                    'attribute' => 'image',
                                    'format' => 'raw',
                                    'value' => fn($model) => $model->image ? Html::img(
                                        Yii::$app->services->image->thumb($model->image, [300, 300], 'resize'),
                                    ) : null,
                                ],
                                [
                                    'attribute' => 'video',
                                    'format' => 'raw',
                                    'value' => fn($model) => $model->video ? Html::tag(
                                        'video',
                                        null,
                                        [
                                            'src' => $model->video,
                                            'style' => 'width: 300px;',
                                            'controls' => true,
                                        ],
                                    ) : null,
                                ],
                                'short_description:ntext',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-advantages">
                            <?= Yii::t('app', 'Преимущества') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-advantages" class="collapse show">
                    <div class="card-body disabled-block">
                        <?= $this->render('_advantages', [
                            'model' => $model,
                            'form' => $form,
                        ]); ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-authors">
                            <?= Yii::t('app', 'Авторы') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-authors" class="collapse show">
                    <div class="card-body disabled-block">
                        <?= $this->render('_authors', [
                            'model' => $model,
                            'form' => $form,
                        ]); ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-active_students">
                            <?= Yii::t('app', 'Активные студенты') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-active_students" class="collapse show">
                    <div class="card-body">
                        <?= $this->render('_students', [
                            'students' => (clone($students_query))->andWhere([
                                'and',
                                ['is', 'user_course.demo_datetime_to', null],
                                ['>=', 'user_course.last_visit', date('Y-m-d H:i:s', strtotime('-1 week'))],
                            ])->asObject()->all(),
                            'user_search_params' => [
                                'search_course_id' => $model->id,
                                'search_course_status' => 'active',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-inactive_students">
                            <?= Yii::t('app', 'Неактивные студенты') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-inactive_students" class="collapse show">
                    <div class="card-body">
                        <?= $this->render('_students', [
                            'students' => (clone($students_query))->andWhere([
                                'and',
                                ['is', 'user_course.demo_datetime_to', null],
                                [
                                    'or',
                                    ['<', 'user_course.last_visit', date('Y-m-d H:i:s', strtotime('-1 week'))],
                                    ['is', 'user_course.last_visit', null],
                                ],
                            ])->asObject()->all(),
                            'user_search_params' => [
                                'search_course_id' => $model->id,
                                'search_course_status' => 'inactive',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-finished_students">
                            <?= Yii::t('app', 'Окончившие студенты') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-finished_students" class="collapse show">
                    <div class="card-body">
                        <?= $this->render('_students', [
                            'students' => (clone($students_query))->andWhere(['user_course.progress' => 100])->asObject()->all(),
                            'user_search_params' => [
                                'search_course_id' => $model->id,
                                'search_course_status' => 'finished',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-subscribers">
                            <?= Yii::t('app', 'Подписчики') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-subscribers" class="collapse show">
                    <div class="card-body">
                        <?= $this->render('_students', [
                            'students' => (clone($students_query))->andWhere(['is not', 'user_course.demo_datetime_to', null])->asObject()->all(),
                            'user_search_params' => [
                                'search_course_id' => $model->id,
                                'search_course_status' => 'subscribers',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="course-integration-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <?= Yii::t('app', 'course_integration_title') ?>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <?= Yii::t('app', 'course_integration_description') ?>
            </div>
            
            <div class="modal-footer">
                <?= Yii::$app->services->permissions->isAllowedByRoute('integration/import/course') ? Html::button(
                    Html::tag('i', null, ['class' => 'fas fa-file-import me-2']) . Yii::t('app', 'Импортировать'),
                    [
                        'class' => 'btn btn-success mb-2 me-1',
                        'data-sr-trigger' => 'ajax-button',
                        'data-sr-url' => Yii::$app->urlManager->createUrl(['integration/import/course', 'id' => $model->id]),
                        'data-sr-wrapper' => '#main-modal',
                        'data-sr-callback' => "$('#main-modal').modal('show');",
                    ]
                ) : null ?>
                
                <?= Yii::$app->services->permissions->isAllowedByRoute('integration/export/course') ? Html::a(
                    Html::tag('i', null, ['class' => 'fas fa-file-export me-2']) . Yii::t('app', 'Экспортировать'),
                    ['/integration/export/course', 'id' => $model->id],
                    [
                        'class' => 'btn btn-warning mb-2',
                    ]
                ) : null ?>
            </div>
        </div>
    </div>
</div>
