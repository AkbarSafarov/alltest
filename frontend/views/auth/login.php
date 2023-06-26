<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Войти');
$this->params['header_class'] = 'header';

?>

<main class="main">
    <section class="login">
        <div class="container">
            <div class="login__wrapper">
                <div class="login__img _ibg">
                    <img src="<?= $blocks->image_login ?>" alt="">
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

                <div id="firebaseui-auth-container"></div>
                
                <div class="login__inputs">
                    <div class="login__input">
                        <?= $form->field($model, 'username')->textInput()->hint(Yii::t('app', 'auth_login_hint')) ?>
                    </div>
                    <div class="login__input">
                        <?= $form->field($model, 'password', ['template' => Yii::$app->params['input_password_template']])->passwordInput() ?>
                       
                        <a class="input-item__link" href="<?= Yii::$app->urlManager->createUrl(['auth/reset-password-request']) ?>">
                            <?= Yii::t('app', 'Забыли пароль?') ?>
                        </a>
                    </div>
                </div>
                
                <div class="login__btn">
                    <button type="submit" class="primary-btn">
                        <span class="_icon-arrow">
                            <?= Yii::t('app', 'Войти') ?>
                        </span>
                    </button>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</main>
