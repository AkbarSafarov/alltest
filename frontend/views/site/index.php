<?php

$page->registerSeoMeta();

$this->title = Yii::$app->services->settings->site_name;
$this->params['header_class'] = 'header';

?>

<main class="main">
    <div class="major-wrapper">
        <section class="main-slider">
            <div class="container">
                <div class="main-slider__container swiper-container">
                    <div class="main-slider__wrapper swiper-wrapper">
                        <?php foreach ($blocks->slider as $slide) { ?>
                            <div class="main-slider__item main-slider__slide swiper-slide">
                                <h1 class="main-slider__title swiper-no-swiping">
                                    <?= $slide->title ?>
                                    <span class="title-decor"></span>
                                </h1>
                                <div class="main-slider__img">
                                    <img src="<?= $slide->image ?>" alt="">
                                </div>
                                <div class="main-slider__info">
                                    <p class="main-slider__info-text swiper-no-swiping">
                                        <?= nl2br($slide->description) ?>
                                    </p>
                                    <?php if ($slide->link) { ?>
                                        <a href="<?= $slide->link ?>" class="main-slider__link primary-btn">
                                            <span class="_icon-arrow">
                                                <?= Yii::t('app', 'Начать учиться') ?>
                                            </span>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="main-slider__pagination swiper-pagination"></div>
                </div>
            </div>
        </section>
        
        <section class="platform">
            <div class="container">
                <div class="platform__wrapper">
                    <h2 class="platform__title">
                        <?= $blocks->how_it_works_title ?>
                    </h2>
                    
                    <div class="platform__info">
                        <div class="platform__sections">
                            <?php foreach ($blocks->how_it_works_list as $item) { ?>
                                <div class="platform__section submenu">
                                    <button class="platform__section-btn">
                                        <span class="platform__section-text">
                                            <span class="platform__title-icon">
                                                <!-- new icon style -->
                                                <img src="<?= $item->icon ?>" alt="">
                                            </span>
                                            <span class="platform__section-title title-item">
                                                <?= $item->title ?>
                                            </span>
                                        </span>
                                        <span class="platform__icon">
                                            <i class="platform__section-icon _icon-plus"></i>
                                            <i class="platform__section-icon _icon-minus"></i>
                                        </span>
                                    </button>
                                    <div class="platform__section-dropdown">
                                        <p class="platform__section-info">
                                            <?= nl2br($item->description) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <div class="platform__img">
                            <img src="<?= $blocks->how_it_works_image ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="video-main">
            <div class="video-main__container container">
                <div class="video-main__wrapper">
                    <div class="video-main__text">
                        <h2 class="video-main__title">
                            <?= $blocks->video_title ?>
                        </h2>
                        <div class="video-main__info">
                            <?= nl2br($blocks->video_description) ?>
                        </div>
                    </div>
                    <div class="video" oncontextmenu="return false">
                        <video id="my-video" class="video-js" poster="<?= $blocks->video_poster ?>" controls preload="auto" data-setup="{}">
                            <source src="<?= $blocks->video ?>" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="education">
            <div class="container">
                <div class="education__wrapper">
                    <div class="education__img-block">
                        <img src="<?= $blocks->education_image ?>" alt="" class="education__img">
                    </div>
                    
                    <div class="education__info">
                        <h2 class="education__title-main">
                            <?= $blocks->education_title ?>
                        </h2>
                        
                        <div class="education__blocks">
                            <?php foreach ($blocks->education_list as $key => $item) { ?>
                                <div class="education__section submenu">
                                    <button class="education__title">
                                        <span class="education__number title-item">
                                            <?= sprintf('%02d', $key + 1); ?>
                                        </span>
                                        <span class="education__number-dot title-item">.</span>
                                        <span class="title-item">
                                            <?= $item->title ?>
                                        </span>
                                        <span class="education__icon">
                                            <i class="education__section-icon _icon-plus"></i>
                                            <i class="education__section-icon _icon-minus"></i>
                                        </span>
                                    </button>
                                    <p class="education__text">
                                        <?= nl2br($item->description) ?>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <a href="<?= Yii::$app->urlManager->createUrl(['course/courses']) ?>" class="primary-btn education__btn">
                            <?= Yii::t('app', 'Учиться') ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="read-more">
            <div class="container">
                <div class="read-more__wrapper">
                    <h2 class="read-more__title-main">
                        <?= Yii::t('app', 'Новости') ?>
                    </h2>
                    <div class="read-more__container swiper-container" data-mobile="false">
                        <div class="read-more__articles swiper-wrapper">
                            <?= $this->render('@frontend/views/particles/blogs', ['models' => $news]); ?>
                        </div>
                    </div>
                    <div class="read-more__link">
                        <a href="<?= Yii::$app->urlManager->createUrl(['blog/index']) ?>" class="primary-btn read-more__link-btn">
                            <span class="_icon-arrow">
                                <?= Yii::t('app', 'Все новости') ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="reviews">
            <div class="container">
                <div class="reviews__wrapper">
                    <h2 class="reviews__title">
                        <?= $blocks->review_title ?>
                    </h2>
                    
                    <div class="reviews__container swiper-container">
                        <div class="reviews__slider swiper-wrapper">
                            <?php foreach ($blocks->review_list as $item) { ?>
                                <div class="reviews__item swiper-slide">
                                    <div class="reviews__item-bubble">
                                        <h6 class="reviews__item-title">
                                            <?= $item->title ?>
                                        </h6>
                                        <p class="reviews__item-text">
                                            <?= nl2br($item->description) ?>
                                        </p>
                                        <svg class="reviews__triangle" version="1.1" id="Layer_1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 28 20.1" style="enable-background:new 0 0 28 20.1;"
                                            xml:space="preserve">
                                            <path class="st0" d="M0,18.3V0h28L2.9,19.7C1.7,20.6,0,19.8,0,18.3z" />
                                        </svg>
                                    </div>
                                    <div class="reviews__item-account">
                                        <div class="reviews__item-image">
                                            <img src="<?= Yii::$app->services->image->thumb($item->image, [54, 54]) ?>" alt="">
                                        </div>
                                        <div class="reviews__item-info">
                                            <h6 class="reviews__item-name">
                                                <?= $item->full_name ?>
                                            </h6>
                                            <div class="reviews__item-subject">
                                                <?= $item->position ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="reviews__buttons">
                        <button class="tertinary-btn reviews__buttons-prev _icon-arrow swiper-button-prev"></button>
                        <button class="tertinary-btn reviews__buttons-next _icon-arrow swiper-button-next"></button>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="free">
            <div class="free__container container">
                <div class="free__wrapper">
                    <div class="free__info">
                        <h2 class="free__title">
                            <?= $blocks->banner_title ?>
                        </h2>
                        <?php if ($blocks->banner_link) { ?>
                            <a href="<?= $blocks->banner_link ?>" class="primary-btn free__link">
                                <span class="_icon-arrow">
                                    <?= Yii::t('app', 'Подробнее') ?>
                                </span>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="free__img">
                        <img src="<?= $blocks->banner_image ?>" alt="">
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
