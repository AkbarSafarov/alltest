<?php

namespace backend\modules\Reward\services;

use Yii;
use yii\helpers\ArrayHelper;

use backend\modules\Reward\models\RewardAchievement;
use backend\modules\Reward\enums\RewardAchievementEnums;
use backend\modules\User\models\UserCourseUnit;
use backend\modules\User\models\UserCourseAchievement;
use backend\modules\User\models\UserCourseLeague;


class RewardCheckService
{
    private $achievements;
    private $achievement_types;
    
    private $course;
    private $course_achievements;
    private $course_leagues;
    
    public function __construct($course)
    {
        $this->achievements = ArrayHelper::map(RewardAchievement::find()->asArray()->all(), 'key', 'id');
        $this->achievement_types = RewardAchievementEnums::types();
        
        $this->course = $course;
        $this->course_achievements = UserCourseAchievement::find()->andWhere(['user_course_id' => $course->id])->select('achievement_id')->column();
        $this->course_leagues = UserCourseLeague::find()->andWhere(['user_course_id' => $course->id])->indexBy('key')->all();
    }
    
    public function all()
    {
        $this->{$this->course->course_json['type']}();
    }
    
    private function linear()
    {
        foreach ($this->achievement_types['gradation']['linear'] as $type => $levele) {
            switch ($type) {
                case 'explorer':
                    $units = UserCourseUnit::find()
                        ->andWhere([
                            'tree' => $this->course->id,
                            'depth' => 3,
                        ])
                        ->select([
                            'SUM(CASE WHEN is_unlocked = 1 THEN 1 ELSE 0 END) as unlocked_count',
                            'COUNT(*) as total_count',
                        ])
                        ->asObject()->one();
                    
                    $current_value = round($units->unlocked_count / ($units->total_count ?: 1) * 100);
                    break;
                case 'nerd':
                    $current_value = $this->course->performance;
                    break;
            }
            
            $this->createAchievement($type, $levele, $current_value);
            $this->updateLeagueValue($type, $current_value);
        }
    }
    
    private function exam()
    {
        foreach ($this->achievement_types['gradation']['exam'] as $type => $levele) {
            switch ($type) {
                case 'persistent':
                    $units = UserCourseUnit::find()
                        ->andWhere([
                            'tree' => $this->course->id,
                            'depth' => 3,
                        ])
                        ->select([
                            'SUM(JSON_LENGTH(performance)) as performance',
                        ])
                        ->asObject()->one();
                    
                    $current_value = $units->performance;
                    break;
                case 'intellectual':
                    $current_value = $this->course->performance;
                    break;
            }
            
            $this->createAchievement($type, $levele, $current_value);
            $this->updateLeagueValue($type, $current_value);
        }
    }
    
    private function createAchievement($type, $levele, $current_value)
    {
        $new_level = null;
        $achievement_id = null;
        
        foreach ($levele as $level => $required_value) {
            if ($current_value < $required_value) break;
            
            $new_level = $level;
            $achievement_id = $this->achievements["{$type}_{$level}"];
        }
        
        if ($achievement_id && !in_array($achievement_id, $this->course_achievements)) {
            $achievement = new UserCourseAchievement();
            $achievement->user_course_id = $this->course->id;
            $achievement->achievement_id = $achievement_id;
            $achievement->save();
            
            Yii::$app->services->notification->create(
                [$achievement->user_id],
                'user_course_achievement_create', $achievement->id,
                [
                    'course' => $this->course->course_json['name'],
                    'achievement' => $achievement->achievement->name,
                ]
            );
        }
        
        //        Deleting lower achievements
        
        if ($new_level) {
            unset($levele[$new_level]);
            
            $old_achievements = array_intersect(
                array_flip($this->achievements),
                array_map(fn($value) => "{$type}_{$value}", array_flip($levele))
            );
            
            UserCourseAchievement::deleteAll(['user_course_id' => $this->course->id, 'achievement_id' => array_keys($old_achievements)]);
        }
    }
    
    private function updateLeagueValue($key, $current_value)
    {
        $league = $this->course_leagues[$key] ?? new UserCourseLeague(['value' => 0]);
        $league->course_id = $this->course->course_id;
        $league->user_course_id = $this->course->id;
        $league->key = $key;
        $league->value = $current_value;
        
        $max_value = UserCourseLeague::find()->andWhere(['course_id' => $this->course->course_id, 'key' => $key])->max('value') ?: 0;
        
        if ($current_value > $max_value) {
            UserCourseLeague::updateAll(['is_best' => 0], ['course_id' => $this->course->course_id, 'key' => $key]);
            $league->is_best = 1;
        }
        
        $league->save();
    }
}
