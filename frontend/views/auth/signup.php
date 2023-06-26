<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Регистрация');
$this->params['header_class'] = 'header';

?>

<main class="main">
    <section class="registration">
        <div class="container">
            <div class="registration__wrapper">
                <div class="registration__img _ibg">
                    <img src="<?= $blocks->image_signup ?>" alt="">
                </div>
                
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'registration__form has-phone-mask-form'],
                ]); ?>
                
                <h2 class="registration__title">
                    <?= $this->title ?>
                </h2>
                
                <div class="registration__singin">
                    <?= Yii::t('app', 'Уже есть учётная запись на платформе «EXM Edx Platform»?') ?>
                    <a class="registration__signin-link" href="<?= Yii::$app->urlManager->createUrl(['auth/login']) ?>">
                        <?= Yii::t('app', 'Войти') ?>
                    </a>
                </div>
                
                <div id="firebaseui-auth-container"></div>
                
                <div class="registration__inputs">
                    <?= $form->field($model, 'username')->textInput() ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'nickname')->textInput() ?>
                    <?= $form->field($model, 'full_name')->textInput() ?>
                    <?= $form->field($model, 'gender')->dropDownList(ArrayHelper::getColumn($model->genders(), 'label')) ?>
                    <?= $form->field($model, 'birth_date')->textInput(['class' => 'date_mask', 'minlength' => '10']) ?>
                    <?= $form->field($model, 'phone')->textInput(['class' => 'phone_mask']) ?>
                    <?= $form->field($model, 'parent_phone')->textInput(['class' => 'phone_mask']) ?>
                    <?= $form->field($model, 'address')->textInput() ?>
                </div>
                
                <p class="registration__text">
                    <?= Yii::t('app', 'registration_submit_information') ?>
                </p>
                
                <div class="registration__btn">
                    <button type="submit" class="primary-btn">
                        <?= Yii::t('app', 'Создать учётную запись') ?>
                    </button>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</main>
