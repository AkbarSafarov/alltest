<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$answers_count = (int)Yii::$app->services->settings->default_test_answers_count;
$options = ArrayHelper::merge($model->options, [
    'options' => array_merge(['__key__' => null], ...array_fill(0, $model->isNewRecord ? $answers_count : 0, [null])),
    'answers' => [],
]);

?>

<table class="table table-bordered table-striped table-relations tests-validation-table">
    <thead>
        <tr>
            <th style="width: 50px;"></th>
            <th style="width: 70px;"></th>
            <th><?= $model->getAttributeLabel('options') ?></th>
            <th style="width: 50px;"></th>
        </tr>
    </thead>
    
    <tbody data-toggle="sortable">
        <?php foreach ($options['options'] as $key => $value) { ?>
            <tr class="<?= $key === '__key__' ? 'table-new-relation' : null ?>" data-table="options-checkbox-<?= $model->id ?? 0 ?>">
                <td>
                    <div class="btn btn-primary table-sorter">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </td>
                
                <td>
                    <div class="form-check form-check-primary">
                        <?= Html::checkbox(
                            "{$name_prefix}[answers][]",
                            in_array($key, ArrayHelper::getValue($options, 'answers'), true),
                            [
                                'class' => 'form-check-input form-check-input-lg',
                                'id' => Html::getInputId($model, ($model->id ?? 0) . "options[answers][$key]"),
                                'value' => $key,
                            ]
                        ) ?>
                        
                        <?= Html::label(null, Html::getInputId($model, ($model->id ?? 0) . "options[answers][$key]"), [
                            'class' => 'form-check-label',
                        ]) ?>
                    </div>
                </td>
                
                <td>
                    <?= $form->field($model, "options[options][$key]", [
                        'template' => '{input}',
                        'options' => ['class' => null],
                    ])->textInput([
                        'name' => "{$name_prefix}[options][{$key}]",
                        'data-toggle' => 'redactor-modal',
                        'required' => true,
                    ]) ?>
                </td>
                
                <td>
                    <button type="button" class="btn btn-danger btn-remove">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    
    <tfoot>
        <tr>
            <td colspan="4">
                <button type="button" class="btn btn-success w-100 btn-add" data-table="options-checkbox-<?= $model->id ?? 0 ?>">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    </tfoot>
</table>
