<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$sequence = Yii::$app->session->get("unit_{$model->id}_{$group}_{$test->id}_answer", []);
$sequence = ArrayHelper::filter($sequence, $answer);

$options = is_object($test->options) ? $test->options : json_decode($test->options);
$options = ArrayHelper::filter($options->answers, $sequence);

?>

<ul>
    <?php foreach ($options as $key => $option) { ?>
        <li>
            <div class="sortable__item _icon-sequence">
                <div class="sortable__num"></div>
                <div class="sortable__option">
                    <img src="<?= $option ?>" alt="">
                </div>
            </div>
        </li>
    <?php } ?>
</ul>
