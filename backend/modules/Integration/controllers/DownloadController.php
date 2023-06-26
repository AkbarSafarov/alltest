<?php

namespace backend\modules\Integration\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;


class DownloadController extends Controller
{
    public function actionFile($url)
    {
        if (!is_file($url)) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Архив отсутствует'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename=Archive.tar.gz");
        readfile($url);
        
        unlink($url);
        
        die;
    }
}
