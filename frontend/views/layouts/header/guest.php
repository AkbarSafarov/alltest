<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<div class="container">
    <div class="header__wrapper">
        <div class="header__left">
            <div class="header__left-inner">
                <a class="header__logo logo" href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>">
                    <img src="<?= Yii::$app->services->settings->site_logo_1 ?>" alt="">
                </a>
                <?= $this->render('_languages') ?>
            </div>
            <button class="header-mobile-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        
        <?= $this->render('_search') ?>
        
        <div class="header__right header-mobile-content">
            <?php if (Yii::$app->controller->id != 'auth' || Yii::$app->controller->action->id != 'signup') { ?>
                <a class="header__link-signup" href="<?= Yii::$app->urlManager->createUrl(['auth/signup']) ?>">
                    <?= Yii::t('app', 'Регистрация') ?>
                </a>
            <?php } ?>
            
            <?php if (Yii::$app->controller->id != 'auth' || Yii::$app->controller->action->id != 'login') { ?>
                <a class="header__link-signin _icon-sign-in" href="<?= Yii::$app->urlManager->createUrl(['auth/login']) ?>">
                    <?= Yii::t('app', 'Войти') ?>
                </a>
            <?php } ?>
            
            <?= $this->render('_nav', ['menu' => $menu]) ?>
        </div>
    </div>
</div>