<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'error_page_title');

?>

<main class="main has-bg">
    <div class="container">
        <div class="site-error">
            <h3 class="site-error__title">
                <?= $this->title ?>
            </h3>
            
            <div class="site-error__img">
                <img src="/img/content/not-found/404.png" alt="">
            </div>

            <div class="site-error__text">
                <?= Yii::t('app', 'error_page_description') ?>
            </div>

            <div class="site-error__btns">
                <div class="site-error__btn">
                    <a class="primary-btn" href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>">
                        <?= Yii::t('app', 'Вернуться на главную') ?>
                    </a>
                </div>
                <div class="site-error__btn">
                    <a class="secondary-btn" href="<?= Yii::$app->urlManager->createUrl(['course/courses']) ?>">
                        <?= Yii::t('app', 'Посмотреть каталог') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
