<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\widgets\LibraryAttachmentTemplateWidget;
use frontend\widgets\LibraryAttachmentTestWidget;
use frontend\widgets\LibraryAttachmentTestPackWidget;

$test_quantity = count($model->library_attachment_json['tests']);
$test_packs_quantity = ArrayHelper::getColumn($model->library_attachment_json['test_packs'], 'quantity');
$test_packs_quantity = array_sum($test_packs_quantity);
$test_quantity += $test_packs_quantity;

?>

<div class="unit__body">
    <div class="bookmark-banner">
        <div class="bookmark-banner__content">
            <h3 class="bookmark-banner__title">
                <?= $model->unit_json['name'] ?>
            </h3>
            
            <?= Html::a(
                Yii::t('app', $model->is_bookmarked ? 'Убрать из закладок' : 'Добавить в закладки'),
                '#',
                [
                    'class' => 'bookmark-banner__btn _icon-bookmark',
                    'data-sr-trigger' => 'ajax-button',
                    'data-sr-url' => Yii::$app->urlManager->createUrl([
                        'user/bookmark/toggle',
                        'id' => $model->id,
                        'redirect_url' => $bookmark_redirect_url ?? false,
                    ]),
                    'data-sr-callback' => "el.text(el.data('sr-bookmark_text-' + data.is_bookmarked));",
                    'data-sr-bookmark_text-true' => Yii::t('app', 'Добавить в закладки'),
                    'data-sr-bookmark_text-false' => Yii::t('app', 'Убрать из закладок'),
                ]
            ) ?>
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
                
                <?php if ($model->unit_json['default_time_for_test']) { ?>
                    <div class="test-info__item">
                        <div class="test-info__name">
                            <?= Yii::t('app', 'Рекомендуемое время заполнения:') ?>
                            <span><?= Yii::t('app', '{quantity} минут', ['quantity' => $model->unit_json['default_time_for_test']]) ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            <div class="timer-launcher">
                <div class="test-banner">
                    <div class="test-banner__wrapper">
                        <h5 class="test-banner__title">
                            <?= Yii::t('app', 'Хотите задать время для прохождения заданий?') ?>
                        </h5>
                        <div class="test-banner__inner">
                            <div class="test-banner__num">
                                <div class="input">
                                    <?= Html::textInput('test_time', $model->unit_json['default_time_for_test'], [
                                        'class' => 'input-num',
                                    ]) ?>
                                </div>
                                <label>
                                    <?= Yii::t('app', 'минут') ?>
                                </label>
                            </div>
                            
                            <div class="test-banner__btn">
                                <?= Html::button(Yii::t('app', 'Задать'), [
                                    'class' => 'primary-btn timer-launcher-start-btn',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <?= Html::button(null, [
                        'class' => 'test-banner__btn-cross _icon-cross timer-launcher-close-btn',
                    ]) ?>
                </div>
            </div>
            
            <div class="timer _lp" style="display: none;"></div>
        <?php } ?>
        
        <?= $model->unit_json['description'] ?>
        
        <?= LibraryAttachmentTemplateWidget::widget([
            'templates' => Yii::$app->services->array->toObjects($model->library_attachment_json['templates']),
        ]) ?>
        
        <?= Html::beginForm(['user/unit/submit', 'id' => $model->id], 'post', [
            'id' => 'unit-form',
        ]) ?>
        
        <?= LibraryAttachmentTestWidget::widget([
            'model' => $model,
            'tests' => Yii::$app->services->array->toObjects($model->library_attachment_json['tests']),
        ]) ?>
        
        <?= LibraryAttachmentTestPackWidget::widget([
            'model' => $model,
            'test_packs' => Yii::$app->services->array->toObjects($model->library_attachment_json['test_packs']),
        ]) ?>
        
        <button type="submit" class="test__btn primary-btn">
            <span class="_icon-arrow">
                <?=  Yii::t('app', $test_quantity ? 'Завершить тестирование' : 'Вперёд') ?>
            </span>
        </button>
        
        <?= Html::endForm() ?>
    </article>
</div>
