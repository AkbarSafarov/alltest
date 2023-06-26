<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use speedrunner\services\DateService;

use backend\modules\Course\enums\CourseEnums;

$this->title = Yii::t('app', 'Корзина');

?>

<?= !Yii::$app->request->isAjax ? Html::beginTag('div', ['id' => 'cart-page']) : null ?>

<main class="main">
    <section class="basket">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop') ?>
            
            <div class="container">
                <?php if ($cart->products) { ?>
                    <div class="basket__top">
                        <h3 class="basket__title">
                            <?= $this->title ?>
                        </h3>
                        <a href="<?= Yii::$app->urlManager->createUrl(['cart/clear']) ?>" class="basket__top-btn _icon-cross">
                            <?= Yii::t('app', 'Очистить корзину') ?>
                        </a>
                    </div>
                    
                    <div class="basket__content container-content">
                        <ul class="basket__items">
                            <?php foreach ($cart->products as $key => $p) { ?>
                                <?php $image = $p->model_json['preview_image'] ?? $p->model_json['image'] ?>
                                
                                <li class="basket__item">
                                    <article class="basket-item">
                                        <a class="basket-item__img" href="#">
                                            <img src="<?= Yii::$app->services->image->thumb($image, [140, 116]) ?>" alt="">
                                        </a>
                                        
                                        <div class="basket-item__inner">
                                            <div class="basket-item__body">
                                                <div class="basket-item__content">
                                                    <h6 class="basket-item__category">
                                                        <?php
                                                            if ($p->model_class == 'Course') {
                                                                echo ArrayHelper::getValue(CourseEnums::types(), "{$p->model_json['type']}.label");
                                                            } else {
                                                                echo Yii::t('app', 'Пакет курсов');
                                                            }
                                                        ?>
                                                    </h6>
                                                    
                                                    <a class="basket-item__title" href="#">
                                                        <h5>
                                                            <?= $p->model_json['name'] ?>
                                                        </h5>
                                                    </a>
                                                    
                                                    <div class="basket-item__info">
                                                        <span class="basket-item__info-title">
                                                            <?= $p->model_json['optimal_time'] ?>
                                                        </span>
                                                        
                                                        <span class="basket-item__info-title">
                                                            <?= Yii::t('app', 'Доступ до {date}', [
                                                                'date' => $p->model_json['date_to'],
                                                            ]) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="basket-item__price">
                                                    <h5 class="basket-item__price-current">
                                                        <?= Yii::t('app', '{price} UZS', [
                                                            'price' => Yii::$app->formatter->asDecimal($p->checkout_price),
                                                        ]) ?>
                                                    </h5>
                                                    
                                                    <?php if ($p->checkout_price != $p->price) { ?>
                                                        <div class="basket-item__price-old">
                                                            <div class="course-card__price-old">
                                                                <?= Yii::t('app', '{price} UZS', [
                                                                    'price' => Yii::$app->formatter->asDecimal($p->price),
                                                                ]) ?>
                                                            </div>
                                                            
                                                            <div class="basket__total-item basket__total-item--sale cart-product-sale">
                                                                <?= $p->price ? (100 - round($p->checkout_price / $p->price * 100)) : null ?>%
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            
                                            <div class="basket-item__btn">
                                                <?= Html::beginForm(['cart/change'], 'post', [
                                                    'data-toggle' => 'cart-change-form',
                                                ]); ?>
                                                
                                                <?= Html::hiddenInput('type', $p->model_class) ?>
                                                <?= Html::hiddenInput('id', $p->model_id) ?>
                                                
                                                <button type="submit" class="_icon-cross-circle"></button>
                                                
                                                <?= Html::endForm(); ?>
                                            </div>
                                        </div>
                                    </article>
                                </li>
                            <?php } ?>
                        </ul>
                        
                        <div class="basket__total">
                            <div class="basket__promo">
                                <div class="basket__promo-content">
                                    <h6 class="basket__promo-title">
                                        <?= Yii::t('app', 'Промокод') ?>
                                    </h6>
                                    <p class="basket__promo-text">
                                        <?= Yii::t('app', 'cart_promocode_description') ?>
                                    </p>
                                </div>
                                
                                <?php if ($promocode = $cart->cart->promocode) { ?>
                                    <div class="basket__promo-content">
                                        <h6 class="basket__total-item basket__total-item--sale">
                                            <?= Yii::t('app', 'Применённый промокод: "{promocode}"', [
                                                'promocode' => $promocode->key,
                                            ]) ?>
                                            
                                            <button type="button"
                                                    class="_icon-cross btn-cart-promocode-delete"
                                                    data-sr-trigger="ajax-button"
                                                    data-sr-url="<?= Yii::$app->urlManager->createUrl(['cart/change-promocode']) ?>"
                                                    data-sr-wrapper="#cart-page"
                                            ></button>
                                        </h6>
                                    </div>
                                <?php } else { ?>
                                    <?= Html::beginForm(['cart/change-promocode'], 'post', [
                                        'data-sr-trigger' => 'ajax-form',
                                        'data-sr-wrapper' => '#cart-page',
                                    ]) ?>
                                    
                                    <div class="basket__promo-inner">
                                        <div class="basket__promo-input">
                                            <div class="input">
                                                <input type="text" name="promocode" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="basket__promo-btn tertinary-btn">
                                            <?= Yii::t('app', 'Применить') ?>
                                        </button>
                                    </div>
                                    
                                    <?= Html::endForm() ?>
                                <?php } ?>
                            </div>
                            
                            <div class="basket__total-content">
                                <div class="basket__total-items">
                                    <div class="basket__total-item">
                                        <div class="basket__total-label">
                                            <?= Yii::t('app', 'Сумма:') ?>
                                        </div>
                                        <h6 class="basket__total-num">
                                            <?= Yii::t('app', '{price} UZS', [
                                                'price' => Yii::$app->formatter->asDecimal($cart->total_price),
                                            ]) ?>
                                        </h6>
                                    </div>
                                    <div class="basket__total-item basket__total-item--sale">
                                        <div class="basket__total-label">
                                            <?= Yii::t('app', 'Скидка:') ?>
                                        </div>
                                        <h6 class="basket__total-num">
                                            <?= $cart->total_price ? (100 - round($cart->checkout_price / $cart->total_price * 100)) : 0 ?>%
                                        </h6>
                                    </div>
                                </div>
                                
                                <div class="basket__total-item basket__total-item--general">
                                    <h6 class="basket__total-label">
                                        <?= Yii::t('app', 'Итого:') ?>
                                    </h6>
                                    <h6 class="basket__total-num">
                                        <?= Yii::t('app', '{price} UZS', [
                                            'price' => Yii::$app->formatter->asDecimal($cart->checkout_price),
                                        ]) ?>
                                    </h6>
                                </div>
                                
                                <?php
                                    if ($cart->checkout_price) {
                                        echo Html::button(Yii::t('app', 'Оплатить с помощью {payment}', [
                                            'payment' => 'Payme',
                                        ]), [
                                            'class' => 'basket__total-btn basket__total-btn--payme secondary-btn',
                                            'data-sr-trigger' => 'ajax-button',
                                            'data-sr-url' => Yii::$app->urlManager->createUrl(['order/pay', 'type' => 'paycom']),
                                            'data-sr-wrapper' => '#main-modal-wrapper',
                                        ]);
                                        
                                        echo Html::button(Yii::t('app', 'Оплатить с помощью {payment}', [
                                            'payment' => 'Click',
                                        ]), [
                                            'class' => 'basket__total-btn basket__total-btn--click secondary-btn',
                                            'data-sr-trigger' => 'ajax-button',
                                            'data-sr-url' => Yii::$app->urlManager->createUrl(['order/pay', 'type' => 'click']),
                                            'data-sr-wrapper' => '#main-modal-wrapper',
                                        ]);
                                    } else {
                                        echo Html::a(Yii::t('app', 'Приобрести'), ['order/pay', 'type' => 'paycom'], [
                                            'class' => 'basket__total-btn secondary-btn',
                                        ]);
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="basket__top basket__top--empty">
                        <h3 class="basket__title">
                            <?= Yii::t('app', 'Ваша корзина пуста') ?>
                        </h3>
                        <div class="basket__img">
                            <img src="/img/content/not-found/cart.png" alt="">
                        </div>
                        <p class="basket__text">
                            <?= Yii::t('app', 'Вы можете начать свой выбор с каталога, посмотреть курсы или воспользоваться поиском, если ищете что-то конкретное') ?>
                        </p>
                        <a class="basket__link-back primary-btn" href="<?= Yii::$app->urlManager->createUrl(['course/courses']) ?>">
                            <?= Yii::t('app', 'Посмотреть каталог') ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile') ?>
</main>

<?= !Yii::$app->request->isAjax ? Html::endTag('div') : null ?>
