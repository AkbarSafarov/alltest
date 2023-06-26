<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'integration-library-test-category-import-form',
                'data-sr-trigger' => 'ajax-form',
                'data-sr-wrapper' => '#main-modal',
            ],
            'fieldConfig' => [
                'options' => ['class' => 'col-sm-12 mb-3'],
            ],
        ]); ?>
        
        <div class="modal-header">
            <h4 class="modal-title">
                <?= Yii::t('app', 'Импорт библиотеки тестов') ?>
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
            <div class="row">
                <?= $form->field($model, 'file')->fileInput(['class' => 'form-control h-auto']) ?>
                <?= $form->field($model, 'id', ['template' => '{input}{error}'])->hiddenInput() ?>
            </div>
        </div>
        
        <div class="modal-footer">
            <?= Html::submitButton(
                Yii::t('app', 'Импортировать'),
                ['class' => 'btn btn-primary w-100']
            ) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>
