<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\User;
use backend\modules\Course\models\Course;
use backend\modules\Reward\models\RewardLeague;


class UserCourseLeague extends ActiveRecord
{
    public $total_count;
    
    public static function tableName()
    {
        return '{{%user_course_league}}';
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
    
    public function getUserCourse()
    {
        return $this->hasOne(UserCourse::className(), ['id' => 'user_course_id']);
    }
    
    public function getLeague()
    {
        return $this->hasOne(RewardLeague::className(), ['id' => 'league_id']);
    }
    
    public function beforeSave($insert)
    {
        $this->user_id = $this->user_id ?: $this->userCourse->user_id;
        return parent::beforeSave($insert);
    }
}
