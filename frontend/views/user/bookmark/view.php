<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Закладки');

?>

<main class="main has-bg" oncopy="return false">
    <div class="unit">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', [
                'current_route' => ['user/bookmark/view'],
                'course_structure_collapse_button' => true,
            ]) ?>
            
            <div class="container">
                <?php if ($model) { ?>
                    <div class="unit__inner">
                        <div class="nav-menu bookmark-menu">
                            <div class="bookmark-menu__top">
                                <div class="bookmark-menu__title">
                                    <?= $this->title ?>
                                </div>
                                <button class="nav-menu__btn-resize bookmark-menu__btn-resize _icon-arrow-fill"></button>
                            </div>
                            
                            <div class="nav-menu__content bookmark-menu__content">
                                <ul class="bookmark-menu__list">
                                    <?php foreach ($unit_groups as $course_id => $units) { ?>
                                        <li>
                                            <div class="bookmark-menu__list-title">
                                                <?= ArrayHelper::getValue($units, '0.course.course_json.name') ?>
                                            </div>
                                            
                                            <?php foreach ($units as $unit) { ?>
                                                <a href="<?= Yii::$app->urlManager->createUrl(['user/bookmark/view', 'id' => $unit->id]) ?>"
                                                   class="<?= $unit->id == $model->id ? '_active' : null ?>"
                                                >
                                                    <span class="_icon-<?= ArrayHelper::getValue($unit, 'type.icon') ?>">
                                                        <?= $unit->unit_json['name'] ?>
                                                    </span>
                                                </a>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="nav-overlay"></div>
                        
                        <?= $this->render("@frontend/views/user/unit/view/{$model->course->course_json['type']}", [
                            'model' => $model,
                            'bookmark_redirect_url' => Yii::$app->urlManager->createUrl(['user/bookmark/view']),
                        ]); ?>
                    </div>
                <?php } else { ?>
                    <div class="bookmark-empty">
                        <h3 class="bookmark-empty__title">
                            <?= Yii::t('app', 'Список закладок пуст') ?>
                        </h3>
                        
                        <div class="bookmark-empty__img">
                            <img src="/img/content/not-found/bookmarks.png" alt="">
                        </div>
                        <p class="bookmark-empty__text">
                            <?= Yii::t('app', 'Вы можете добавлять интересные для вас юниты и затем читать их здесь') ?>
                        </p>
                        <a class="bookmark-empty__btn primary-btn" href="<?= Yii::$app->urlManager->createUrl(['course/courses']) ?>">
                            <?= Yii::t('app', 'Посмотреть каталог') ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['user/bookmark/view']]) ?>
</main>
