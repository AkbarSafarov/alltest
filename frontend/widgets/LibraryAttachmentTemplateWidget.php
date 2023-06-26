<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;


class LibraryAttachmentTemplateWidget extends Widget
{
    public array $templates;
    
    public function run()
    {
        $template_groups = ArrayHelper::index($this->templates, null, 'group');
        
        foreach ($template_groups as $template_group) {
            $content = ArrayHelper::getValue($template_group, '0.template_information.content');
            
            foreach ($template_group as $key => $template) {
                $content = str_replace("{{$template->label}:{$template->input_type}}", nl2br($template->value), $content);
            }
            
            $result[] = $content;
        }
        
        return implode(null, $result ?? []);
    }
}
