<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\widgets\LibraryAttachmentTestResultWidget;

$prev_unit = $model->service->prev();
$next_unit = $model->service->next();

?>

<div class="unit__body">
    <div class="bookmark-banner">
        <div class="bookmark-banner__content">
            <h3 class="bookmark-banner__title">
                <?= $model->unit_json['name'] ?>
            </h3>
        </div>
    </div>
    
    <article class="unit__content container-content">
        <div class="test-info">
            <div class="test-info__item">
                <div class="test-info__name">
                    <?= Yii::t('app', 'Тест состоял из') ?>
                    <span><?= Yii::t('app', '{quantity} вопросов', ['quantity' => $test_quantity['total']]) ?></span>
                </div>
            </div>
            
            <div class="test-info__item">
                <div class="test-info__name">
                    <?= Yii::t('app', 'Рекомендуемое время заполнения:') ?>
                    <span><?= Yii::t('app', '{quantity} минут', ['quantity' => $model->unit_json['default_time_for_test']]) ?></span>
                </div>
            </div>
        </div>
        
        <?= $this->render('_result', [
            'model' => $model,
            'course' => $course,
            'test_quantity' => $test_quantity,
            'prev_unit' => $prev_unit,
            'next_unit' => $next_unit,
            'type' => 'exam',
        ]); ?>
        
        <?= LibraryAttachmentTestResultWidget::widget([
            'model' => $model,
        ]) ?>
        
        <?= $this->render('_result', [
            'model' => $model,
            'course' => $course,
            'test_quantity' => $test_quantity,
            'prev_unit' => $prev_unit,
            'next_unit' => $next_unit,
            'type' => 'exam',
        ]); ?>
    </article>
</div>
