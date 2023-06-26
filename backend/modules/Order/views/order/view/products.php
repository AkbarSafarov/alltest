<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\Order\models\OrderProduct;

$attribute_labels = (new OrderProduct())->attributeLabels();

?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><?= $attribute_labels['model_id'] ?></th>
            <th><?= $attribute_labels['total_price'] ?></th>
            <th><?= $attribute_labels['discount_price'] ?></th>
            <th><?= $attribute_labels['promocode_price'] ?></th>
            <th><?= $attribute_labels['checkout_price'] ?></th>
        </tr>
    </thead>
    
    <tbody>
        <?php foreach ($model->products as $product) { ?>
            <tr>
                <td>
                    <?= Html::tag('b', ArrayHelper::getValue($product, 'model_json.name')) ?><br>
                    <?= Html::tag('small', ArrayHelper::getValue($product->enums->modelClasses(), "$product->model_class.label")) ?>
                </td>
                
                <td>
                    <?= Yii::$app->formatter->asDecimal($product->total_price) ?>
                </td>
                
                <td>
                    <?= Yii::$app->formatter->asDecimal($product->discount_price) ?>
                </td>
                
                <td>
                    <?= Yii::$app->formatter->asDecimal($product->promocode_price) ?>
                </td>
                
                <td>
                    <?= Html::tag('b', Yii::$app->formatter->asDecimal($product->checkout_price)) ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
