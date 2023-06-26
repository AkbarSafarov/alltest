<?php

namespace backend\modules\Integration\controllers;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Integration\services\export\CourseService;
use backend\modules\Integration\services\export\LibraryTestCategoryService;
use backend\modules\Integration\services\export\UserXlsService;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;
use backend\modules\Library\models\LibraryTestCategory;
use backend\modules\Library\models\LibraryTest;


class ExportController extends Controller
{
    public function actionCourse($id)
    {
        if (!($course = Course::find()->where(['id' => $id])->one())) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Курс не найден'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $root_unit = CourseUnit::find()->andWhere(['tree' => $course->id, 'depth' => 0])->one();
        $root_unit->beforeSearch();
        
        $service = new CourseService($course, Yii::$app->services->array->toObjects($root_unit->tree([], ['attachmentTests'])));
        
        if ($service->process()) {
            Yii::$app->session->addFlash('success', Yii::t('app', 'В ближайшее время Вы получите уведомление со ссылкой на архив'));
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionLibraryTestCategory($id)
    {
        if (!($category = LibraryTestCategory::find()->where(['id' => $id])->one())) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Категория тестов не найдена'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $service = new LibraryTestCategoryService($category, LibraryTest::find()->andWhere(['category_id' => $category->id]));
        
        if ($service->process()) {
            Yii::$app->session->addFlash('success', Yii::t('app', 'В ближайшее время Вы получите уведомление со ссылкой на архив'));
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionUsersXls()
    {
        $service = new UserXlsService(Yii::$app->request->get('user_search_params', []));
        
        if (!$service->process()) return $this->redirect(Yii::$app->request->referrer);
    }
}
