<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$options = is_object($test->options) ? $test->options : json_decode($test->options);
$answers = $options->answers;
shuffle($answers);

foreach ($options->answers as $key => $answer) {
    $sequence[$key] = array_search($answer, $answers) + 1;
}

Yii::$app->session->set("unit_{$model->id}_{$group}_{$test->id}_answer", $sequence ?? []);

?>

<div class="matching">
    <div class="matching__inner">
        <div class="matching__item">
            <h6 class="matching__item-title">
                <?= Yii::t('app', 'unit_test_match_label_key') ?>
            </h6>
            <ul class="matching__item-list">
                <?php foreach ($options->answers as $key => $answer) { ?>
                    <li>
                        <span><?= range('A', 'Z')[$key] ?></span> - <?= $answer->key ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="matching__item">
            <h6 class="matching__item-title">
                <?= Yii::t('app', 'unit_test_match_label_value') ?>
            </h6>
            <ul class="matching__item-list">
                <?php foreach ($answers as $key => $answer) { ?>
                    <li>
                        <span><?= $key + 1 ?></span> - <?= $answer->value ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    
    <div class="matching__inputs">
        <?php foreach ($options->answers as $key => $answer) { ?>
            <div class="matching__inputs-item">
                <h6 class="matching__inputs-label">
                    <?= range('A', 'Z')[$key] ?>
                </h6>
                <div class="input">
                    <?= Html::textInput("answer[$group][$test->id][$key]", null, [
                        'class' => 'input-num',
                    ]) ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
