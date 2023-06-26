<?php

namespace backend\modules\Library\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

use backend\modules\Library\models\LibraryTest;
use backend\modules\Moderation\enums\ModerationEnums;
use backend\modules\Trash\widgets\TrashStateTabsWidget;


class TestController extends CrudController
{
    public function init()
    {
        $this->model = new LibraryTest();
        return parent::init();
    }
    
    public function actions()
    {
        $actions = ArrayHelper::filter(parent::actions(), ['update', 'delete']);
        
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => Actions\crud\DataProviderAction::className(),
                'render_params' => function() {
                    $this->model->setAttributes(['status' => ArrayHelper::getValue($_GET, 'LibraryTestSearch.status')], false);
                    
                    return [
                        'table_buttons' => [
                            'change_status' => Yii::$app->request->get('state') != 'archive' ? Yii::$app->services->moderationStatus->tableButtons($this->model) : [],
                        ],
                    ];
                },
            ],
            'create' => [
                'class' => Actions\crud\CreateAction::className(),
                'model' => new LibraryTest([
                    'subject_id' => Yii::$app->request->get('LibraryTest')['subject_id'] ?? null,
                    'category_id' => Yii::$app->request->get('LibraryTest')['category_id'] ?? null,
                    'input_type' => Yii::$app->request->get('LibraryTest')['input_type'] ?? null,
                ]),
            ],
        ]);
    }
    
    public function actionFileSort($id)
    {
        $model = $this->model->findOne($id);
        
        if (!$model || $model->input_type != 'sequence') {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $stack = Yii::$app->request->post('sort')['stack'] ?? [];
        $images = ArrayHelper::getColumn($stack, 'key');
        
        return $model->updateAttributes(['options' => ['answers' => array_values($images)]]);
    }
    
    public function actionFileDelete($id)
    {
        $model = $this->model->findOne($id);
        
        if (!$model || $model->input_type != 'sequence') {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $images = ArrayHelper::getValue($model, 'options.answers', []);
        $key = array_search(Yii::$app->request->post('key'), $images);
        
        if ($key !== false) {
            Yii::$app->services->file->delete($images[$key]);
            unset($images[$key]);
            
            return $model->updateAttributes(['options' => ['answers' => array_values($images)]]);
        }
    }
    
    public function actionOptions($value)
    {
        if (!isset($this->model->enums->inputTypes()[$value])) {
            return false;
        }
        
        return $this->renderAjax("update/options/$value", [
            'model' => $this->model,
            'form' => new ActiveForm(),
            'name_prefix' => 'LibraryTest[options]',
            'sequence_file_input_urls' => [
                'sort' => ['library/test/file-sort', 'id' => $this->model->id],
                'delete' => ['library/test/file-delete', 'id' => $this->model->id],
            ],
        ]);
    }
}
