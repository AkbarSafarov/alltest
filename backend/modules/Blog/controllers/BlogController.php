<?php

namespace backend\modules\Blog\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Blog\models\Blog;


class BlogController extends CrudController
{
    public function init()
    {
        $this->model = new Blog();
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
}
