<?php

namespace backend\modules\Integration\controllers;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Integration\forms\import\CourseForm;
use backend\modules\Integration\forms\import\LibraryTestCategoryForm;


class ImportController extends Controller
{
    public function actions()
    {
        return [
            'course' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => CourseForm::className(),
                'model_params' => ['id' => Yii::$app->request->get('id')],
                'render_view' => 'course',
                'run_method' => 'process',
                'success_message' => 'import_course_success_alert',
                'redirect_route' => Yii::$app->request->referrer,
            ],
            'library-test-category' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => LibraryTestCategoryForm::className(),
                'model_params' => ['id' => Yii::$app->request->get('id')],
                'render_view' => 'library_test_category',
                'run_method' => 'process',
                'success_message' => 'import_library_test_category_success_alert',
                'redirect_route' => Yii::$app->request->referrer,
            ],
        ];
    }
}
