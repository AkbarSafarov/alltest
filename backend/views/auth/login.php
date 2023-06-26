<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Авторизация');

?>

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card bg-pattern">
                    <div class="card-body p-4">
                        <div class="text-center w-75 mx-auto mb-3">
                            <div class="auth-logo">
                                <a href="#" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="<?= Yii::$app->services->settings->site_logo_1 ?>" alt="" height="22">
                                    </span>
                                </a>
                            </div>
                        </div>
                        
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'enableClientValidation' => false,
                            'fieldConfig' => [
                                'options' => ['class' => 'mb-3'],
                            ],
                        ]); ?>
                        
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
                        <?= $form->field($model, 'password', [
                            'template' => '{label}<div class="input-group input-group-merge">
                                {input}
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>{hint}{error}',
                        ])->passwordInput(); ?>
                        
                        <?= $form->field($model, 'remember_me')->checkbox([
                            'class' => 'form-check-input'
                        ])->label(null, [
                            'class' => 'form-check-label'
                        ]) ?>
                        
                        <div class="text-center d-grid">
                            <button class="btn btn-primary" type="submit">
                                <?= Yii::t('app', 'Войти') ?>
                            </button>
                        </div>
                        
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer footer-alt">
        <?= date('Y') . ' &copy; ' . Yii::$app->services->settings->site_name ?>
</footer>
