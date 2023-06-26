<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Order\models\OrderPromocode;
use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;

$promocodes = OrderPromocode::find()->where(['id' => $model->promocode_id])->asArray()->all();
$courses = Course::find()->where(['id' => $model->search_course_ids])->asArray()->all();
$packages = CoursePackage::find()->where(['id' => $model->search_package_ids])->asArray()->all();

?>

<div class="accordion custom-accordion mb-3">
    <div class="card mb-0">
        <div class="card-header p-0 bg-primary">
            <h5 class="m-0 position-relative">
                <a class="custom-accordion-title p-3 text-white d-block collapsed" data-bs-toggle="collapse" href="#tab-filter">
                    <?= Yii::t('app', 'Фильтр') ?>
                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                </a>
            </h5>
        </div>
        
        <div id="tab-filter" class="collapse">
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'fieldConfig' => [
                        'options' => ['class' => 'col-md'],
                    ],
                ]) ?>
                
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <?= $form->field($model, 'id')->textInput() ?>
                            
                            <?= $form->field($model, 'student_id')->widget(Select2::className(), [
                                'data' => [$model->student_id => ArrayHelper::getValue($model->student, 'username')],
                                'options' => ['placeholder' => ' '],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl(['items-list/users', 'show_deleted' => true]),
                                        'dataType' => 'json',
                                        'delay' => 300,
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]) ?>
                            
                            <?= $form->field($model, 'promocode_id')->widget(Select2::className(), [
                                'data' => ArrayHelper::map($promocodes, 'id', 'key'),
                                'options' => ['placeholder' => ' '],
                                'pluginOptions' => [
                                    'multiple' => true,
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl(['items-list/order-promocodes', 'show_deleted' => true]),
                                        'dataType' => 'json',
                                        'delay' => 300,
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]) ?>
                        </div>
                        
                        <div class="row mt-3">
                            <?= $form->field($model, 'status')->widget(Select2::className(), [
                                'data' => ArrayHelper::getColumn($model->enums->statuses(), 'label'),
                                'options' => [
                                    'placeholder' => ' ',
                                    'value' => $model->status ?: ['paid_paycom', 'paid_click'],
                                ],
                                'pluginOptions' => [
                                    'multiple' => true,
                                    'allowClear' => true,
                                ],
                            ]) ?>
                        </div>
                        
                        <div class="row mt-3">
                            <?= $form->field($model, 'search_created_at_from')->textInput(['data-toggle' => 'datetimepicker']) ?>
                            <?= $form->field($model, 'search_created_at_to')->textInput(['data-toggle' => 'datetimepicker']) ?>
                            <?= $form->field($model, 'search_checkout_price_from')->textInput() ?>
                            <?= $form->field($model, 'search_checkout_price_to')->textInput() ?>
                        </div>
                        
                        <div class="row mt-3">
                            <?= $form->field($model, 'search_course_ids')->widget(Select2::className(), [
                                'data' => ArrayHelper::map($courses, 'id', 'name'),
                                'options' => ['placeholder' => ' '],
                                'pluginOptions' => [
                                    'multiple' => true,
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl(['items-list/courses', 'show_deleted' => true]),
                                        'dataType' => 'json',
                                        'delay' => 300,
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]) ?>
                            
                            <?= $form->field($model, 'search_package_ids')->widget(Select2::className(), [
                                'data' => ArrayHelper::map($packages, 'id', 'name'),
                                'options' => ['placeholder' => ' '],
                                'pluginOptions' => [
                                    'multiple' => true,
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl(['items-list/course-packages', 'show_deleted' => true]),
                                        'dataType' => 'json',
                                        'delay' => 300,
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <?= Html::label('&nbsp;', null, ['class' => 'form-label']) ?><br>
                        <?= Html::submitButton(
                            Html::tag('i', null, ['class' => 'fa fa-filter me-2']) . Yii::t('app', 'Применить'),
                            ['class' => 'btn btn-info w-100']
                        ) ?>
                        
                        <?= Html::resetButton(
                            Html::tag('i', null, ['class' => 'fa fa-power-off me-2']) . Yii::t('app', 'Сбросить'),
                            ['class' => 'btn btn-danger w-100 mt-2']
                        ) ?>
                    </div>
                </div>
                
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
