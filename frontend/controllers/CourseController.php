<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;
use backend\modules\Course\models\CourseUnit;
use backend\modules\Course\enums\CourseEnums;


class CourseController extends Controller
{
    public function actionCourses($offset = 0, $type = null)
    {
        $page = Yii::$app->services->staticpage->courses;
        $course_types = ['linear', 'exam'];
        
        foreach ($course_types as $course_type) {
            $query = Course::find()
                ->active()
                ->with(['activeDiscount'])
                ->andWhere([
                    'course.type' => $course_type,
                ])
                ->andFilterWhere([
                    'course.type' => $type,
                    'course.language_id' => Yii::$app->request->get('language'),
                    'course.author' => Yii::$app->request->get('author'),
                ])
                ->orderBy('date_to')
                ->offset($offset);
            
            $course_groups[$course_type] = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 15,
                    'page' => $offset,
                ],
            ]);
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/particles/courses', [
                'models' => isset($course_groups[$type]) ? $course_groups[$type]->getModels() : [],
            ]);
        } else {
            return $this->render('course/index', [
                'page' => $page['page'],
                'course_groups' => $course_groups,
                'types' => CourseEnums::types(),
                'languages' => ArrayHelper::map(Yii::$app->urlManager->languages, 'id', 'name'),
                'authors' => ArrayHelper::map(Course::find()->select(['DISTINCT(author) as author'])->asArray()->all(), 'author', 'author'),
            ]);
        }
    }
    
    public function actionCoursesSearch($offset = 0, $search = null)
    {
        $page = Yii::$app->services->staticpage->courses;
        
        $query = Course::find()
            ->active()
            ->with(['activeDiscount'])
            ->andFilterWhere([
                'or',
                ['like', 'name', $search],
                ['like', 'short_description', $search],
                ['like', 'active_structure', $search],
            ])
            ->orderBy('course.date_to')
            ->offset($offset);
        
        $courses = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $offset,
            ],
        ]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/particles/courses', [
                'models' => $courses->getModels(),
            ]);
        } else {
            return $this->render('course/search', [
                'page' => $page['page'],
                'courses' => $courses,
            ]);
        }
    }
    
    public function actionCourseView($slug)
    {
        if (!($model = Course::find()->active()->addSelect(['active_structure'])->bySlug($slug)->one())) {
            return $this->redirect(['courses']);
        }
        
        return $this->render('course/view', [
            'model' => $model,
            'sections' => Yii::$app->services->array->toObjects($model->active_structure),
        ]);
    }
    
    public function actionPackages($offset = 0)
    {
        $page = Yii::$app->services->staticpage->course_packages;
        
        $query = CoursePackage::find()
            ->active()
            ->with(['activeDiscount'])
            ->andFilterWhere([
                'language_id' => Yii::$app->request->get('language'),
            ])
            ->orderBy('date_to')
            ->offset($offset);
        
        $packages = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $offset,
            ],
        ]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/particles/packages', [
                'models' => $packages->getModels(),
            ]);
        } else {
            return $this->render('package/index', [
                'page' => $page['page'],
                'packages' => $packages,
                'languages' => ArrayHelper::map(Yii::$app->urlManager->languages, 'id', 'name'),
            ]);
        }
    }
    
    public function actionPackageView($id)
    {
        if (!($model = CoursePackage::find()->active()->with(['activeCourses.activeDiscount'])->andWhere(['id' => $id])->one())) {
            return $this->redirect(['packages']);
        }
        
        return $this->renderAjax('package/view', [
            'model' => $model,
        ]);
    }
}
