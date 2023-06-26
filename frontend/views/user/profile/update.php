<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Редактирование профиля');

$profile_information = $model->user->service->profileInformation();

?>

<main class="main has-bg">
    <div class="account">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['user/profile/view']]) ?>
            
            <div class="container">
                <h3 class="account__title">
                    <?= $this->title ?>
                </h3>
                
                <div class="account__content container-content">
                    <?php $form = ActiveForm::begin([
                        'options' => ['class' => 'has-phone-mask-form'],
                        'fieldConfig' => [
                            'template' => '{label}<div class="input">{input}</div>{hint}{error}',
                            'options' => [
                                'tag' => 'li',
                                'class' => 'account__info-item',
                            ],
                            'labelOptions' => [
                                'class' => 'account__info-label',
                            ],
                        ],
                    ]); ?>

                    <div class="account__content-row">
                        <div class="account__content-left">
                            <h5 class="account__subtitle">
                                <?= Yii::t('app', 'Фотография') ?>
                            </h5>
                            
                            <div class="account__img">
                                <div class="account__img-preview">
                                    <img src="<?= Yii::$app->services->image->thumb($model->user->image, [270, 270], 'crop', 'profile') ?>" alt="">
                                </div>
                                
                                <div class="account__img-input">
                                    <?= $form->field($model, 'image', [
                                        'template' => '{input}{error}',
                                    ])->fileInput([
                                        'accept' => '.jpg, .png, .gif',
                                        'class' => 'account__input-file',
                                    ]) ?>
                                    
                                    <button type="button" class="account__input-btn _icon-edit"></button>
                                </div>
                            </div>
                        </div>

                        <div class="account__content-right">
                            <div class="account__item">
                                <h5 class="account__subtitle">
                                    <?= Yii::t('app', 'Общая информация') ?>
                                </h5>
                                
                                <div class="account__info">
                                    <ul class="account__info-items">
                                        <?= $form->field($model, 'nickname')->textInput() ?>
                                        <?= $form->field($model, 'full_name')->textInput() ?>
                                        <?= $form->field($model, 'birth_date')->textInput(['class' => 'date_mask', 'minlength' => '10']) ?>
                                        <?= $form->field($model, 'address')->textInput() ?>
                                        <?= $form->field($model, 'gender')->dropDownList(ArrayHelper::getColumn($model->user->enums->genders(), 'label')) ?>
                                        <?= $form->field($model, 'phone')->textInput(['class' => 'phone_mask']) ?>
                                        <?= $form->field($model, 'parent_phone')->textInput(['class' => 'phone_mask']) ?>
                                    </ul>
                                    
                                    <button type="submit" class="account__info-btn primary-btn">
                                        <?= Yii::t('app', 'Применить') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php ActiveForm::end() ?>

                    <div class="account__content-row account__content-row--edit">
                        <div class="account__content-left">
                            <?= $this->render('_rewards', ['rewards' => $profile_information['rewards']]) ?>
                        </div>
                        
                        <div class="account__content-right">
                            <div class="account__item">
                                <h5 class="account__subtitle">
                                    <?= Yii::t('app', 'Пароль') ?>
                                </h5>
                                
                                <button type="button" class="account__reset-btn primary-btn account__reset-btn--update">
                                    <?= Yii::t('app', 'Сбросить пароль') ?>
                                </button>
                                
                                <div class="account__reset-form">
                                    <?php $form = ActiveForm::begin([
                                        'fieldConfig' => [
                                            'template' => '{label}<div class="input">{input}</div>{hint}{error}',
                                            'options' => [
                                                'tag' => 'li',
                                                'class' => 'account__info-item',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'account__info-label',
                                            ],
                                        ],
                                    ]); ?>
                                    
                                    <ul class="account__info-inner">
                                        <?= $form->field($model, 'old_password')->passwordInput() ?>
                                        <?= $form->field($model, 'new_password')->passwordInput() ?>
                                        <?= $form->field($model, 'confirm_password')->passwordInput() ?>
                                    </ul>
                                    
                                    <button type="submit" class="primary-btn">
                                        <?= Yii::t('app', 'Сохранить') ?>
                                    </button>
                                    
                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                            
                            <?= $this->render('_certificates', ['certificates' => $profile_information['certificates']]) ?>
                            <?= $this->render('_orders', ['orders' => $profile_information['last_orders']]) ?>
                            
                            <div class="account__item">
                                <h5 class="account__subtitle">
                                    <?= Yii::t('app', 'Удалить аккаунт') ?>
                                </h5>
                                
                                <div class="account__delete">
                                    <p class="account__delete-text">
                                        <?= Yii::t('app', 'account_delete_primary_text') ?>
                                    </p>
                                    <p class="account__delete-warning">
                                        <?= Yii::t('app', 'account_delete_warning_text') ?>
                                    </p>
                                    
                                    <?= Html::a(Yii::t('app', 'Удалить аккаунт'), '#', [
                                        'class' => 'account__delete-btn tertinary-btn',
                                        'data-sr-trigger' => 'ajax-button',
                                        'data-sr-method' => 'post',
                                        'data-sr-url' => Yii::$app->urlManager->createUrl(['user/profile/delete']),
                                        'data-sr-wrapper' => '#main-modal-wrapper',
                                        'data-sr-callback' => "$('#main-modal').addClass('_active');",
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['user/profile/view']]) ?>
</main>
