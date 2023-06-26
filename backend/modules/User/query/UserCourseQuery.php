<?php

namespace backend\modules\User\query;

use Yii;
use yii\helpers\StringHelper;
use yii\db\Expression;
use speedrunner\db\ActiveQuery;


class UserCourseQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere([
            'and',
            ['<=', 'user_course.date_from', date('Y-m-d')],
            ['>=', 'user_course.date_to', date('Y-m-d')],
            [
                'or',
                ['>', 'user_course.demo_datetime_to', date('Y-m-d H:i:s')],
                ['IS', 'user_course.demo_datetime_to', null],
            ],
        ]);
    }
}