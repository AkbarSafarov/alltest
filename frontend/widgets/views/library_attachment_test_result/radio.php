<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$options = is_object($test->options) ? $test->options : json_decode($test->options);

?>

<ul class="test-item__options">
    <?php foreach ($options->options as $key => $option) { ?>
        <li class="test-item__option">
            <label class="test-item__label">
                <?= Html::radio(null, in_array($key, $answer), [
                    'class' => 'radio',
                ]) ?>
                
                <span class="radio__style"></span>
                <span class="test-item__option-text">
                    <?= $option ?>
                </span>
            </label>
        </li>
    <?php } ?>
</ul>
