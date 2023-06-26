<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$courses_price = array_map(fn($value) => $value->service->realPrice(), $model->activeCourses);
$is_in_cart = ArrayHelper::getValue(Yii::$app->services->cart->cart, "products.CoursePackage-$model->id");

?>

<div class="package-course__wrapper">
    <h3 class="package-course__title">
        <?= $model->name ?>
    </h3>
    
    <div class="package-course__cards">
        <?= $this->render('@frontend/views/particles/courses', ['models' => $model->activeCourses]); ?>
    </div>
    
    <div class="package-course__total">
        <div class="package-course__total-items">
            <div class="package-course__total-item">
                <div class="package-course__total-name">
                    <?= Yii::t('app', 'Все курсы:') ?>
                </div>
                <h3 class="package-course__total-num">
                    <?= Yii::t('app', '{price} UZS', [
                        'price' => Yii::$app->formatter->asDecimal(array_sum($courses_price)),
                    ]) ?>
                </h3>
            </div>
            <div class="package-course__total-item">
                <div class="package-course__total-name">
                    <?= Yii::t('app', 'Пакетом:') ?>
                </div>
                <h3 class="package-course__total-num package-course__total-num--color">
                    <?= Yii::t('app', '{price} UZS', [
                        'price' => Yii::$app->formatter->asDecimal($model->service->realPrice()),
                    ]) ?>
                </h3>
            </div>
            <div class="package-course__total-item">
                <div class="package-course__total-name">
                    <?= Yii::t('app', 'Выгода:') ?>
                </div>
                <h3 class="package-course__total-num package-course__total-num--color">
                    <?= round(100 - (array_sum($courses_price) ? $model->service->realPrice() / array_sum($courses_price) * 100 : 100)) ?>%
                </h3>
            </div>
        </div>
        
        <?= Html::beginForm(['cart/change'], 'post', [
            'data-toggle' => 'cart-change-form',
        ]); ?>
        
        <?= Html::hiddenInput('id', $model->id) ?>
        <?= Html::hiddenInput('type', 'CoursePackage') ?>
        
        <button type="submit" class="package-course__total-btn primary-btn btn-with-text">
            <?= Yii::t('app', $is_in_cart ? 'В корзине' : 'Купить') ?>
        </button>
        
        <?= Html::endForm(); ?>
    </div>
</div>
