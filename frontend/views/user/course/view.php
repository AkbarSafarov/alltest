<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use speedrunner\services\DateService;
use frontend\widgets\LibraryAttachmentTemplateWidget;

$this->title = $course->name;
$this->params['header_class'] = 'header';

?>

<main class="main">
    <div class="course-wrapper">
        <div class="course">
            <div class="container__wrapper">
                <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['user/course/index']]) ?>
                
                <div class="container">
                    <section class="course-info">
                        <div class="container">
                            <div class="course-info__wrapper">
                                <div class="course-info__title-wrapper">
                                    <div class="course-info__category">
                                        <?= ArrayHelper::getValue($course_types, "$course->type.label") ?>
                                    </div>
                                    <h1 class="course-info__title">
                                        <?= $course->name ?>
                                        <span class="title-decor"></span>
                                    </h1>
                                </div>
                                <div class="course-info__img">
                                    <img src="<?= $course->image ?>" alt="">
                                </div>
                                <div class="course-info__content">
                                    <p class="course-info__text">
                                        <?= nl2br($course->short_description) ?>
                                    </p>
                                    <div class="course-info__links">
                                        <?= Html::a(
                                            Yii::t('app', 'Продолжить обучение'),
                                            ['user/unit/view', 'course_id' => $model->id],
                                            [
                                                'class' => 'course-info__link primary-btn',
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                                <ul class="course-info__list">
                                    <li class="course-info__item">
                                        <div class="course-info__item-name">
                                            <?= Yii::t('app', 'Оптимальное время для прохождения:') ?>
                                        </div>
                                        <h4 class="course-info__item-num">
                                            <?= $course->optimal_time ?>
                                        </h4>
                                    </li>
                                    <li class="course-info__item">
                                        <div class="course-info__item-name">
                                            <?= Yii::t('app', 'Доступен до:') ?>
                                        </div>
                                        <h4 class="course-info__item-num">
                                            <?= $course->date_to ?>
                                        </h4>
                                    </li>
                                    <li class="course-info__item">
                                        <div class="course-info__item-name">
                                            <?= Yii::t('app', 'Приобрели курс:') ?>
                                        </div>
                                        <h4 class="course-info__item-num">
                                            <?= $model->course->students_total_quantity ?>
                                        </h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                    
                    <section class="course-description">
                        <div class="container">
                            <h2 class="course-description__title">
                                <?= Yii::t('app', 'Описание курса') ?>
                            </h2>
                            <div class="course-description__content">
                                <div class="course-description__full">
                                    <?= $course->full_description ?>
                                    
                                    <?= LibraryAttachmentTemplateWidget::widget([
                                        'templates' => Yii::$app->services->array->toObjects($model->library_attachment_json['templates']),
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <section class="course-video">
                        <div class="container">
                            <h2 class="course-video__title">
                                <?= Yii::t('app', 'Видео о курсе') ?>
                            </h2>
                            <div class="video" oncontextmenu="return false">
                                <video id="my-video" class="video-js" controls preload="auto" data-setup="{}">
                                    <source src="<?= $course->video ?>" type="video/mp4" />
                                </video>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['user/course/index']]) ?>
</main>
