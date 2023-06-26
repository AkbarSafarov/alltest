<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;


class CoursePackageRef extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%course_package_ref}}';
    }
}
