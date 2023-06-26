<?php

namespace backend\modules\Reward\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Reward\models\RewardLeague;


class LeagueController extends CrudController
{
    public function init()
    {
        $this->model = new RewardLeague();
        return parent::init();
    }
    
    public function actions()
    {
        $actions = ArrayHelper::filter(parent::actions(), ['index', 'update']);
        
        return ArrayHelper::merge($actions, [
            'sort' => [
                'class' => Actions\web\SortAction::className(),
                'model_class' => $this->model->className(),
            ],
        ]);
    }
}
