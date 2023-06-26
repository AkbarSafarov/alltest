<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;


class CourseTeacherRef extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%course_teacher_ref}}';
    }
}
