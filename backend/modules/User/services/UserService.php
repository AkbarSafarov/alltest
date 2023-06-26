<?php

namespace backend\modules\User\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;

use backend\modules\User\models\UserCourse;
use backend\modules\User\models\UserCourseAchievement;
use backend\modules\User\models\UserCourseLeague;
use backend\modules\Order\models\Order;


class UserService extends ActiveService
{
    public function checkByRole(array $roles)
    {
        return in_array($this->model->role, $roles);
    }
    
    public function profileInformation()
    {
        $rewards['achievements'] = UserCourseAchievement::find()
            ->joinWith(['achievement'])
            ->andWhere([
                'user_course_achievement.user_id' => Yii::$app->user->id,
            ])
            ->select([
                'user_course_achievement.achievement_id',
                'COUNT(*) as total_count',
            ])
            ->groupBy('achievement_id')
            ->orderBy('reward_achievement.sort')
            ->all();
        
        $rewards['leagues'] = UserCourseLeague::find()
            ->joinWith(['league'])
            ->andWhere([
                'user_course_league.user_id' => Yii::$app->user->id,
            ])
            ->select([
                'user_course_league.league_id',
                'COUNT(*) as total_count',
            ])
            ->groupBy('league_id')
            ->orderBy('reward_league.sort')
            ->all();
        
        return [
            'rewards' => $rewards,
            'certificates' => UserCourse::find()->andWhere('certificate_file IS NOT null')->orderBy('created_at DESC')->select('certificate_file')->column(),
            'last_orders' => Order::find()->with(['products'])->andWhere(['student_id' => $this->model->id])->orderBy('id DESC')->limit(5)->all(),
        ];
    }
}