<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$user_course_structure = Yii::$app->session->get("user_course_structure_$course->id", []);

?>

<div class="nav-menu">
    <div class="nav-menu__top">
        <button class="nav-menu__btn _icon-arrow close-all-nav-menu"></button>
        <button class="nav-menu__btn-resize _icon-arrow-fill"></button>
    </div>
    
    <div class="nav-menu__content">
        <div class="nav-menu__title">
            <?= $course->course_json['name'] ?>
        </div>
        
        <ul class="nav-menu__status">
            <li class="nav-menu__status-item">
                <div class="nav-menu__status-name">
                    <?= Yii::t('app', 'Пройдено:') ?>
                </div>
                <div class="nav-menu__status-num">
                    <?= $course->progress ?>%
                </div>
            </li>
            
            <li class="nav-menu__status-item">
                <div class="nav-menu__status-name">
                    <?= Yii::t('app', 'Успеваемость:') ?>
                </div>
                <div class="nav-menu__status-num">
                    <?= $course->performance ?>%
                </div>
            </li>
            
            <?php if ($course->demo_datetime_to) { ?>
                <li class="nav-menu__status-item">
                    <div class="nav-menu__status-name">
                        <?= Yii::t('app', 'Окончание демо-доступа:') ?>
                    </div>
                    <div class="nav-menu__status-num">
                        <?= $course->demo_datetime_to ?>
                    </div>
                </li>
            <?php } ?>
        </ul>
        
        <div class="nav-menu__achievements">
            <?php if ($course->leagues) { ?>
                <div class="achievements-leagues">
                    <ul class="achievements-leagues__list">
                        <?php foreach ($course->leagues as $league) { ?>
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
            
            <?php if ($course->achievements) { ?>
                <div class="achievements__progress">
                    <ul class="achievements__progress-list">
                        <?php foreach ($course->achievements as $achievement) { ?>
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
        
        <a class="nav-menu__link" href="<?= Yii::$app->urlManager->createUrl(['user/course/view', 'id' => $course->id]) ?>">
            <span class="_icon-arrow">
                <?= Yii::t('app', 'О курсе') ?>
            </span>
        </a>
        
        <ul class="nav-menu__list">
            <?php foreach ($course->service->unitsTree() as $section) { ?>
                <?php $parent_class = $section->is_current ? 'current' : ($section->is_unlocked ? 'finished' : 'unfinished'); ?>
                <li class="nav-menu__item section-<?= $parent_class ?> submenu">
                    <button class="nav-menu__item-btn _icon-arrow"
                            data-sr-trigger="ajax-button"
                            data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                'user/unit/structure-toggle',
                                'course_id' => $course->id,
                                'id' => $section->id,
                            ]) ?>"
                    >
                        <span class="nav-menu__item-name">
                            <?= $section->name ?>
                        </span>
                    </button>
                    
                    <?php if (isset($section->children)) { ?>
                        <ul class="nav-menu__sublist <?= in_array($section->id, $user_course_structure) ? '_active' : null ?>">
                            <?php foreach ($section->children as $subsection) { ?>
                                <li class="nav-menu__subitem submenu">
                                    <button class="nav-menu__subitem-btn _icon-arrow"
                                            data-sr-trigger="ajax-button"
                                            data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                                'user/unit/structure-toggle',
                                                'course_id' => $course->id,
                                                'id' => $subsection->id,
                                            ]) ?>"
                                    >
                                        <span class="nav-menu__subitem-name">
                                            <?= $subsection->name ?>
                                        </span>
                                    </button>
                                    
                                    <?php if (isset($subsection->children)) { ?>
                                        <ul class="nav-menu__sublink-list <?= in_array($subsection->id, $user_course_structure) ? '_active' : null ?>">
                                            <?php foreach ($subsection->children as $unit) { ?>
                                                <?php $icon = $unit->is_current ? 'clock-fill' : ($unit->is_passed ? 'check-mark' : null) ?>
                                                
                                                <li class="nav-menu__sublink-item">
                                                    <?php
                                                        $css_class = $unit->is_current ? ' _active' : null;
                                                        $css_class .= !$unit->is_unlocked ? ' _disabled' : null;
                                                    ?>
                                                    
                                                    <a class="nav-menu__sublink<?= $css_class ?>"
                                                       href="<?= Yii::$app->urlManager->createUrl([
                                                           'user/unit/view',
                                                           'course_id' => $course->id,
                                                           'id' => $unit->id,
                                                       ]) ?>"
                                                    >
                                                        <img src="<?= $unit->icon ?>" alt="">
                                                        <div class="nav-menu__sublink-inner">
                                                            <div class="nav-menu__sublink-name">
                                                                <?= $unit->name ?>
                                                            </div>
                                                            <div class="nav-menu__sublink-status <?= $icon && !$unit->performance ? "_icon-$icon" : null ?>">
                                                                <?= $unit->performance ? "$unit->performance%" : null ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
    
    <div class="nav-menu__bottom">
        <button class="nav-menu__btn _icon-arrow close-all-nav-menu"></button>
    </div>
</div>
