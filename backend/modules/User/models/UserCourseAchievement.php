<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\User;
use backend\modules\Reward\models\RewardAchievement;


class UserCourseAchievement extends ActiveRecord
{
    public $total_count;
    
    public static function tableName()
    {
        return '{{%user_course_achievement}}';
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getUserCourse()
    {
        return $this->hasOne(UserCourse::className(), ['id' => 'user_course_id']);
    }
    
    public function getAchievement()
    {
        return $this->hasOne(RewardAchievement::className(), ['id' => 'achievement_id']);
    }
    
    public function beforeSave($insert)
    {
        $this->user_id = $this->user_id ?: $this->userCourse->user_id;
        return parent::beforeSave($insert);
    }
}
