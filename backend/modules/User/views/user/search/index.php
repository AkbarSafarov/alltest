<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Course\models\Course;

$course = Course::find()->where(['id' => $model->search_course_id])->one();

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
                        'options' => ['class' => 'col-md-3'],
                    ],
                ]) ?>
                
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <?= $form->field($model, 'search_common', ['options' => ['class' => 'col-6']])->textInput() ?>
                            <?= $form->field($model, 'search_course_id')->widget(Select2::className(), [
                                'data' => [$model->search_course_id => ArrayHelper::getValue($course, 'name')],
                                'options' => ['placeholder' => ' '],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl(['items-list/courses', 'show_deleted' => true]),
                                        'dataType' => 'json',
                                        'delay' => 300,
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]) ?>
                            
                            <?= $form->field($model, 'search_course_status')->dropDownList([
                                'active' => Yii::t('app', 'Активные'),
                                'inactive' => Yii::t('app', 'Неактивные'),
                                'finished' => Yii::t('app', 'Завершившие'),
                                'subscribers' => Yii::t('app', 'Подписчики'),
                            ], [
                                'prompt' => '',
                            ]) ?>
                            
                            <div class="my-2"></div>
                            
                            <?= $form->field($model, 'search_created_at_from')->textInput(['data-toggle' => 'datetimepicker']) ?>
                            <?= $form->field($model, 'search_created_at_to')->textInput(['data-toggle' => 'datetimepicker']) ?>
                            <?= $form->field($model, 'search_last_activity_from')->textInput(['data-toggle' => 'datetimepicker']) ?>
                            <?= $form->field($model, 'search_last_activity_to')->textInput(['data-toggle' => 'datetimepicker']) ?>
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
