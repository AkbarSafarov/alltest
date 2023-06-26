<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

use backend\modules\User\models\UserCourse;

$this->title = Yii::t('app', 'Просмотр: {value}', ['value' => $model->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
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
                                [
                                    'label' => false,
                                    'format' => 'raw',
                                    'value' => fn($model) => Html::img(
                                        Yii::$app->services->image->thumb($model->image, [200, 200], 'resize', 'profile'),
                                        ['style' => 'max-width: 200px;']
                                    ),
                                ],
                                'username:email',
                                'nickname',
                                [
                                    'attribute' => 'role',
                                    'value' => fn($model) => ArrayHelper::getValue($model->enums->roles(), "$model->role.label"),
                                ],
                                'full_name',
                                [
                                    'attribute' => 'gender',
                                    'value' => fn($model) => ArrayHelper::getValue($model->enums->genders(), "$model->gender.label"),
                                ],
                                'birth_date',
                                'phone',
                                'parent_phone',
                                'address',
                                [
                                    'label' => Yii::t('app', 'Курсы'),
                                    'format' => 'raw',
                                    'value' => fn($model) => Html::a(
                                        Yii::t('app', 'Ссылка'),
                                        ['/course/course/index', 'state' => 'active', 'CourseSearch[teacher_id]' => $model->id],
                                        ['target' => '_blank']
                                    ),
                                    'visible' => $model->role == 'teacher',
                                ],
                                [
                                    'label' => Yii::t('app', 'Предметы'),
                                    'format' => 'raw',
                                    'value' => fn($model) => Html::a(
                                        Yii::t('app', 'Ссылка'),
                                        ['/library/test-subject/index', 'LibraryTestSubjectSearch[teachers_tmp]' => $model->id],
                                        ['target' => '_blank']
                                    ),
                                    'visible' => $model->role == 'teacher',
                                ],
                                [
                                    'label' => Yii::t('app', 'Заказы'),
                                    'format' => 'raw',
                                    'value' => fn($model) => Html::a(
                                        Yii::t('app', 'Ссылка'),
                                        ['/order/order/index', 'OrderSearch[student_id]' => $model->id],
                                        ['target' => '_blank']
                                    ),
                                    'visible' => $model->role == 'student',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <?php if ($model->role == 'student') { ?>
                <div class="card mb-0">
                    <div class="card-header p-0 bg-primary">
                        <h5 class="m-0 position-relative">
                            <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-courses-owned">
                                <?= Yii::t('app', 'Приобретённые курсы') ?>
                                <i class="mdi mdi-chevron-down accordion-arrow"></i>
                            </a>
                        </h5>
                    </div>
                    
                    <div id="tab-courses-owned" class="collapse show">
                        <div class="card-body">
                            <?= $this->render('view/courses', [
                                'courses' => (clone($courses_query))->andWhere(['is', 'demo_datetime_to', null])->asObject()->all(),
                            ]); ?>
                        </div>
                    </div>
                    
                    <div class="card-header p-0 bg-primary">
                        <h5 class="m-0 position-relative">
                            <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-courses-subscribed">
                                <?= Yii::t('app', 'Подписан на курсы') ?>
                                <i class="mdi mdi-chevron-down accordion-arrow"></i>
                            </a>
                        </h5>
                    </div>
                    
                    <div id="tab-courses-subscribed" class="collapse show">
                        <div class="card-body">
                            <?= $this->render('view/courses', [
                                'courses' => (clone($courses_query))->andWhere(['is not', 'demo_datetime_to', null])->asObject()->all(),
                            ]); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
