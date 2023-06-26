<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Order\enums\OrderEnums;
use backend\modules\Order\models\OrderDiscount;
use backend\modules\Order\models\OrderPromocode;
use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;

$discount_id = ArrayHelper::getValue($filter, 'discount_id');
$promocode_id = ArrayHelper::getValue($filter, 'promocode_id');
$course_id = ArrayHelper::getValue($filter, 'course_id');
$package_id = ArrayHelper::getValue($filter, 'package_id');

$discount = OrderDiscount::findOne($discount_id);
$promocode = OrderPromocode::findOne($promocode_id);
$course = Course::findOne($course_id);
$package = CoursePackage::findOne($package_id);

?>

<div class="analytics-filter-wrapper accordion custom-accordion mb-3">
    <div class="card mb-0">
        <div class="card-header p-0">
            <h5 class="m-0 bg-primary d-flex">
                <a class="custom-accordion-title position-relative p-3 w-100" data-bs-toggle="collapse" href="#tab-filter-<?= $key ?>">
                    <span class="text-white">
                        <?= Yii::t('app', 'Фильтр №{number}', ['number' => $key + 1]) ?>
                        <i class="mdi mdi-chevron-down accordion-arrow"></i>
                    </span>
                </a>
                
                <button type="button" class="btn btn-danger px-3" onclick="$(this).closest('.analytics-filter-wrapper').remove();">
                    <i class="fas fa-times"></i>
                </button>
            </h5>
        </div>
        
        <div id="tab-filter-<?= $key ?>" class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Тип суммы'), null, ['class' => 'form-label']) ?>
                        <?= Html::dropDownList(
                            "filters[$key][price_type]",
                            ArrayHelper::getValue($filter, 'price_type', 'checkout_price'),
                            array_filter(
                                ArrayHelper::getColumn($enums->orderPrices(), ['label']),
                                fn($key) => in_array($key, ['total_price', 'checkout_price']),
                                ARRAY_FILTER_USE_KEY
                            ),
                            [
                                'class' => 'form-control',
                            ]
                        ) ?>
                    </div>
                    
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Дата (от)'), null, ['class' => 'form-label']) ?>
                        <?= Html::textInput("filters[$key][date_from]", ArrayHelper::getValue($filter, 'date_from'), [
                            'class' => 'form-control',
                            'data-toggle' => 'datetimepicker',
                        ]) ?>
                    </div>
                    
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Дата (до)'), null, ['class' => 'form-label']) ?>
                        <?= Html::textInput("filters[$key][date_to]", ArrayHelper::getValue($filter, 'date_to'), [
                            'class' => 'form-control',
                            'data-toggle' => 'datetimepicker',
                        ]) ?>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col">
                        <?= Html::label(Yii::t('app', 'Статус'), null, ['class' => 'form-label']) ?>
                        <?= Select2::widget([
                            'name' => "filters[$key][status]",
                            'value' => ArrayHelper::getValue($filter, 'status', ['paid_paycom', 'paid_click']),
                            'data' => ArrayHelper::getColumn(OrderEnums::statuses(), 'label'),
                            'options' => [
                                'placeholder' => ' ',
                                'id' => "analytics-filter-status-$key",
                            ],
                            'pluginOptions' => [
                                'multiple' => true,
                                'allowClear' => true,
                            ]
                        ]) ?>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Скидка'), null, ['class' => 'form-label']) ?>
                        <?= Html::dropDownList(
                            "filters[$key][discount_id]",
                            $discount_id,
                            $discount ? [$discount->id => $discount->name] : [],
                            [
                                'class' => 'form-control',
                                'data-toggle' => 'select2-ajax',
                                'data-action' => Yii::$app->urlManager->createUrl(['items-list/order-discounts', 'show_deleted' => true]),
                            ]
                        ) ?>
                    </div>
                    
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Промокод'), null, ['class' => 'form-label']) ?>
                        <?= Html::dropDownList(
                            "filters[$key][promocode_id]",
                            $promocode_id,
                            $promocode ? [$promocode->id => $promocode->key] : [],
                            [
                                'class' => 'form-control',
                                'data-toggle' => 'select2-ajax',
                                'data-action' => Yii::$app->urlManager->createUrl(['items-list/order-promocodes', 'show_deleted' => true]),
                            ]
                        ) ?>
                    </div>
                    
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Курс'), null, ['class' => 'form-label']) ?>
                        <?= Html::dropDownList(
                            "filters[$key][course_id]",
                            $course_id,
                            $course ? [$course->id => $course->name] : [],
                            [
                                'class' => 'form-control',
                                'data-toggle' => 'select2-ajax',
                                'data-action' => Yii::$app->urlManager->createUrl(['items-list/courses', 'show_deleted' => true]),
                            ]
                        ) ?>
                    </div>
                    
                    <div class="col-md">
                        <?= Html::label(Yii::t('app', 'Пакет курсов'), null, ['class' => 'form-label']) ?>
                        <?= Html::dropDownList(
                            "filters[$key][package_id]",
                            $package_id,
                            $package ? [$package->id => $package->name] : [],
                            [
                                'class' => 'form-control',
                                'data-toggle' => 'select2-ajax',
                                'data-action' => Yii::$app->urlManager->createUrl(['items-list/course-packages', 'show_deleted' => true]),
                            ]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>