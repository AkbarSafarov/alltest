<div class="account__item">
    <h5 class="account__subtitle">
        <?= Yii::t('app', 'История платежей') ?>
    </h5>
    
    <ul class="account__table">
        <?= $this->render('@frontend/views/particles/orders', ['models' => $orders]); ?>
    </ul>
    
    <?php if (count($orders) == 5) { ?>
        <a class="account__reset-btn primary-btn" href="<?= Yii::$app->urlManager->createUrl(['user/profile/orders']) ?>">
            <?= Yii::t('app', 'Все оплаты') ?>
        </a>
    <?php } ?>
</div>