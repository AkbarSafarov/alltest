<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Курсы пользователя');

$pagination = $courses->pagination;

?>

<main class="main has-bg">
    <div class="user-courses">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop') ?>
            
            <div class="container">
                <div class="user-courses__content">
                    <div class="user-courses__top">
                        <h3 class="user-courses__title">
                            <?= $this->title ?>
                        </h3>
                        <div class="user-courses__search">
                            <div class="search-input">
                                <div class="input">
                                    <?= Html::beginForm(['user/course/index', 'type' => $type], 'get') ?>
                                    <?= Html::textInput('user_course_search', Yii::$app->request->get('user_course_search'), [
                                        'placeholder' => Yii::t('app', 'Поиск по курсам пользователя'),
                                    ]) ?>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="user-courses__dropdown">
                            <div class="dropdown-course dropdown--wrapper">
                                <button class="dropdown-course__btn _icon-arrow dropdown--btn dropdown--btn-arrow">
                                    <?= ArrayHelper::getValue($types, "$type.label") ?>
                                </button>
                                
                                <ul class="dropdown-course__list dropdown--content">
                                    <?php foreach ($types as $key => $t) { ?>
                                        <li>
                                            <a href="<?= Yii::$app->urlManager->createUrl([
                                                'user/course/index',
                                                'user_course_search' => Yii::$app->request->get('user_course_search'),
                                                'type' => $key,
                                            ]) ?>"
                                               class="<?= $key == $type ? '_active' : null ?>"
                                            >
                                                <div class="dropdown-course__title">
                                                    <?= $t['label'] ?>
                                                </div>
                                                <div class="dropdown-course__num">
                                                    <?= ArrayHelper::getValue($type_quantities, $key) ?>
                                                </div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($courses->totalCount) { ?>
                        <ul class="user-courses__items" id="user-courses-lazy-load-result">
                            <?= $this->render('@frontend/views/particles/user_courses', ['models' => $courses->getModels()]); ?>
                        </ul>
                        
                        <?php if ($pagination->pageSize * ($pagination->page + 1) < $pagination->totalCount) { ?>
                            <button type="button"
                               class="news__btn primary-btn"
                               data-toggle="lazy-load-button"
                               data-action="<?= Yii::$app->urlManager->createUrl([
                                    'user/course/index',
                                    'type' => $type,
                                ]) ?>"
                               data-result="#user-courses-lazy-load-result"
                               data-offset="1"
                               data-offset_max="<?= ceil($pagination->totalCount / $pagination->pageSize) - 1 ?>"
                            >
                                <span class="_icon-arrow">
                                    <?= Yii::t('app', 'Больше курсов') ?>
                                </span>
                            </button>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="search-error">
                            <h3 class="search-error__title">
                                <?= Yii::t('app', 'Ничего не найдено') ?>
                            </h3>
                            
                            <div class="search-error__img">
                                <img src="/img/content/not-found/user-courses.png" alt="">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile') ?>
</main>
