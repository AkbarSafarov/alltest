<?php

namespace backend\modules\Library\behaviors;

use Yii;
use yii\base\Behavior;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\Library\models\LibraryAttachmentTestPack;


class LibraryAttachmentTestPackBehavior extends Behavior
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
        $relations_tmp = Yii::$app->request->post('LibraryAttachmentTestPack');
        
        if ($relations_tmp) {
            $counter = 0;
            
            foreach ($relations_tmp as $key => $value) {
                $relation_mdl = ArrayHelper::getValue($relations, $key) ?: new LibraryAttachmentTestPack();
                $relation_mdl->model_class = StringHelper::basename($this->owner->className());
                $relation_mdl->model_id = $this->owner->id;
                $relation_mdl->subject_id = ArrayHelper::getValue($value, 'subject_id');
                $relation_mdl->category_ids = ArrayHelper::getValue($value, 'category_ids');
                $relation_mdl->input_types = ArrayHelper::getValue($value, 'input_types');
                $relation_mdl->quantity = ArrayHelper::getValue($value, 'quantity');
                
                $relation_mdl->sort = $counter;
                $relation_mdl->save();
                
                ArrayHelper::remove($relations, $key);
                $counter++;
            }
        }
        
        foreach ($relations as $value) { $value->delete(); };
    }
}
