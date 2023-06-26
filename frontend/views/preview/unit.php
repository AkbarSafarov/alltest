<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\widgets\LibraryAttachmentTemplateWidget;
use frontend\widgets\LibraryAttachmentTestWidget;
use frontend\widgets\LibraryAttachmentTestPackWidget;

$this->title = Yii::t('app', 'Предпросмотр: {value}', ['value' => $model->name]);

$test_quantity = count($tests);
$test_packs_quantity = ArrayHelper::getColumn($test_packs, 'quantity');
$test_packs_quantity = array_sum($test_packs_quantity);
$test_quantity += $test_packs_quantity;

$tests = ArrayHelper::getColumn($tests, function($value) {
    $result = $value['attributes'];
    $result['options'] = json_encode($result['options'], JSON_UNESCAPED_UNICODE);
    return $result;
});

?>

<main class="main has-bg" oncopy="return false">
    <div class="unit">
        <div class="container">
            <div class="unit__inner">
                <div class="unit__body">
                    <div class="bookmark-banner">
                        <div class="bookmark-banner__content">
                            <h3 class="bookmark-banner__title">
                                <?= $model->name ?>
                            </h3>
                        </div>
                    </div>
                    
                    <article class="unit__content container-content">
                        <?php if ($test_quantity) { ?>
                            <div class="test-info">
                                <div class="test-info__item">
                                    <div class="test-info__name">
                                        <?= Yii::t('app', 'Тест состоит из') ?>
                                        <span><?= Yii::t('app', '{quantity} вопросов', ['quantity' => $test_quantity]) ?></span>
                                    </div>
                                </div>
                                
                                <div class="test-info__item">
                                    <div class="test-info__name">
                                        <?= Yii::t('app', 'Рекомендуемое время заполнения:') ?>
                                        <span><?= Yii::t('app', '{quantity} минут', ['quantity' => $model->default_time_for_test]) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?= $model->description ?>
                        
                        <?= LibraryAttachmentTemplateWidget::widget([
                            'templates' => Yii::$app->services->array->toObjects($templates),
                        ]) ?>
                        
                        <?= LibraryAttachmentTestWidget::widget([
                            'model' => $model,
                            'tests' => Yii::$app->services->array->toObjects($tests),
                        ]) ?>
                        
                        <?= LibraryAttachmentTestPackWidget::widget([
                            'model' => $model,
                            'test_packs' => $test_packs,
                        ]) ?>
                    </article>
                </div>
            </div>
        </div>
    </div>
</main>
