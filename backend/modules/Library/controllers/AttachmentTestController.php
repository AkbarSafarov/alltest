<?php

namespace backend\modules\Library\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use backend\modules\Library\models\LibraryAttachmentTest;


class AttachmentTestController extends Controller
{
    public function actionAdd($input_type)
    {
        $model = new LibraryAttachmentTest(['id' => uniqid()]);
        $input_types = $model->enums->inputTypes();
        
        if (!in_array($input_type, array_keys($input_types))) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $model->input_type = $input_type;
        
        return Html::tag('tr', $this->renderAjax('@backend/modules/Library/widgets/views/library_attachment_test/test', [
            'model' => $model,
            'form' => new ActiveForm(),
            'input_types' => $input_types,
        ]));
    }
    
    public function actionFileSort($id)
    {
        $model = LibraryAttachmentTest::findOne($id);
        
        if (!$model || $model->input_type != 'sequence') {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $stack = Yii::$app->request->post('sort')['stack'] ?? [];
        $images = ArrayHelper::getColumn($stack, 'key');
        
        return $model->updateAttributes(['options' => ['answers' => array_values($images)]]);
    }
    
    public function actionFileDelete($id)
    {
        $model = LibraryAttachmentTest::findOne($id);
        
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
}
