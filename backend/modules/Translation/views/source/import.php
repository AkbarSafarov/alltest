<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'translations-import-form',
                'data-sr-trigger' => 'ajax-form',
                'data-sr-wrapper' => '#main-modal',
            ],
        ]); ?>
        
        <div class="modal-header">
            <h4 class="modal-title">
                <?= Yii::t('app', 'Импорт переводов') ?>
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
            <div class="row">
                <?= $form->field($model, 'category')->dropDownList($model->available_categories, ['prompt' => '']); ?>
                <?= $form->field($model, 'file')->fileInput(['class' => 'form-control h-auto']); ?>
            </div>
        </div>
        
        <div class="modal-footer">
            <?= Html::submitButton(
                Yii::t('app', 'Ипортировать'),
                ['class' => 'btn btn-primary w-100']
            ) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>
