<?php

namespace backend\modules\Course\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\CoursePackage;
use backend\modules\System\models\SystemLanguage;


class PackageController extends CrudController
{
    public function init()
    {
        $this->model = new CoursePackage();
        return parent::init();
    }
    
    public function actions()
    {
        $actions = ArrayHelper::filter(parent::actions(), ['delete']);
        
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => Actions\crud\DataProviderAction::className(),
                'trash_state_tabs' => ['active', 'outdated', 'archive'],
                'render_params' => fn () => [
                    'languages' => SystemLanguage::find()->asArray()->all(),
                ],
            ],
            'create' => [
                'class' => Actions\crud\CreateAction::className(),
                'render_params' => fn () => [
                    'languages' => SystemLanguage::find()->asArray()->all(),
                ],
            ],
            'update' => [
                'class' => Actions\crud\UpdateAction::className(),
                'render_params' => fn () => [
                    'languages' => SystemLanguage::find()->asArray()->all(),
                ],
            ],
        ]);
    }
    
    public function findModel($id)
    {
        return $this->model->find()->with(['courses'])->andWhere(['id' => $id])->one();
    }
}
