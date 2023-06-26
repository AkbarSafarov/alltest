<?php

namespace backend\modules\Library\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use backend\modules\Library\models\LibraryAttachmentTemplate;
use backend\modules\Library\models\LibraryTemplate;


class AttachmentTemplateController extends Controller
{
    public function actionAdd($id)
    {
        if (!($template = LibraryTemplate::findOne($id))) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return Html::tag('tr', $this->renderAjax('add', [
            'template' => $template,
        ]));
    }
    
    public function actionSearch($language_id = null, $value)
    {
        $templates = LibraryTemplate::find()->andWhere(['like', 'name', $value])->orderBy('name');
        
        if ($language_id) {
            $templates->andWhere(['language_id' => $language_id]);
        } else {
            $templates->andWhere(['is', 'language_id', new Expression('NULL')]);
        }
        
        return $this->renderPartial('@backend/modules/Library/widgets/views/library_attachment_template/templates', [
            'templates' => $templates->asObject()->all(),
        ]);
    }
    
    public function actionFileDelete($id)
    {
        $model = LibraryAttachmentTemplate::findOne($id);
        
        if (!$model || $model->input_type != 'file_input') {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        Yii::$app->services->file->delete($model->value);
        return $model->updateAttributes(['value' => null]);
    }
}
