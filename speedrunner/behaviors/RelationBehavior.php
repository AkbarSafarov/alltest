<?php

namespace speedrunner\behaviors;

use Yii;
use yii\base\Behavior;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class RelationBehavior extends Behavior
{
    public $type;
    
    public $attributes;
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }
    
    public function afterSave($event)
    {
        return call_user_func([$this, $this->type]);
    }
    
    public function oneOne()
    {
        foreach ($this->attributes as $a) {
            $relation_mdl = $this->owner->{$a['relation']} ? clone($this->owner->{$a['relation']}) : $a['model'];
            $relation_mdl->{$a['attributes']['main']} = $this->owner->id;
            
            foreach ($a['attributes']['relational'] as $a_r) {
                if ($this->owner->isAttributeActive($a_r)) {
                    $relation_mdl->{$a_r} = $this->owner->{$a_r};
                }
            }
            
            $relation_mdl->save();
        }
    }
    
    public function oneMany()
    {
        foreach ($this->attributes as $a_key => $a) {
            if (!$this->owner->isAttributeActive($a_key)) {
                continue;
            }
            
            $relations = ArrayHelper::index($this->owner->{$a['relation']}, 'id');
            $attribute = $this->owner->{$a_key};
            
            if ($attribute) {
                $counter = 0;
                
                foreach ($attribute as $key => $value) {
                    $relation_mdl = clone(ArrayHelper::getValue($relations, $key, $a['model']));
                    $relation_mdl->{$a['attributes']['main']} = $this->owner->id;
                    
                    foreach ($a['attributes']['relational'] as $p) {
                        isset($value[$p]) ? $relation_mdl->{$p} = $value[$p] : null;
                    }
                    
                    $relation_mdl->sort = $counter;
                    $relation_mdl->save();
                    
                    ArrayHelper::remove($relations, $key);
                    $counter++;
                }
            }
            
            $a['model']->deleteAll(['id' => ArrayHelper::getColumn($relations, 'id')]);
//            foreach ($relations as $value) { $value->delete(); };
        }
    }
    
    public function manyMany()
    {
        foreach ($this->attributes as $a_key => $a) {
            if (!$this->owner->isAttributeActive($a_key)) {
                continue;
            }
            
            $extra_attributes = ArrayHelper::getValue($this, "attributes.$a_key.attributes.extra", []);
            
            $relations = ArrayHelper::map($this->owner->{$a['relation']}, 'id', 'id');
            
            $attribute = $this->owner->{$a_key};
            
            if ($attribute) {
                foreach ($attribute as $value) {
                    if (!in_array($value, $relations)) {
                        $relation_mdl = clone($a['model']);
                        $relation_mdl->{$a['attributes']['main']} = $this->owner->id;
                        $relation_mdl->{$a['attributes']['relational']} = $value;
                        
                        foreach ($extra_attributes as $e_a_key => $e_a_value) {
                            $relation_mdl->{$e_a_key} = $e_a_value;
                        }
                        
                        $relation_mdl->save();
                    }
                    
                    ArrayHelper::remove($relations, $value);
                }
            }
            
            $delete_all_filter = [$a['attributes']['main'] => $this->owner->id, $a['attributes']['relational'] => $relations];
            $delete_all_filter = ArrayHelper::merge($delete_all_filter, $extra_attributes);
            
            $a['model']->deleteAll($delete_all_filter);
        }
    }
}
