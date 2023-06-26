<?php

namespace backend\modules\Integration\controllers;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Integration\forms\check\CourseCertificateFileForm;


class CheckController extends Controller
{
    public function actions()
    {
        return [
            'course-certificate-file' => [
                'class' => Actions\web\FormAction::className(),
                'model' => new CourseCertificateFileForm(),
                'render_view' => 'course_certificate_file',
                'run_method' => 'process',
                'success_message' => 'test_course_certificate_file_success_alert',
                'redirect_route' => Yii::$app->request->referrer,
            ],
        ];
    }
}
