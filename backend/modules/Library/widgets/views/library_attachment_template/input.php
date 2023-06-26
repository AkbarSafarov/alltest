<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use alexantr\tinymce\TinyMCE;
use alexantr\elfinder\InputFile;
use kartik\file\FileInput;

$name_prefix = "LibraryAttachmentTemplate[$key]";
$label_translit = Inflector::slug($label);

echo Html::label($label);
echo Html::hiddenInput("{$name_prefix}[label]", $label);
echo Html::hiddenInput("{$name_prefix}[input_type]", $input_type);
echo Html::hiddenInput("{$name_prefix}[template_id]", $template_id);
echo Html::hiddenInput("{$name_prefix}[group]", $group);

switch ($input_type) {
    case 'text_input':
        echo Html::textInput("{$name_prefix}[value]", $value, ['class' => 'form-control']);
        break;
        
    case 'text_area':
        echo Html::textArea("{$name_prefix}[value]", $value, ['class' => 'form-control', 'rows' => 5]);
        break;
        
    case 'text_editor':
        echo TinyMCE::widget([
            'name' => "{$name_prefix}[value]",
            'value' => $value,
            'id' => "redactor-$key-$label_translit",
        ]);
        break;
        
    case 'file_manager':
        echo Html::tag(
            'div',
            InputFile::widget([
                'name' => "{$name_prefix}[value]",
                'value' => $value,
                'id' => "elfinder-$key-$label_translit",
            ]),
            ['data-toggle' => 'elfinder']
        );
        break;
        
    case 'file_input':
        $upload_extensions = Yii::$app->services->array->leaves(Yii::$app->params['extensions']);
        $upload_extensions = array_map(fn ($value) => ".$value", $upload_extensions);
        
        $file = Yii::getAlias("@frontend/web$value");
        
        if (is_file($file)) {
            $widget_filetype = mime_content_type($file);
            $widget_type = explode('/', $widget_filetype)[0];
        }
        
        echo FileInput::widget([
            'name' => "{$name_prefix}[value]",
            'id' => "fileinput-$key-$label_translit",
            'options' => [
                'accept' => implode(', ', $upload_extensions),
                'multiple' => false,
            ],
            'pluginOptions' => array_merge(Yii::$app->params['fileInput_plugin_options'], [
                'maxFileSize' => 1024 * 1024 * 100,
                'deleteUrl' => Yii::$app->urlManager->createUrl(['library/attachment-template/file-delete', 'id' => $key]),
                'initialPreview' => $value ?? '',
                'initialPreviewConfig' => [
                    [
                        'type' => $widget_type ?? 'other',
                        'filetype' => $widget_filetype ?? 'other',
                        'key' => $value,
                        'downloadUrl' => $value,
                    ]
                ],
            ]),
        ]);
        break;
}
