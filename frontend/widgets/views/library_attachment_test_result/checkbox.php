<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$options = is_object($test->options) ? $test->options : json_decode($test->options);

?>

<ul class="test-item__options">
    <?php foreach ($options->options as $key => $option) { ?>
        <li class="test-item__option">
            <label class="test-item__label">
                <?= Html::checkbox(null, in_array($key, $answer), [
                    'class' => 'checkbox',
                ]) ?>
                
                <span class="checkbox__style"></span>
                <span class="test-item__option-text">
                    <?= $option ?>
                </span>
            </label>
        </li>
    <?php } ?>
</ul>
