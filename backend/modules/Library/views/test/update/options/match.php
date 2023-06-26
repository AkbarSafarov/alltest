<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$answers_count = (int)Yii::$app->services->settings->default_test_answers_count;
$options = ArrayHelper::merge($model->options, [
    'answers' => array_merge(['__key__' => null], ...array_fill(0, $model->isNewRecord ? $answers_count : 0, [null])),
]);

?>

<table class="table table-bordered table-striped table-relations">
    <thead>
        <tr>
            <th style="width: 50px;"></th>
            <th><?= Yii::t('app', 'Ключи') ?></th>
            <th><?= Yii::t('app', 'Значения') ?></th>
            <th style="width: 50px;"></th>
        </tr>
    </thead>
    
    <tbody data-toggle="sortable">
        <?php foreach ($options['answers'] as $key => $value) { ?>
            <tr class="<?= $key === '__key__' ? 'table-new-relation' : null ?>" data-table="options-match-<?= $model->id ?? 0 ?>">
                <td>
                    <div class="btn btn-primary table-sorter">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </td>
                
                <td>
                    <?= $form->field($model, "options[answers][$key][key]", [
                        'template' => '{input}',
                        'options' => ['class' => null],
                    ])->textArea([
                        'name' => "{$name_prefix}[answers][{$key}][key]",
                        'rows' => 5,
                        'data-toggle' => 'redactor-modal',
                        'required' => true,
                    ]) ?>
                </td>
                
                <td>
                    <?= $form->field($model, "options[answers][$key][value]", [
                        'template' => '{input}',
                        'options' => ['class' => null],
                    ])->textArea([
                        'name' => "{$name_prefix}[answers][{$key}][value]",
                        'rows' => 5,
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
                <button type="button" class="btn btn-success w-100 btn-add" data-table="options-match-<?= $model->id ?? 0 ?>">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    </tfoot>
</table>
