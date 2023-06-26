<?php

namespace backend\modules\Library\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\Library\models\LibraryTestCategory;


class TestCategoryController extends CrudController
{
    public function init()
    {
        $this->model = new LibraryTestCategory();
        return parent::init();
    }
    
    public function actions()
    {
        return ArrayHelper::filter(parent::actions(), ['index', 'create', 'update', 'delete']);
    }
    
    public function actionFilteredBySubject($value, array $category_ids = [])
    {
        $result = $this->model->find()->andWhere(['subject_id' => $value])->asArray()->all();
        $result = ArrayHelper::map($result, 'id', 'name');
        $options = ['prompt' => ''];
        
        return Html::renderSelectOptions($category_ids, $result, $options);
    }
}
