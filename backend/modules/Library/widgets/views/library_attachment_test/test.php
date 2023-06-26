<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use alexantr\tinymce\TinyMCE;

$name_prefix = "LibraryAttachmentTest[$model->id]";

?>

<td style="width: 50px;">
    <div class="btn btn-primary table-sorter">
        <i class="fas fa-arrows-alt"></i>
    </div>
</td>

<td>
    <div class="mb-3">
        <?php
            echo Html::label($model->getAttributeLabel('input_type'));
            echo Html::hiddenInput("{$name_prefix}[input_type]", $model->input_type);
            echo Html::dropDownList(
                null,
                $model->input_type,
                ArrayHelper::getColumn($input_types, 'label'),
                [
                    'class' => 'form-control',
                    'disabled' => true,
                ]
            );
        ?>
    </div>
    
    <div class="mb-3 mt-3">
        <?php
            echo Html::label($model->getAttributeLabel('question'));
            echo TinyMCE::widget([
                'name' => "{$name_prefix}[question]",
                'value' => $model->question,
                'id' => "redactor-$model->id",
            ]);
        ?>
    </div>
    
    <div class="mb-3 mt-3">
        <?php
            echo $this->render("@backend/modules/Library/views/test/update/options/$model->input_type", [
                'model' => $model,
                'form' => $form,
                'name_prefix' => "LibraryAttachmentTest[$model->id][options]",
                'sequence_file_input_urls' => [
                    'sort' => ['library/attachment-test/file-sort', 'id' => $model->id],
                    'delete' => ['library/attachment-test/file-delete', 'id' => $model->id],
                ],
            ]);
        ?>
    </div>
</td>

<td style="width: 50px;">
    <button type="button" class="btn btn-danger btn-remove">
        <span class="fas fa-times"></span>
    </button>
</td>
