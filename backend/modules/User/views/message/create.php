<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use alexantr\tinymce\TinyMCE;

?>

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                <?= Yii::t('app', 'Отправка сообщения') ?>
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'id' => 'user-message-create-form',
                    'data-sr-trigger' => 'ajax-form',
                    'data-sr-wrapper' => '#main-modal',
                ],
            ]); ?>
            
            <div class="row">
                <?= $form->field($model, 'theme')->textInput() ?>
                <?= $form->field($model, 'file')->fileInput(['class' => 'form-control h-auto']) ?>
                <?= $form->field($model, 'text', [
                    'options' => ['class' => 'mb-3'],
                ])->widget(TinyMCE::className(), [
                    'options' => ['id' => 'text_editor-' . uniqid()]
                ]) ?>
            </div>
            
            <?= $form->field($model, 'user_search_params', [
                'template' => '{input}{error}',
            ])->hiddenInput(['value' => $user_search_params]) ?>
            
            <?php ActiveForm::end(); ?>
        </div>
        
        <div class="modal-footer">
            <button type="submit" form="user-message-create-form" class="btn w-100 btn-primary">
                <?= Yii::t('app', 'Отправить') ?>
            </button>
        </div>
    </div>
</div>