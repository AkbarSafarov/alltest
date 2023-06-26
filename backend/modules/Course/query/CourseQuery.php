<?php

namespace backend\modules\Course\query;

use Yii;
use yii\helpers\StringHelper;
use yii\db\Expression;
use speedrunner\db\ActiveQuery;


class CourseQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere([
            'and',
            ['<=', 'course.date_from', date('Y-m-d')],
            ['>=', 'course.date_to', date('Y-m-d')],
        ]);
    }
    
    public function byTeacher($teacher_id = null)
    {
        $teacher_id = $teacher_id ?? Yii::$app->user->id;
        return $this->joinWith(['teachers'], false)->andWhere(['user.id' => $teacher_id]);
    }
    
    public function itemsList($attr, $type, $show_deleted = false, $q = null, $limit = 20)
    {
        if ($show_deleted) {
            $this->where(true);
        }
        
        $this->select(['course.id', "course.$attr as text"])->andFilterWhere(['like', "course.$attr", $q]);
        
        switch ($type) {
            case 'active':
                $this->andFilterWhere([
                    'and',
                    ['<=', 'course.date_from', date('Y-m-d')],
                    ['>=', 'course.date_to', date('Y-m-d')],
                ]);
                break;
        }
        
        return $this->limit($limit);
    }
}