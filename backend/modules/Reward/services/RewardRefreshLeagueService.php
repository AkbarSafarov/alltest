<?php

namespace backend\modules\Reward\services;

use Yii;
use yii\helpers\ArrayHelper;

use backend\modules\Reward\models\RewardLeague;
use backend\modules\Reward\enums\RewardLeagueEnums;
use backend\modules\User\models\UserCourseLeague;


class RewardRefreshLeagueService
{
    private $types;
    private $levels;
    
    public function __construct()
    {
        $this->leagues = ArrayHelper::map(RewardLeague::find()->asArray()->all(), 'key', 'id');
        $this->types = RewardLeagueEnums::types();
        $this->levels = RewardLeagueEnums::levels();
    }
    
    public function process()
    {
        $best_course_groups = UserCourseLeague::find()
            ->joinWith(['course' => fn($query) => $query->active()], false)
            ->select([
                'user_course_league.course_id',
                'user_course_league.key',
                'MAX(user_course_league.value) as max_value',
            ])
            ->groupBy(['course_id', 'key'])
            ->asArray()->all();
        
        $best_course_groups = ArrayHelper::map($best_course_groups, 'course_id', 'max_value', 'key');
        
        foreach ($best_course_groups as $type => $best_course_group) {
            $records = [];
            
            foreach ($this->levels as $level => $from_to_value) {
                $league_id = $this->leagues["{$type}_{$level}"];
                
                foreach ($best_course_group as $course_id => $max_value) {
                    $from_value = round($max_value * $from_to_value[0] / 100);
                    $to_value = round($max_value * $from_to_value[1] / 100);
                    
                    $records[] = "WHEN course_id = '$course_id' AND value BETWEEN '$from_value' AND '$to_value' THEN $league_id";
                }
            }
            
            $records = implode(' ', $records);
            
            Yii::$app->db->createCommand(
                "UPDATE user_course_league SET league_id = CASE $records ELSE null END WHERE `key` = '$type'"
            )->execute();
            
            //        Setting best leagues
            
            $league_id = $this->leagues["{$type}_best"];
            UserCourseLeague::updateAll(['league_id' => $league_id], ['is_best' => 1, 'key' => $type]);
        }
    }
}
