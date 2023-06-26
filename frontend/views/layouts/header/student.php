<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\UserNotification;

$profiles = Yii::$app->request->cookies->getValue('user_login_profiles', []);

$notifications = UserNotification::find()->andWhere(['user_id' => $user->id])->orderBy('id DESC')->limit(10)->all();
$notifications_not_seen_count = array_sum(ArrayHelper::getColumn($notifications, 'is_not_seen'));
$cart_quantity = Yii::$app->services->cart->total_quantity;

?>

<div class="container">
    <div class="header-login__wrapper">
        <div class="header-login__left">
            <a class="header-login__logo logo" href="<?= Yii::$app->urlManager->createUrl(['site/home']) ?>">
                <img src="<?= Yii::$app->services->settings->site_logo_1 ?>" alt="">
            </a>
            
            <?= $this->render('_languages') ?>
        </div>
        
        <?= $this->render('_search') ?>
        
        <div class="header-login__right">
            <div class="header-login__notification">
                <div class="header-notification dropdown--wrapper">
                    <button class="header-notification__btn _icon-bell dropdown--btn"
                            data-sr-trigger="ajax-button"
                            data-sr-url="<?= Yii::$app->urlManager->createUrl(['user/profile/clear-notifications']) ?>"
                    >
                        <span class="header-notification__num <?= $notifications_not_seen_count ? '_acitve' : null ?>">
                            <?= $notifications_not_seen_count ?>
                        </span>
                    </button>
                    
                    <div class="header-notification__content dropdown--content">
                        <div class="header-notification__top">
                            <button class="header-notification__title _icon-arrow">
                                <?= Yii::t('app', 'Уведомления') ?>
                            </button>
                            <button class="header-notification__btn-cross _icon-cross"></button>
                        </div>
                        
                        <ul class="header-notification__list">
                            <?php if ($notifications) { ?>
                                <?php foreach ($notifications as $key => $n) { ?>
                                    <li>
                                        <a class="<?= $n->is_not_seen ? '_active' : null ?>">
                                            <div class="header-notification__time">
                                                <?= Yii::t('app', '{date} в {time}', [
                                                    'date' => date('d/m/Y', strtotime($n->created_at)),
                                                    'time' => date('H:i', strtotime($n->created_at)),
                                                ]) ?>
                                            </div>
                                            <div class="header-notification__info">
                                                <?= ArrayHelper::getValue($n->service->actionData(), 'label') ?>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <?= Html::tag('div', Yii::t('app', 'Нет уведомлений'), ['class' => 'px-4']); ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <a class="header-login__cart _icon-cart" href="<?= Yii::$app->urlManager->createUrl(['cart/index']) ?>">
                <span class="header-login__cart-num <?= $cart_quantity ? '_active' : null ?>" id="cart-quantity">
                    <?= $cart_quantity ?>
                </span>
            </a>
            
            <div class="header-login__dropdown-user">
                <div class="dropdown-user dropdown--wrapper">
                    <button class="dropdown-user__btn _icon-arrow dropdown--btn dropdown--btn-arrow">
                        <span class="dropdown-user__icon">
                            <?php
                                if ($user->image) {
                                    echo Html::img(Yii::$app->services->image->thumb($user->image, [38, 38], 'crop', 'profile'));
                                } else {
                                    echo Yii::$app->services->string->firstLetters($user->full_name);
                                }
                            ?>
                        </span>
                        <span class="dropdown-user__name">
                            <?= $user->nickname ?>
                        </span>
                    </button>
                    
                    <div class="dropdown-user__content dropdown--content">
                        <ul class="dropdown-user__list">
                            <?php foreach ($profiles as $key => $profile) { ?>
                                <li>
                                    <a class="dropdown-user__item <?= $key == $user->id ? '_active' : null ?>"
                                       href="<?= Yii::$app->urlManager->createUrl(['auth/login-as', 'id' => $key]) ?>"
                                    >
                                        <div class="dropdown-user__inner">
                                            <span class="dropdown-user__icon">
                                                <?php
                                                    if ($profile['image']) {
                                                        echo Html::img(Yii::$app->services->image->thumb($profile['image'], [38, 38]));
                                                    } else {
                                                        echo Yii::$app->services->string->firstLetters($profile['full_name']);
                                                    }
                                                ?>
                                            </span>
                                            <span class="dropdown-user__name">
                                                <?= $profile['nickname'] ?>
                                            </span>
                                        </div>
                                    </a>
                                    
                                    <?= Html::a(
                                        null,
                                        ['auth/logout-as', 'id' => $key],
                                        ['class' => 'dropdown-user__btn-cross _icon-cross', 'data-method' => 'post']
                                    ) ?>
                                </li>
                            <?php } ?>
                        </ul>
                        
                        <a class="dropdown-user__add" href="<?= Yii::$app->urlManager->createUrl(['auth/login']) ?>">
                            <span class="dropdown-user__icon _icon-plus"></span>
                            <span class="dropdown-user__name">
                                <?= Yii::t('app', 'Добавить пользователя') ?>
                            </span>
                        </a>
                        
                        <div class="dropdown-user__bottom">
                            <?= Html::a(
                                Yii::t('app', 'Выйти из профиля'),
                                ['auth/logout-as', 'id' => $user->id],
                                ['class' => 'dropdown-user__log-out _icon-log-out', 'data-method' => 'post']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="header-mobile-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <div class="header-mobile-menu header-mobile-content">
            <?= $this->render('_languages') ?>
            <?= $this->render('_search') ?>
            
            <div class="header-login__dropdown-user">
                <div class="dropdown-user dropdown--wrapper">
                    <button class="dropdown-user__btn _icon-arrow dropdown--btn dropdown--btn-arrow">
                        <span class="dropdown-user__icon">
                            <?php
                                if ($user->image) {
                                    echo Html::img(Yii::$app->services->image->thumb($user->image, [38, 38], 'crop', 'profile'));
                                } else {
                                    echo Yii::$app->services->string->firstLetters($user->full_name);
                                }
                            ?>
                        </span>
                        <span class="dropdown-user__name">
                            <?= $user->nickname ?>
                        </span>
                    </button>
                    
                    <div class="dropdown-user__content dropdown--content">
                        <ul class="dropdown-user__list">
                            <?php foreach ($profiles as $key => $profile) { ?>
                                <li>
                                    <a class="dropdown-user__item <?= $key == $user->id ? '_active' : null ?>"
                                       href="<?= Yii::$app->urlManager->createUrl(['auth/login-as', 'id' => $key]) ?>"
                                    >
                                        <div class="dropdown-user__inner">
                                            <span class="dropdown-user__icon">
                                                <?php
                                                    if ($profile['image']) {
                                                        echo Html::img(Yii::$app->services->image->thumb($profile['image'], [38, 38]));
                                                    } else {
                                                        echo Yii::$app->services->string->firstLetters($profile['full_name']);
                                                    }
                                                ?>
                                            </span>
                                            <span class="dropdown-user__name">
                                                <?= $profile['nickname'] ?>
                                            </span>
                                        </div>
                                    </a>
                                    
                                    <?= Html::a(
                                        null,
                                        ['auth/logout-as', 'id' => $key],
                                        ['class' => 'dropdown-user__btn-cross _icon-cross', 'data-method' => 'post']
                                    ) ?>
                                </li>
                            <?php } ?>
                        </ul>
                        
                        <a class="dropdown-user__add" href="<?= Yii::$app->urlManager->createUrl(['auth/login']) ?>">
                            <span class="dropdown-user__icon _icon-plus"></span>
                            <span class="dropdown-user__name">
                                <?= Yii::t('app', 'Добавить пользователя') ?>
                            </span>
                        </a>
                        
                        <div class="dropdown-user__bottom">
                            <?= Html::a(
                                Yii::t('app', 'Выйти из профиля'),
                                ['auth/logout-as', 'id' => $user->id],
                                ['class' => 'dropdown-user__log-out _icon-log-out', 'data-method' => 'post']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?= $this->render('_nav', ['menu' => $menu]) ?>
        </div>
    </div>
</div>
