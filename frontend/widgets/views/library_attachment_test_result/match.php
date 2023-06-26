<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$sequence = Yii::$app->session->get("unit_{$model->id}_{$group}_{$test->id}_answer", []);
$sequence = array_map(fn($value) => $value - 1, $sequence);

$options = is_object($test->options) ? $test->options : json_decode($test->options);
$answers = array_values(ArrayHelper::filter($options->answers, $sequence));

?>

<div class="matching">
    <div class="matching__inner">
        <div class="matching__item">
            <h6 class="matching__item-title">
                <?= Yii::t('app', 'unit_test_match_label_key') ?>
            </h6>
            <ul class="matching__item-list">
                <?php foreach ($options->answers as $key => $a) { ?>
                    <li>
                        <span><?= range('A', 'Z')[$key] ?></span> - <?= $a->key ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="matching__item">
            <h6 class="matching__item-title">
                <?= Yii::t('app', 'unit_test_match_label_value') ?>
            </h6>
            <ul class="matching__item-list">
                <?php foreach ($answers as $key => $a) { ?>
                    <li>
                        <span><?= $key + 1 ?></span> - <?= $a->value ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    
    <div class="matching__inputs">
        <?php foreach ($answer as $key => $a) { ?>
            <div class="matching__inputs-item">
                <h6 class="matching__inputs-label">
                    <?= range('A', 'Z')[$key] ?>
                </h6>
                <div class="input">
                    <?= Html::textInput(null, $a, [
                        'class' => 'input-num',
                    ]) ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
