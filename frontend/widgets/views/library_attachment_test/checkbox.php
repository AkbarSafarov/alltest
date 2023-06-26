<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$options = is_object($test->options) ? $test->options : json_decode($test->options);
Yii::$app->session->set("unit_{$model->id}_{$group}_{$test->id}_answer", $options->answers);

?>

<div class="test-item__warning _icon-exclamation">
    <?= Yii::t('app', 'Здесь несколько вариантов ответа') ?>
</div>

<ul class="test-item__options">
    <?php foreach ($options->options as $key => $option) { ?>
        <li class="test-item__option">
            <label class="test-item__label">
                <?= Html::checkbox("answer[$group][$test->id][]", null, [
                    'class' => 'checkbox',
                    'value' => $key,
                ]) ?>
                
                <span class="checkbox__style"></span>
                <span class="test-item__option-text">
                    <?= $option ?>
                </span>
            </label>
        </li>
    <?php } ?>
</ul>
