<?php

namespace backend\modules\Course\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use linslin\yii2\curl\Curl;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;


class PreviewController extends Controller
{
    public function actionCourse($id)
    {
        return ($model = Course::findOne($id)) ? $this->curl($model, ['preview/course']) : $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionUnit($id)
    {
        return ($model = CourseUnit::findOne($id)) ? $this->curl($model, ['preview/unit']) : $this->redirect(Yii::$app->request->referrer);
    }
    
    private function curl($model, $route)
    {
        $curl = new Curl();
        $curl->setOptions([
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        return $curl->setPostParams([
           'key' => base64_encode(Yii::$app->user->identity->auth_key . "___$model->id"),
        ])->post(Yii::$app->urlManagerFrontend->createAbsoluteUrl($route, 'https'));
    }
}
