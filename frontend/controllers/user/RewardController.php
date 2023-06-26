<?php

namespace frontend\controllers\user;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use backend\modules\Reward\models\RewardAchievement;
use backend\modules\Reward\models\RewardLeague;


class RewardController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionView($id, $type)
    {
        switch ($type) {
            case 'achievement':
                $model = RewardAchievement::findOne($id);
                break;
            case 'league':
                $model = RewardLeague::findOne($id);
                break;
            default:
                $model = null;
        }
        
        if (!$model) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->renderPartial('view', [
            'model' => $model,
        ]);
    }
}
