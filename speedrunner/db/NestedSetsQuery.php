<?php

namespace speedrunner\db;

use Yii;
use yii\db\Expression;
use speedrunner\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;


class NestedSetsQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->byState(false);
    }
    
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
    
    public function checkParentIsDeleted($model)
    {
        return $this->byState(true)
            ->andWhere([
                'and',
                ['tree' => $model->tree],
                ['<', 'lft', $model->lft],
                ['>', 'rgt', $model->rgt],
            ]);
    }
    
    public function withoutRoots()
    {
        return $this->andWhere(['>', "$this->table_name.depth", 0]);
    }
    
    public function itemsTree($attr, $type)
    {
        switch ($type) {
            case 'self':
                $this->addSelect(['id', new Expression("CONCAT(REPEAT(('- '), (depth - 1)), $this->table_name.name) as text")]);
                break;
            case 'translation':
                $this->addSelect(['id', new Expression("CONCAT(REPEAT(('- '), (depth - 1)), $this->table_name.name->>'$.$this->lang') as text")]);
                break;
        }
        
        return $this->orderBy('lft ASC');
    }
}