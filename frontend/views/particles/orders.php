<?php

use yii\helpers\ArrayHelper;

?>

<?php foreach ($models as $model) { ?>
    <li class="account__table-item">
        <div class="account__table-inner">
            <h6 class="account__table-title">
                <?= implode(', ', ArrayHelper::getColumn($model->products, 'model_json.name')) ?>
            </h6>
            <div class="account__table-num">
                #<?= $model->id ?>
            </div>
        </div>
        <h6 class="account__table-price">
            <?= Yii::t('app', '{price} UZS', [
                'price' => Yii::$app->formatter->asDecimal($model->checkout_price),
            ]) ?>
        </h6>
        <h6 class="account__table-price">
            <?= date('d.m.Y', strtotime($model->created_at)) ?>
        </h6>
    </li>
<?php } ?>