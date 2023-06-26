<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<div class="test__content container-content">
    <ul class="test__items">
        <?php foreach ($test_groups as $group => $tests) { ?>
            <?php foreach ($tests as $key => $test) { ?>
                <?php
                    $answer = ArrayHelper::getValue(Yii::$app->session->get("unit_{$model->id}_test_post_data", []), "$group.$test->id");
                    $correct_answers = ArrayHelper::getValue(Yii::$app->session->get("unit_{$model->id}_test_post_data_correct", []), $group, []);
                    $is_correct = in_array($test->id, $correct_answers);
                ?>
                
                <li class="test__item wrapper-<?= $is_correct ? 'success' : 'danger' ?>">
                    <div class="test-item">
                        <div class="test-item__num">
                            <?= Yii::t('app', 'Вопрос') ?>
                        </div>
                        <h5 class="test-item__question">
                            <?= $test->question ?>
                        </h5>
                        <p class="test-item__subtext">
                            <?= Yii::t('app', "unit_test_{$test->input_type}_hint") ?>
                        </p>
                        
                        <?= $this->render("library_attachment_test_result/$test->input_type", [
                            'model' => $model,
                            'group' => $group,
                            'test' => $test,
                            'answer' => $answer ?: [],
                        ]); ?>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
