<div class="row mb-4">
    <div class="col-md">
        <div class="alert alert-primary m-0">
            <strong><?= Yii::t('app', 'Предварительная сумма') ?>:</strong>
            <?= Yii::$app->formatter->asDecimal($query->sum('total_price')) ?>
        </div>
    </div>
    
    <div class="col-md">
        <div class="alert alert-success m-0">
            <strong><?= Yii::t('app', 'Итоговая сумма') ?>:</strong>
            <?= Yii::$app->formatter->asDecimal($query->sum('checkout_price')) ?>
        </div>
    </div>
    
    <div class="col-md">
        <div class="alert alert-warning m-0">
            <strong><?= Yii::t('app', 'Итоговое количество заказов') ?>:</strong>
            <?= Yii::$app->formatter->asDecimal($query->count()) ?>
        </div>
    </div>
</div>