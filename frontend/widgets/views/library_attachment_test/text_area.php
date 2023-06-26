<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$options = is_object($test->options) ? $test->options : json_decode($test->options);
Yii::$app->session->set("unit_{$model->id}_{$group}_{$test->id}_answer", $options->answers);

?>

<div class="input test-item__input">
    <?= Html::textInput("answer[$group][$test->id]") ?>
</div>
