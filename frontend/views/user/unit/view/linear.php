<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\widgets\LibraryAttachmentTemplateWidget;
use frontend\widgets\LibraryAttachmentTestWidget;
use frontend\widgets\LibraryAttachmentTestPackWidget;

$prev_unit = $model->service->prev();
$next_unit = $model->service->next();

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
        <?php if ($model->available_from < date('Y-m-d H:i:s')) { ?>
            <?php if ($test_quantity) { ?>
                <div class="test-info">
                    <div class="test-info__item">
                        <div class="test-info__name">
                            <?= Yii::t('app', 'Тест состоит из') ?>
                            <span><?= Yii::t('app', '{quantity} вопросов', ['quantity' => $test_quantity]) ?></span>
                        </div>
                    </div>
                </div>
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
            
            <?php if ($test_quantity) { ?>
                <div class="unit__links">
                    <button type="submit" class="unit__link-next primary-btn">
                        <span class="_icon-arrow">
                            <?=  Yii::t('app', 'Завершить тестирование') ?>
                        </span>
                    </button>
                </div>
            <?php } ?>
            
            <div class="unit__links">
                <?php if ($prev_unit) { ?>
                    <a class="unit__link-prev tertinary-btn"
                       href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $prev_unit->tree, 'id' => $prev_unit->id]) ?>"
                    >
                        <span class="_icon-arrow">
                            <?= Yii::t('app', 'Назад') ?>
                        </span>
                    </a>
                <?php } ?>
                
                <?php if ($next_unit && (!$test_quantity || $next_unit->is_unlocked)) { ?>
                    <?php if ($next_unit->is_unlocked) { ?>
                        <a class="unit__link-next primary-btn"
                           href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $next_unit->tree, 'id' => $next_unit->id]) ?>"
                        >
                            <span class="_icon-arrow">
                                <?= Yii::t('app', 'Вперёд') ?>
                            </span>
                        </a>
                    <?php } elseif (!$test_quantity) { ?>
                        <button type="submit" class="unit__link-next primary-btn">
                            <span class="_icon-arrow">
                                <?=  Yii::t('app', 'Вперёд') ?>
                            </span>
                        </button>
                    <?php } ?>
                <?php } ?>
            </div>
            
            <?= Html::endForm() ?>
        <?php } else { ?>
            <div class="test-info test-info--result">
                <?php if ($test_quantity) { ?>
                    <div class="test-info__item">
                        <div class="test-info__name">
                            <?= Yii::t('app', 'Тест состоит из') ?>
                            <span><?= Yii::t('app', '{quantity} вопросов', ['quantity' => $test_quantity]) ?></span>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="test-result-unsuccessful__status test-result--status">
                    <div class="test-result-unsuccessful__label">
                        <?= Yii::t('app', 'Тест не пройден!') ?>
                    </div>
                    <div class="test-result-unsuccessful__time _icon-clock">
                        <div class="test-result-unsuccessful__time-label">
                            <?= Yii::t('app', 'Следующая попытка через:') ?>
                        </div>
                        <h6 class="test-result-unsuccessful__time-num">
                            <?= Yii::t('app', '{hours} часов', [
                                'hours' => Html::tag('span', round((strtotime($model->available_from) - time()) / 3600)),
                            ]) ?>
                        </h6>
                    </div>
                </div>
            </div>
            
            <div class="unit__links">
                <?php if ($prev_unit) { ?>
                    <a class="unit__link-prev tertinary-btn"
                       href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $prev_unit->tree, 'id' => $prev_unit->id]) ?>"
                    >
                        <span class="_icon-arrow">
                            <?= Yii::t('app', 'Назад') ?>
                        </span>
                    </a>
                <?php } ?>
                
                <?php if ($next_unit && $next_unit->is_unlocked) { ?>
                    <a class="unit__link-next primary-btn"
                       href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $next_unit->tree, 'id' => $next_unit->id]) ?>"
                    >
                        <span class="_icon-arrow">
                            <?= Yii::t('app', 'Вперёд') ?>
                        </span>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </article>
</div>
