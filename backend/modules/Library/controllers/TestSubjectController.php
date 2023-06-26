<?php

namespace backend\modules\Library\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Library\models\LibraryTestSubject;


class TestSubjectController extends CrudController
{
    public function init()
    {
        $this->model = new LibraryTestSubject();
        return parent::init();
    }
    
    public function actions()
    {
        return ArrayHelper::filter(parent::actions(), ['index', 'create', 'update', 'delete']);
    }
}
