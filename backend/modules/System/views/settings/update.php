<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use alexantr\elfinder\InputFile;
use alexantr\tinymce\TinyMCE;

$this->title = Yii::t('app', 'Настройки');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'options' => ['id' => 'update-form'],
    'fieldConfig' => [
        'enableClientValidation' => false,
        'options' => ['class' => 'mb-3'],
    ]
]); ?>

<div class="card">
    <div class="card-body">
        <div class="text-sm-end mb-3">
            <?= Yii::$app->services->html->saveButtons(['save']) ?>
        </div>
        
        <ul id="sortable" class="p-0 m-0">
            <?php foreach ($settings as $s) { ?>
                <li class="d-flex p-3" data-id="<?= $s->id ?>">
                    <div class="me-3">
                        <label class="form-label">&nbsp;</label><br>
                        <div class="btn btn-primary table-sorter">
                            <i class="fas fa-arrows-alt"></i>
                        </div>
                    </div>
                    
                    <div class="w-100">
                        <?php
                            switch ($s->type) {
                                case 'text_input':
                                    echo $form->field($s, 'value')->textInput([
                                        'name' => "SystemSettings[$s->id][value]",
                                        'id' => "systemsettings-$s->id",
                                    ])->label($s->label);
                                    
                                    break;
                                case 'text_area':
                                    echo $form->field($s, 'value')->textArea([
                                        'name' => "SystemSettings[$s->id][value]",
                                        'id' => "systemsettings-$s->id",
                                        'rows' => 5,
                                    ])->label($s->label);
                                    
                                    break;
                                case 'checkbox':
                                    echo Html::label('&nbsp;<br>') . $form->field($s, 'value')->checkbox([
                                        'name' => "SystemSettings[$s->id][value]",
                                        'id' => "systemsettings-$s->id",
                                        'class' => 'form-check-input',
                                    ])->label($s->label, [
                                        'class' => 'form-check-label',
                                    ]);
                                    
                                    break;
                                case 'tinymce':
                                    echo $form->field($s, 'value')->widget(TinyMCE::className(), [
                                        'options' => [
                                            'name' => "SystemSettings[$s->id][value]",
                                            'id' => "systemsettings-$s->id",
                                        ],
                                    ])->label($s->label);
                                    
                                    break;
                                case 'elfinder':
                                    echo $form->field($s, 'value')->widget(InputFile::className(), [
                                        'options' => [
                                            'name' => "SystemSettings[$s->id][value]",
                                            'id' => "systemsettings-$s->id",
                                        ]
                                    ])->label($s->label);
                                    
                                    break;
                            }
                        ?>
                    </div>
                </li>
            <?php } ?>
        </ul>
        
        <div class="text-sm-end mt-3">
            <?= Yii::$app->services->html->saveButtons(['save']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let id, action, sendData,
            oldIndex, newIndex;
        
        action = '<?= Yii::$app->urlManager->createUrl(['system/settings/sort']) ?>';
        
        $('#sortable').sortable({
            handle: '.table-sorter',
            placeholder: 'sortable-placeholder mb-2',
            start: function(event, ui) {
                ui.placeholder.height(ui.helper.outerHeight());
                id = ui.item[0].dataset.id;
                oldIndex = ui.item.index();
            },
            stop: function(event, ui) {
                newIndex = ui.item.index();
                
                if (oldIndex !== newIndex) {
                    sendData = {
                        "_csrf-backend": $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        old_index: oldIndex,
                        new_index: newIndex
                    };
                    
                    $.post(action, sendData);
                }
            }
        }).disableSelection();
    });
</script>
