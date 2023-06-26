<?php

namespace speedrunner\db;

use Yii;
use yii\helpers\StringHelper;
use yii\db\Expression;


class ActiveQuery extends \yii\db\ActiveQuery
{
    use ActiveQueryTrait;
    
    public $table_name;
    public $lang;
    
    public function init()
    {
        $this->table_name = $this->modelClass::tableName();
        $this->lang = Yii::$app->language;
        
        return parent::init();
    }
    
    public function populate($rows)
    {
        $result = parent::populate($rows);
        
        if ($this->asObject) {
            $result = Yii::$app->services->array->toObjects($result);
        }
        
        return $result;
    }
    
    public function byState($is_deleted)
    {
        $model_class = StringHelper::basename($this->modelClass);
        $condition = $is_deleted ? 'IN' : 'NOT IN';
        
        return $this->where("$this->table_name.id $condition (SELECT trash_model_id FROM trash WHERE trash_model_class = '$model_class')");
        
        //        Alternate query
        
//        return $this->where([
//            $is_deleted ? 'EXISTS' : 'NOT EXISTS',
//            (new \yii\db\Query())
//                ->from('trash')
//                ->where("trash_model_id = $this->table_name.id AND trash_model_class = '$model_class'")
//        ]);
    }
    
    public function byUser($attribute, $user_id = null)
    {
        return $this->andWhere(["$this->table_name.$attribute" => $user_id ?? Yii::$app->user->id]);
    }
    
    public function bySlug($slug)
    {
        return $this->andWhere(["$this->table_name.slug" => $slug]);
    }
    
    public function setTranslationAttributes(array $attributes)
    {
        foreach ($attributes as $a) {
            $this->addSelect([new Expression("$this->table_name.$a->>'$.$this->lang' as $a")]);
        }
        
        return $this;
    }
    
    public function itemsList($attribute, $type, $show_deleted = false, $q = null, $limit = 20)
    {
        $this->table_name = $this->modelClass::tableName();
        
        if ($show_deleted) {
            $this->where(true);
        }
        
        switch ($type) {
            case 'self':
                $this->select([
                    "$this->table_name.id",
                    "$this->table_name.$attribute as text",
                ])->andFilterWhere([
                    'like', "$this->table_name.$attribute", $q
                ]);
                
                break;
            case 'translation':
                $this->select([
                    "$this->table_name.id",
                    new Expression("$this->table_name.$attribute->>'$.$this->lang' as text"),
                ])->andFilterWhere([
                    'like', new Expression("LOWER(JSON_EXTRACT($this->table_name.$attribute, '$.$this->lang'))"), mb_strtolower($q)
                ]);
                
                break;
            default:
                $this->andWhere('false');
        }
        
        return $this->limit($limit);
    }
}