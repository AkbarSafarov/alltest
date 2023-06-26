<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'update-form',
        'data-sr-trigger' => 'ajax-form',
        'data-sr-wrapper' => '#nav-item-content',
    ],
]); ?>


<div class="card">
    <div class="card-header bg-primary p-3">
        <h5 class="m-0 text-white">
            <?= $this->title ?>
        </h5>
    </div>
    
    <div class="card-body">
        <div class="row">
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'url')->textInput() ?>
            
            <?php
                if ($model->isNewRecord) {
                    echo $form->field($model, 'parent_id')->dropDownList(
                        ArrayHelper::map($menu_list, 'id', 'text'),
                        [
                            'class' => 'form-control',
                            'data-toggle' => 'select2',
                        ]
                    );
                }
            ?>
        </div>
        
        <div class="text-sm-end">
            <?= Html::submitButton(
                Html::tag('i', null, ['class' => 'fas fa-save me-2']) . Yii::t('app', 'Сохранить'),
                ['class' => 'btn btn-primary']
            ) ?>
            
            <?php
                if (!$model->isNewRecord) {
                    if ($model->state == 'active') {
                        echo Html::a(
                            Html::tag('i', null, ['class' => 'fas fa-trash me-2']) . Yii::t('app', 'Удалить'),
                            ['delete', 'id' => $model->id],
                            [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Вы уверены?'),
                                    'method' => 'post',
                                ]
                            ]
                        );
                    } else {
                        echo Html::a(
                            Html::tag('i', null, ['class' => 'fas fa-trash-restore me-2']) . Yii::t('app', 'Восстановить'),
                            ['delete', 'id' => $model->id],
                            [
                                'class' => 'btn btn-info',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Вы уверены?'),
                                    'method' => 'post',
                                ]
                            ]
                        );
                    }
                }
            ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
