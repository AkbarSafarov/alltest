<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use backend\modules\Reward\services\RewardRefreshLeagueService;


class CronController extends Controller
{
    public function beforeAction($action)
    {
        return Yii::$app->request->get('key') == 'mMz3tLVzL5FyHOkfi6IZ' ? parent::beforeAction($action) : false;
    }
    
    public function actionRefreshLeagues()
    {
        (new RewardRefreshLeagueService())->process();
        file_put_contents(Yii::getAlias('@root/temp/cron/' . $this->action->id . '.txt'), date('Y-m-d H:i:s'));
    }
    
    public function actionOne()
    {
        $users = \backend\modules\User\models\User::find()->column();
        
        foreach ($users as $user_id) {
            (new \backend\modules\User\models\UserCart(['user_id' => $user_id]))->save();
        }
    }
}
