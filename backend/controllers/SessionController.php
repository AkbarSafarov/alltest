<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;


class SessionController extends Controller
{
    public function actionSet()
    {
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        
        if (!isset($name)) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Параметр не найден'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        switch ($name) {
            case 'nav':
                return Yii::$app->session->set($name, !(bool)Yii::$app->session->get($name));
            default:
                Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Параметр не найден'));
                return $this->redirect(Yii::$app->request->referrer);
        }
    }
}
