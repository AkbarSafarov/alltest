<?php

namespace backend\modules\Library\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Library\models\LibraryTemplate;
use backend\modules\Library\models\LibraryTemplateCategory;
use backend\modules\System\models\SystemLanguage;


class TemplateController extends CrudController
{
    public function init()
    {
        $this->model = new LibraryTemplate();
        return parent::init();
    }
    
    public function actions()
    {
        $actions = ArrayHelper::filter(parent::actions(), ['delete']);
        
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => Actions\crud\DataProviderAction::className(),
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
}
