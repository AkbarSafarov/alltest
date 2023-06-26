<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Восстановление профиля');
$this->params['header_class'] = 'header';

?>

<main class="main">
    <section class="login">
        <div class="container">
            <div class="login__wrapper">
                <div class="login__img _ibg login__img--request">
                    <img src="<?= $blocks->image_reset_password_request ?>" alt="">
                </div>
                
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'login__form'],
                ]); ?>
                
                <h2 class="login__title">
                    <?= $this->title ?>
                </h2>
                
                <div class="login__register">
                    <div class="login__register-name">
                        <?= Yii::t('app', 'Новый пользователь?') ?>
                    </div>
                    <a class="login__register-link" href="<?= Yii::$app->urlManager->createUrl(['auth/signup']) ?>">
                        <?= Yii::t('app', 'Создать учетную запись') ?>
                    </a>
                </div>
                
                <div class="login__request">
                    <?= $form->field($model, 'username')->textInput() ?>
                </div>
                
                <div class="login__btn login__btn--request">
                    <button type="submit" class="primary-btn">
                        <span class="_icon-arrow">
                            <?= Yii::t('app', 'Восстановить') ?>
                        </span>
                    </button>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</main>
