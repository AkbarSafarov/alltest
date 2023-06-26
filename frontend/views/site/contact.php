<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$page->registerSeoMeta();

$this->title = Yii::t('app', 'Связаться с нами');
$this->params['header_class'] = 'header';

?>

<main class="main">
    <section class="contact">
        <div class="container">
            <h2 class="contact__title">
                <?= $this->title ?>
                <span class="title-decor"></span>
            </h2>
            
            <div class="contact__wrapper">
                <div class="contact__content">
                    <div class="contact__item">
                        <div class="contact__item-title">
                            <?= Yii::t('app', 'Адрес') ?>
                        </div>
                        <address class="contact__item-info">
                            <?= $blocks->address ?>
                        </address>
                    </div>
                    <div class="contact__item">
                        <div class="contact__item-title">
                            <?= Yii::t('app', 'Телефон') ?>
                        </div>
                        <a class="contact__item-info link-hover" href="tel:<?= $blocks->phone ?>">
                            <?= $blocks->phone ?>
                        </a>
                    </div>
                    <div class="contact__item">
                        <div class="contact__item-title">
                            <?= Yii::t('app', 'Почта') ?>
                        </div>
                        <a class="contact__item-info link-hover" href="mailto:<?= $blocks->email ?>">
                            <?= $blocks->email ?>
                        </a>
                    </div>
                    <div class="contact__item">
                        <div class="contact__item-title">
                            <?= Yii::t('app', 'Социальные сети') ?>
                        </div>
                        <ul class="contact__item-social">
                            <?php
                                foreach (Yii::$app->params['socials'] as $social => $icon) {
                                    if ($link = $blocks->{"social_$social"}) {
                                        echo Html::tag('li', Html::a(null, $link, [
                                            'class' => "link-hover _icon-$icon",
                                            'target' => '_blank',
                                        ]));
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                
                <?php $form = ActiveForm::begin(['options' => ['class' => 'contact__form']]); ?>
                
                <div class="contact__form-inner">
                    <?= $form->field($model, 'full_name')->textInput() ?>
                    <?= $form->field($model, 'phone')->textInput() ?>
                </div>
                
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'message', [
                    'template' => '<div class="input-item__inner">{label}<div class="textarea">{input}</div></div>{hint}{error}',
                ])->textarea() ?>
                
                <button type="submit" class="contact__btn primary-btn">
                    <?= Yii::t('app', 'Отправить') ?>
                </button>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</main>
