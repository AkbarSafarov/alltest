<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use speedrunner\services\DateService;

use backend\modules\Course\enums\CourseEnums;

$types = CourseEnums::types();

?>

<?php foreach ($models as $model) { ?>
    <?php
        if ($model->demo_datetime_to ? date('Y-m-d H:i:s', strtotime($model->demo_datetime_to)) >= date('Y-m-d H:i:s') : true) {
            $link = Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $model->id]);
        } else {
            $link = Yii::$app->urlManager->createUrl(['course/course-view', 'slug' => $model->course->slug, '#' => 'course-view-cart-add']);
        }
    ?>
    
    <li class="user-courses__item">
        <article class="course-card <?= date('Y-m-d', strtotime($model->date_to)) < date('Y-m-d') ? 'course-card--disabled' : null ?>">
            <div class="course-card__inner">
                <div class="course-card__top">
                    <a class="course-card__top-img" href="<?= $link ?>">
                        <img src="<?= Yii::$app->services->image->thumb($model->course_json['preview_image'], [370, 180]) ?>" alt="">
                    </a>
                    
                    <div class="course-card__top-hover">
                        <a class="course-card__link primary-btn" href="<?= $link ?>">
                            <span class="_icon-arrow">
                                <?= Yii::t('app', 'Продолжить') ?>
                            </span>
                        </a>
                    </div>
                </div>
                
                <div class="course-card__body">
                    <a class="course-card__category" href="<?= $link ?>">
                        <?= ArrayHelper::getValue($types, "{$model->course_json['type']}.label") ?>
                    </a>
                    
                    <a class="course-card__title" href="<?= $link ?>">
                        <h5><?= $model->course_json['name'] ?></h5>
                    </a>
                    
                    <div class="course-card__status">
                        <div class="course-card__status-item">
                            <div class="course-card__status-label">
                                <?= Yii::t('app', 'Пройдено:') ?>
                            </div>
                            <div class="course-card__status-num">
                                <?= $model->progress ?>%
                            </div>
                        </div>
                        <div class="course-card__status-item">
                            <div class="course-card__status-label">
                                <?= Yii::t('app', 'Успеваемость:') ?>
                            </div>
                            <div class="course-card__status-num">
                                <?= $model->performance ?>%
                            </div>
                        </div>
                    </div>
                    
                    <div class="course-card__info">
                        <?php if ($model->last_visit) { ?>
                            <div class="course-card__info-item">
                                <?= Yii::t('app', 'Заходили {date} в {time}', [
                                    'date' => date('d.m.Y', strtotime($model->last_visit)),
                                    'time' => date('H:i', strtotime($model->last_visit)),
                                ]) ?>
                            </div>
                        <?php } ?>
                        
                        <div class="course-card__info-item">
                            <?= Yii::t('app', 'Доступ до {date}', [
                                'date' => $model->date_to,
                            ]) ?>
                        </div>
                        
                        <?php if ($model->demo_datetime_to) { ?>
                            <div class="course-card__info-item">
                                <b>
                                    <?= $model->service->isActive() ? Yii::t('app', 'Демо-доступ закончится {date} в {time}', [
                                        'date' => date('d.m.Y', strtotime($model->demo_datetime_to)),
                                        'time' => date('H:i', strtotime($model->demo_datetime_to)),
                                    ]) : Yii::t('app', 'Для продолжения Вам необходимо купить курс') ?>
                                </b>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <?php if ($model->leagues) { ?>
                        <div class="achievements-leagues" style="display: none;">
                            <ul class="achievements-leagues__list">
                                <?php foreach ($model->leagues as $league) { ?>
                                    <?php if (!$league->league) continue ?>
                                    
                                    <li>
                                        <button class="achievements-leagues__item"
                                                data-sr-trigger="ajax-button"
                                                data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                                    'user/reward/view',
                                                    'id' => $league->league_id,
                                                    'type' => 'league',
                                                ]) ?>"
                                                data-sr-wrapper="#main-modal-wrapper"
                                                data-sr-callback="$('#main-modal').addClass('_active');"
                                        >
                                            <img src="<?= $league->league->icon ?>" alt="">
                                        </button>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    
                    <?php if ($model->achievements) { ?>
                        <div class="achievements__progress" style="display: none;">
                            <ul class="achievements__progress-list">
                                <?php foreach ($model->achievements as $achievement) { ?>
                                    <li class="achievements__progress-item">
                                        <button class="achievements__progress-icon"
                                                data-sr-trigger="ajax-button"
                                                data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                                    'user/reward/view',
                                                    'id' => $achievement->achievement_id,
                                                    'type' => 'achievement',
                                                ]) ?>"
                                                data-sr-wrapper="#main-modal-wrapper"
                                                data-sr-callback="$('#main-modal').addClass('_active');"
                                        >
                                            <img src="<?= $achievement->achievement->icon ?>" alt="">
                                        </button>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="course-card__menu dropdown--wrapper">
                <button class="course-card__menu-btn dropdown--btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle class="course-card__menu-btn--background" cx="12" cy="12"
                            r="12" fill="white" />
                        <circle class="course-card__menu-btn--circle" cx="12" cy="9" r="2"
                            fill="#1B1A1F" />
                        <circle class="course-card__menu-btn--circle" cx="12" cy="15" r="2"
                            fill="#1B1A1F" />
                    </svg>
                </button>
                
                <ul class="course-card__menu-list dropdown--content">
                    <li>
                        <a class="_icon-cross-circle"
                           href="<?= Yii::$app->urlManager->createUrl(['user/course/reset-progress', 'id' => $model->id]) ?>"
                           data-confirm="<?= Yii::t('app', 'Весь прогресс курса онулируется. Вы уверены?') ?>"
                        >
                            <?= Yii::t('app', 'Сбросить прогресс') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </article>
    </li>
<?php } ?>