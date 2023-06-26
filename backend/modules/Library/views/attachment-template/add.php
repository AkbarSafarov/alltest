<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$group = uniqid();

?>

<td style="width: 50px;">
    <div class="btn btn-primary table-sorter">
        <i class="fas fa-arrows-alt"></i>
    </div>
</td>

<td>
    <div class="card-header bg-primary text-white text-center">
        <?= $template->name ?>
    </div>
    
    <div class="card-body p-3 border">
        <?php if ($template->inputs) { ?>
            <?php foreach ($template->inputs as $label => $input_type) { ?>
                <div class="mb-3 mb-3">
                    <?= $this->render('@backend/modules/Library/widgets/views/library_attachment_template/input', [
                        'key' => uniqid(),
                        'label' => $label,
                        'input_type' => $input_type,
                        'template_id' => $template->id,
                        'group' => $group,
                        'value' => null,
                    ]) ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <?php
                $key = uniqid();
                $name_prefix = "LibraryAttachmentTemplate[$key]";
                
                echo Html::hiddenInput("{$name_prefix}[label]", $template->name);
                echo Html::hiddenInput("{$name_prefix}[input_type]", 'static');
                echo Html::hiddenInput("{$name_prefix}[template_id]", $template->id);
                echo Html::hiddenInput("{$name_prefix}[group]", $group);
                
                echo Html::label($template->name);
            ?>
        <?php } ?>
    </div>
</td>

<td style="width: 50px;">
    <button type="button" class="btn btn-danger btn-remove">
        <span class="fas fa-times"></span>
    </button>
</td>
