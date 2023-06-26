<?php

namespace backend\modules\Course\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\CourseUnitType;


class UnitTypeController extends CrudController
{
    public function init()
    {
        $this->model = new CourseUnitType();
        return parent::init();
    }
    
    public function actions()
    {
        return ArrayHelper::filter(parent::actions(), ['index', 'create', 'update', 'delete']);
    }
}
