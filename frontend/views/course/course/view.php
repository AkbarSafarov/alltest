<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\widgets\LibraryAttachmentTemplateWidget;

$model->registerSeoMeta();

$this->title = $model->name;
$this->params['header_class'] = 'header';

$is_in_cart = ArrayHelper::getValue(Yii::$app->services->cart->cart, "products.Course-$model->id");

?>

<main class="main" oncopy="return false">
    <div class="course-wrapper">
        <div class="course">
            <div class="container__wrapper">
                <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['course/courses']]) ?>
                
                <div class="container">
                    <section class="course-info">
                        <div class="container">
                            <div class="course-info__wrapper">
                                <div class="course-info__title-wrapper">
                                    <div class="course-info__category">
                                        <?= ArrayHelper::getValue($model->enums->types(), "$model->type.label") ?>
                                    </div>
                                    <h1 class="course-info__title">
                                        <?= $model->name ?>
                                        <span class="title-decor"></span>
                                    </h1>
                                </div>
                                
                                <?php if ($model->image) { ?>
                                    <div class="course-info__img">
                                        <img src="<?= $model->image ?>" alt="">
                                    </div>
                                <?php } ?>
                                
                                <div class="course-info__content">
                                    <p class="course-info__text">
                                        <?= nl2br($model->short_description) ?>
                                    </p>
                                    <div class="course-info__links">
                                        <?php
                                            if ($model->demo_time && !$model->userCourse) {
                                                echo Html::a(
                                                    Yii::t('app', 'Хочу учиться'),
                                                    ['order/get-demo', 'id' => $model->id],
                                                    [
                                                        'class' => 'course-info__link primary-btn',
                                                    ]
                                                );
                                            }
                                        ?>
                                        
                                        <a class="course-info__link secondary-btn" href="#course-units">
                                            <?= Yii::t('app', 'Программа') ?>
                                        </a>
                                    </div>
                                </div>
                                <ul class="course-info__list">
                                    <li class="course-info__item">
                                        <div class="course-info__item-inner">
                                            <div class="course-info__item-name">
                                                <?= Yii::t('app', 'Оптимальное время для прохождения:') ?>
                                            </div>
                                            <h4 class="course-info__item-num">
                                                <?= $model->optimal_time ?>
                                            </h4>
                                        </div>
                                        <div class="course-info__tooltip">
                                            <?= Yii::t('app', 'Длительность прохождения курса') ?>
                                        </div>
                                    </li>
                                    <li class="course-info__item">
                                        <div class="course-info__item-inner">
                                            <div class="course-info__item-name">
                                                <?= Yii::t('app', 'Доступен до:') ?>
                                            </div>
                                            <h4 class="course-info__item-num">
                                                <?= $model->date_to ?>
                                            </h4>
                                        </div>
                                        <div class="course-info__tooltip">
                                            <?= Yii::t('app', 'Курс доступен') ?>
                                        </div>
                                    </li>
                                    <li class="course-info__item">
                                        <div class="course-info__item-inner">
                                            <div class="course-info__item-name">
                                                <?= Yii::t('app', 'Приобрели курс:') ?>
                                            </div>
                                            <h4 class="course-info__item-num">
                                                <?= $model->students_total_quantity ?>
                                            </h4>
                                        </div>
                                        <div class="course-info__tooltip">
                                            <?= Yii::t('app', 'Пользователи приобретенные курс') ?>
                                        </div>
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
                                    <?= $model->full_description ?>
                                    
                                    <?= LibraryAttachmentTemplateWidget::widget([
                                        'templates' => $model->attachmentTemplates,
                                    ]) ?>
                                </div>
                                
                                <ul class="course-description__steps">
                                    <?php foreach ($model->advantages as $advantage) { ?>
                                        <li>
                                            <div class="course-description__step">
                                                <div class="course-description__step-icon">
                                                    <!-- new icon style -->
                                                    <img src="<?= $advantage->icon ?>" alt="">
                                                </div>
                                                <p class="course-description__step-text">
                                                    <?= $advantage->name ?>
                                                </p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </section>
                    
                    <?php if ($model->video) { ?>
                        <section class="course-video">
                            <div class="container">
                                <h2 class="course-video__title">
                                    <?= Yii::t('app', 'Видео о курсе') ?>
                                </h2>
                                <div class="video" oncontextmenu="return false">
                                    <video id="my-video" class="video-js" controls preload="auto" data-setup="{}">
                                        <source src="<?= $model->video ?>" type="video/mp4" />
                                    </video>
                                </div>
                            </div>
                        </section>
                    <?php } ?>
                    
                    <section class="course-program" id="course-units">
                        <div class="container">
                            <h2 class="course-program__title">
                                <?= Yii::t('app', 'Программа курса') ?>
                            </h2>
                            <ul class="course-program__list">
                                <?php foreach ($sections as $section) { ?>
                                    <li class="course-program__item submenu">
                                        <button class="course-program__item-btn">
                                            <span class="course-program__item-wrapper">
                                                <span class="course-program__item-num"></span>
                                                <span class="course-program__item-inner">
                                                    <span class="course-program__item-title">
                                                        <?= $section->name ?>
                                                    </span>
                                                    <?php if ($section->children) { ?>
                                                        <span class="course-program__item-num-sublist">
                                                            <?= Yii::t('app', '{count} подразделов', [
                                                                'count' => count($section->children),
                                                            ]) ?>
                                                        </span>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                            <span class="course-program__icon">
                                                <i class="course-program__icon-plus _icon-plus"></i>
                                                <i class="course-program__icon-minus _icon-minus"></i>
                                            </span>
                                        </button>
                                        
                                        <ul class="course-program__sublist">
                                            <?php foreach ($section->children as $subsection) { ?>
                                                <li class="course-program__subitem submenu">
                                                    <button class="course-program__subitem-btn _icon-arrow">
                                                        <span class="course-program__subitem-name">
                                                            <?= $subsection->name ?>
                                                        </span>
                                                    </button>
                                                    
                                                    <ul class="course-program__sublink-list">
                                                        <?php foreach ($subsection->children as $unit) { ?>
                                                            <li class="course-program__sublink-item">
                                                                <a class="course-program__sublink" href="#">
                                                                    <img src="<?= $unit->type->icon ?>" alt="">
                                                                    <span class="course-program__sublink-name">
                                                                        <?= $unit->name ?>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                            
                            <div class="course-program__links">
                                <?php if ($model->demo_time && !$model->userCourse) { ?>
                                    <?= Html::a(
                                        Yii::t('app', 'Хочу учиться'),
                                        ['order/get-demo', 'id' => $model->id],
                                        [
                                            'class' => 'course-program__link primary-btn',
                                        ]
                                    ) ?>
                                <?php } ?>
                                
                                <button class="course-program__link course-program__link--open secondary-btn">
                                    <?= Yii::t('app', 'Открыть всю программу') ?>
                                </button>
                            </div>
                        </div>
                    </section>
                    
                    <section class="course-creator">
                        <div class="container">
                            <h2 class="course-creator__title">
                                <?= Yii::t('app', 'Создатели курса') ?>
                            </h2>
                            <div class="course-creator__wrapper swiper-container" data-mobile="false">
                                <div class="course-creator__items swiper-wrapper">
                                    <?php foreach ($model->authors as $author) { ?>
                                        <div class="course-creator__item swiper-slide">
                                            <div class="course-creator__item-img">
                                                <img src="<?= Yii::$app->services->image->thumb($author->image, [360, 360]) ?>" alt="">
                                            </div>
                                            <h5 class="course-creator__item-title">
                                                <?= $author->full_name ?>
                                            </h5>
                                            <div class="course-creator__item-info">
                                                <?= nl2br($author->experience) ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="course-creator__buttons">
                                    <button class="tertinary-btn course-creator__buttons-prev _icon-arrow swiper-button-prev"></button>
                                    <button class="tertinary-btn course-creator__buttons-next _icon-arrow swiper-button-next"></button>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <section class="course-banner" id="course-view-cart-add">
                        <div class="container">
                            <div class="course-banner__wrapper">
                                <div class="course-banner__content">
                                    <h2 class="course-banner__title">
                                        <?= Yii::t('app', 'course_detail_slogan_title') ?>
                                        <span class="subtitle-decor"></span>
                                    </h2>
                                    <p class="course-banner__text">
                                        <?= Yii::t('app', 'course_detail_slogan_description') ?>
                                    </p>
                                </div>
                                <div class="course-banner__card">
                                    <div class="course-banner__card-info">
                                        <div class="course-banner__card-date">
                                            <div class="course-banner__card-name">
                                                <?= Yii::t('app', 'Длительность:') ?>
                                            </div>
                                            <div class="course-banner__card-num">
                                                <?= $model->optimal_time ?>
                                            </div>
                                        </div>
                                        <div class="course-banner__card-date">
                                            <div class="course-banner__card-name">
                                                <?= Yii::t('app', 'Доступен до:') ?>
                                            </div>
                                            <div class="course-banner__card-num">
                                                <?= $model->date_to ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="course-banner__total">
                                        <div class="course-banner__total-inner">
                                            <div class="course-banner__total-name">
                                                <?= Yii::t('app', 'Стомиость курса:') ?>
                                            </div>
                                            <div class="course-banner__total-num">
                                                <?= Yii::t('app', '{price} UZS', [
                                                    'price' => Yii::$app->formatter->asDecimal($model->service->realPrice()),
                                                ]) ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($model->service->isOwned()) { ?>
                                            <div class="course-banner__link">
                                                <button type="button" class="secondary-btn btn-with-text">
                                                    <?= Yii::t('app', 'Куплено') ?>
                                                </button>
                                            </div>
                                        <?php } else { ?>
                                            <div class="course-banner__link">
                                                <?= Html::beginForm(['cart/change'], 'post', [
                                                    'data-toggle' => 'cart-change-form',
                                                ]); ?>
                                                
                                                <?= Html::hiddenInput('id', $model->id) ?>
                                                <?= Html::hiddenInput('type', 'Course') ?>
                                                
                                                <button type="submit" class="secondary-btn btn-with-text">
                                                    <?= Yii::t('app', $is_in_cart ? 'В корзине' : 'Купить') ?>
                                                </button>
                                                
                                                <?= Html::endForm(); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['course/courses']]) ?>
</main>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (location.hash === '#course-view-cart-add') {
            $.toast({
                heading: '<?= Yii::t('app', 'course_demo_time_passed_alert') ?>',
                position: 'bottom-right',
                loaderBg: '#80D1D5',
                icon: 'info',
                hideAfter: 4000,
                stack: 10
            });
        }
    });
</script>
