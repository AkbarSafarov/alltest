<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use alexantr\elfinder\InputFile;
use alexantr\tinymce\TinyMCE;

$attrs = ArrayHelper::index($model->attrs, 'name');

foreach ($attrs as $a) {
    $name = $a['name'];
    $new_group['__key__'][$name] = null;
}

$groups = ArrayHelper::merge($model->value, $new_group);

?>

<div class="mb-3">
    <?= Html::label($model->label, null, ['class' => 'form-label']) ?>
    <?= Html::hiddenInput("StaticpageBlock[$model->id][value]", null); ?>
    
    <table class="table table-bordered table-striped table-relations">
        <tbody data-toggle="sortable">
            <?php foreach ($groups as $key => $group) { ?>
                <tr class="<?= strval($key) == '__key__' ? 'table-new-relation' : null ?>" data-table="<?= "groups-$model->id" ?>">
                    <td>
                        <div class="btn btn-primary table-sorter">
                            <i class="fas fa-arrows-alt"></i>
                        </div>
                    </td>
                    
                    <td class="w-100">
                        <?php foreach ($attrs as $a_key => $a_value) { ?>
                            <div class="mb-3 mb-3">
                                <label class="form-label">
                                    <?= $a_value['label'] ?>
                                </label>
                                
                                <?php
                                    $input_name = "StaticpageBlock[$model->id][value][$key][$a_key]";
                                    $input_value = ArrayHelper::getValue($group, $a_key);
                                    
                                    switch ($a_value['type']) {
                                        case 'text_input':
                                            echo Html::textInput(
                                                $input_name,
                                                $input_value,
                                                ['class' => 'form-control']
                                            );
                                            
                                            break;
                                        case 'text_area':
                                            echo Html::textArea(
                                                $input_name,
                                                $input_value,
                                                ['class' => 'form-control', 'rows' => 5]
                                            );
                                            
                                            break;
                                        case 'checkbox':
                                            $checkbox = Html::checkbox(
                                                $input_name,
                                                $input_value,
                                                [
                                                    'uncheck' => 0,
                                                    'id' => "staticpageblock-$model->id-$key-$a_key",
                                                    'class' => 'form-check-input',
                                                ]
                                            );
                                            
                                            $checkbox .= Html::label(null, "staticpageblock-$model->id-$key-$a_key", ['class' => 'form-check-label']);
                                            echo Html::tag('div', $checkbox, ['class' => 'form-check form-check-primary']);
                                            
                                            break;
                                        case 'elfinder':
                                            echo Html::tag(
                                                'div',
                                                InputFile::widget([
                                                    'id' => "elfinder-$model->id-$key-$a_key",
                                                    'name' => $input_name,
                                                    'value' => $input_value,
                                                ]),
                                                ['data-toggle' => 'elfinder']
                                            );
                                            
                                            break;
                                        case 'tinymce':
                                            echo TinyMCE::widget([
                                                'name' => $input_name,
                                                'value' => $input_value,
                                                'id' => "redactor-$model->id-$key-$a_key",
                                                'options' => [
                                                    'data-toggle' => 'redactor',
                                                ]
                                            ]);
                                            
                                            break;
                                    }
                                ?>
                            </div>
                        <?php } ?>
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
                <td colspan="3">
                    <button type="button" class="btn btn-success w-100 btn-add" data-table="<?= "groups-$model->id" ?>">
                        <i class="fas fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
