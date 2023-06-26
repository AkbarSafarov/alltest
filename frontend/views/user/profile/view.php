<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Профиль "{nickname}"', ['nickname' => $model->nickname]);

$profile_information = $model->service->profileInformation();
$attributes = ['nickname', 'full_name', 'birth_date', 'address', 'gender', 'phone', 'parent_phone'];

?>

<main class="main has-bg">
    <div class="account">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop') ?>
            
            <div class="container">
                <h3 class="account__title">
                    <?= $this->title ?>
                </h3>
                
                <div class="account__content container-content">
                    <div class="account__content-row">
                        <div class="account__content-left">
                            <div class="account__item">
                                <h5 class="account__subtitle">
                                    <?= Yii::t('app', 'Фотография') ?>
                                </h5>
                                <div class="account__img">
                                    <div class="account__img-preview">
                                        <img src="<?= Yii::$app->services->image->thumb($model->image, [270, 270], 'crop', 'profile') ?>" alt="">
                                    </div>
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
                                        <?php foreach ($attributes as $attribute) { ?>
                                            <li class="account__info-item">
                                                <label class="account__info-label">
                                                    <?= $model->getAttributeLabel($attribute) ?>
                                                </label>
                                                <div class="account__info-content">
                                                    <?php
                                                        switch ($attribute) {
                                                            case 'gender':
                                                                echo ArrayHelper::getValue($model->enums->genders(), "{$model->{$attribute}}.label");
                                                                break;
                                                            default:
                                                                echo $model->{$attribute};
                                                        }
                                                    ?>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    
                                    <?php if ($model->id == Yii::$app->user->id) { ?>
                                        <div>
                                            <label class="account__info-label">
                                                <?= Yii::t('app', 'Ссылка на профиль') ?>
                                            </label>
                                            <div class="account__info-content">
                                                <?= Html::a(
                                                    Yii::$app->urlManager->createAbsoluteUrl(['user/profile/view', 'nickname' => $model->nickname]),
                                                    Yii::$app->urlManager->createAbsoluteUrl(['user/profile/view', 'nickname' => $model->nickname])
                                                ) ?>
                                            </div>
                                        </div>
                                        <br>
                                        
                                        <a class="account__info-btn primary-btn" href="<?= Yii::$app->urlManager->createUrl(['user/profile/update']) ?>">
                                            <?= Yii::t('app', 'Изменить') ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="account__content-row">
                        <div class="account__content-left">
                            <?= $this->render('_rewards', ['rewards' => $profile_information['rewards']]) ?>
                        </div>
                        
                        <div class="account__content-right">
                            <?= $this->render('_certificates', ['certificates' => $profile_information['certificates']]) ?>
                            <?= $model->id == Yii::$app->user->id ? $this->render('_orders', ['orders' => $profile_information['last_orders']]) : null ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile') ?>
</main>
