<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<div class="test__content container-content">
    <ul class="test__items">
        <?php foreach ($tests as $key => $test) { ?>
            <li class="test__item">
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
                    
                    <?= $this->render("library_attachment_test/$test->input_type", [
                        'group' => $group,
                        'model' => $model,
                        'test' => $test,
                    ]); ?>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
