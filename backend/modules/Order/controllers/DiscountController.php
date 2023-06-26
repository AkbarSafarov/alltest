<?php

namespace backend\modules\Order\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Order\models\OrderDiscount;


class DiscountController extends CrudController
{
    public function init()
    {
        $this->model = new OrderDiscount();
        return parent::init();
    }
    
    public function actions()
    {
        $actions = ArrayHelper::filter(parent::actions(), ['create', 'update', 'delete']);
        
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => Actions\crud\DataProviderAction::className(),
                'trash_state_tabs' => ['active', 'outdated', 'archive'],
            ],
        ]);
    }
    
    public function findModel($id)
    {
        return $this->model->find()->with(['courses', 'packages'])->andWhere(['id' => $id])->one();
    }
}
