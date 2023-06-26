<?php

namespace backend\modules\Course\query;

use Yii;
use yii\helpers\StringHelper;
use yii\db\Expression;
use speedrunner\db\ActiveQuery;


class CoursePackageQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere([
            'and',
            ['<=', 'course_package.date_from', date('Y-m-d')],
            ['>=', 'course_package.date_to', date('Y-m-d')],
        ]);
    }
    
    public function itemsList($attr, $type, $show_deleted = false, $q = null, $limit = 20)
    {
        if ($show_deleted) {
            $this->where(true);
        }
        
        $this->select(['course_package.id', "course_package.$attr as text"])->andFilterWhere(['like', "course_package.$attr", $q]);
        
        switch ($type) {
            case 'active':
                $this->andFilterWhere([
                    'and',
                    ['<=', 'course_package.date_from', date('Y-m-d')],
                    ['>=', 'course_package.date_to', date('Y-m-d')],
                ]);
                break;
        }
        
        return $this->limit($limit);
    }
}