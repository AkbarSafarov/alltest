<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<div class="input test-item__input">
    <?= Html::textInput(null, is_array($answer) ? null : $answer) ?>
</div>
