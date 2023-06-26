<?php

use yii\helpers\Html;

?>

<div class="delete-account">
    <h3 class="delete-account__title">
        <?= Yii::t('app', 'Введите свой пароль для подтверждения') ?>
    </h3>
    
    <?= Html::beginForm() ?>
    
    <div class="input">
        <?= Html::passwordInput('password', null, ['class' => 'form-control', 'required' => true]) ?>
    </div>
    
    <button type="submit" class="delete-account__btn tertinary-btn">
        <?= Yii::t('app', 'Удалить') ?>
    </button>
    
    <?= Html::endForm() ?>
</div>
