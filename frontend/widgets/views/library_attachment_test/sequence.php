<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$options = is_object($test->options) ? $test->options : json_decode($test->options);
$answers = $options->answers;
shuffle($answers);

foreach ($options->answers as $key => $answer) {
    $sequence[] = array_search($answer, $answers);
}

Yii::$app->session->set("unit_{$model->id}_{$group}_{$test->id}_answer", $sequence ?? []);

?>

<ul class="sortable">
    <?php foreach ($answers as $key => $answer) { ?>
        <li>
            <div class="sortable__item _icon-sequence">
                <div class="sortable__num"></div>
                <div class="sortable__option">
                    <img src="<?= $answer ?>" alt="">
                </div>
                
                <?= Html::hiddenInput("answer[$group][$test->id][]", $key) ?>
            </div>
        </li>
    <?php } ?>
</ul>
