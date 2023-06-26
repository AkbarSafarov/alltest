<?php

namespace backend\modules\Library\behaviors;

use Yii;
use yii\base\Behavior;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\Library\models\LibraryTemplate;
use backend\modules\Library\models\LibraryAttachmentTemplate;


class LibraryAttachmentTemplateBehavior extends Behavior
{
    public string $relation;
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }
    
    public function afterSave($event)
    {
        if ($this->owner->scenario == 'empty') {
            return false;
        }
        
        $relations = ArrayHelper::index($this->owner->{$this->relation}, 'id');
        $relations_tmp = (array)Yii::$app->request->post('LibraryAttachmentTemplate');
        
        if ($relations_tmp) {
            $counter = 0;
            
            foreach ($relations_tmp as $key => $value) {
                $relation_mdl = ArrayHelper::getValue($relations, $key) ?: new LibraryAttachmentTemplate();
                $relation_mdl->model_class = StringHelper::basename($this->owner->className());
                $relation_mdl->model_id = $this->owner->id;
                $relation_mdl->label = ArrayHelper::getValue($value, 'label');
                $relation_mdl->input_type = ArrayHelper::getValue($value, 'input_type');
                $relation_mdl->value = ArrayHelper::getValue($value, 'value');
                
                if ($relation_mdl->isNewRecord && $template = LibraryTemplate::findOne(ArrayHelper::getValue($value, 'template_id'))) {
                    $relation_mdl->template_information = $template->attributes;
                }
                
                $relation_mdl->group = ArrayHelper::getValue($value, 'group');
                $relation_mdl->sort = $counter;
                $relation_mdl->upload_name = "LibraryAttachmentTemplate[$key][value]";
                $relation_mdl->save();
                
                ArrayHelper::remove($relations, $key);
                $counter++;
            }
        }
        
        foreach ($relations as $value) { $value->delete(); };
    }
}
