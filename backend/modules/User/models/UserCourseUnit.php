<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;
use backend\modules\Course\models\CourseUnitType;
use backend\modules\System\models\SystemLanguage;


class UserCourseUnit extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_course_unit}}';
    }
    
    public function getUnit()
    {
        return $this->hasOne(CourseUnit::className(), ['id' => 'unit_id']);
    }
    
    public function getType()
    {
        return $this->hasOne(CourseUnitType::className(), ['id' => 'type_id']);
    }
    
    public function getCourse()
    {
        return $this->hasOne(UserCourse::className(), ['id' => 'tree']);
    }
    
    public static function find()
    {
        return (new \speedrunner\db\NestedSetsQuery(get_called_class()));
    }
}
