<?php

namespace backend\modules\Library\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use backend\modules\Library\models\LibraryTemplate;


class LibraryAttachmentTemplateWidget extends Widget
{
    public Model $model;
    
    public function run()
    {
        $lang = Yii::$app->language;
        $all_label = Yii::t('app', 'Общие');
        
        $template_sections = LibraryTemplate::find()
            ->joinWith(['category', 'language'], false)
            ->select([
                'library_template.*',
                "IFNULL(system_language.name, '$all_label') as language_name",
                new Expression("library_template_category.name->>'$.$lang' as category_name"),
            ])
            ->orderBy('system_language.id')
            ->asObject()->all();
        
        $template_sections = array_map(function ($value) {
            return [
                'language' => ArrayHelper::getValue($value, '0.language_name'),
                'template_groups' => array_map(function ($val) {
                    return [
                        'category' => ArrayHelper::getValue($val, '0.category_name'),
                        'templates' => $val,
                    ];
                }, ArrayHelper::index($value, null, 'category_id')),
            ];
        }, ArrayHelper::index($template_sections, null, 'language_id'));
        
        return $this->render('library_attachment_template', [
            'model' => $this->model,
            'template_sections' => $template_sections,
            'template_groups' => ArrayHelper::index($this->model->attachmentTemplates, null, 'group'),
        ]);
    }
}
