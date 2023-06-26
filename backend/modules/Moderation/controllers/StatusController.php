<?php

namespace backend\modules\Moderation\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use backend\modules\Moderation\models\ModerationStatus;


class StatusController extends Controller
{
    public function actionCreate($model_class, $new_value, $redirect = true)
    {
        $model_ids = (array)Yii::$app->request->post('selection', []);
        
        foreach ($model_ids as $model_id) {
            $model = new ModerationStatus();
            $model->model_class = $model_class;
            $model->model_id = $model_id;
            $model->new_value = $new_value;
            
            if ($model->save()) {
                $redirect ? Yii::$app->session->setFlash('success', [Yii::t('app_notification', 'Статус был изменён')]) : null;
            } else {
                $redirect ? Yii::$app->session->setFlash('danger', [$model->getErrorSummary(true)]) : null;
            }
        }
        
        return $redirect ? $this->redirect(Yii::$app->request->referrer) : true;
    }
}
