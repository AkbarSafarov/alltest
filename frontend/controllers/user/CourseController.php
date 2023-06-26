<?php

namespace frontend\controllers\user;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use backend\modules\Course\enums\CourseEnums;
use backend\modules\User\models\UserCourse;
use backend\modules\User\models\UserCourseUnit;
use backend\modules\User\enums\UserCourseEnums;


class CourseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex($offset = 0, $type = null)
    {
        $query = UserCourse::find()
            ->with(['course', 'leagues.league', 'achievements.achievement'])
            ->andWhere([
                'and',
                ['user_id' => Yii::$app->user->id],
                ['like', new Expression("LOWER(JSON_EXTRACT(course_json, '$.name'))"), mb_strtolower(Yii::$app->request->get('user_course_search'))],
            ])
            ->orderBy('id DESC')
            ->offset($offset);
        
        switch ($type) {
            case 'linear':
            case 'exam':
                $query->andWhere([
                    'and',
                    ['=', new Expression("JSON_EXTRACT(course_json, '$.type')"), $type],
                    ['>=', 'date_to', date('Y-m-d')],
                ]);
                break;
            case 'archive':
                $query->andWhere(['<', 'date_to', date('Y-m-d')]);
                break;
        }
        
        $courses = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $offset,
            ],
        ]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/particles/user_courses', [
                'models' => $courses->getModels(),
            ]);
        } else {
            $type_quantities_query = UserCourse::find()->andWhere(['user_id' => Yii::$app->user->id]);
            
            $type_quantities = [
                null => $type_quantities_query->count(),
                'linear' => (clone($type_quantities_query))->andWhere([
                    'and',
                    ['=', new Expression("JSON_EXTRACT(course_json, '$.type')"), 'linear'],
                    ['>=', 'date_to', date('Y-m-d')],
                ])->count(),
                'exam' => (clone($type_quantities_query))->andWhere([
                    'and',
                    ['=', new Expression("JSON_EXTRACT(course_json, '$.type')"), 'exam'],
                    ['>=', 'date_to', date('Y-m-d')],
                ])->count(),
                'archive' => (clone($type_quantities_query))->andWhere(['<', 'date_to', date('Y-m-d')])->count(),
            ];
            
            return $this->render('index', [
                'courses' => $courses,
                'types' => UserCourseEnums::types(),
                'type_quantities' => $type_quantities,
                'type' => $type,
            ]);
        }
    }
    
    public function actionView($id)
    {
        $model = UserCourse::find()
            ->active()
            ->andWhere([
                'id' => $id,
                'user_id' => Yii::$app->user->id,
            ])
            ->one();
        
        if (!$model) {
            return $this->redirect(['index']);
        }
        
        return $this->render('view', [
            'model' => $model,
            'course' => Yii::$app->services->array->toObjects($model->course_json),
            'course_types' => CourseEnums::types(),
        ]);
    }
    
    public function actionResetProgress($id)
    {
        $model = UserCourse::find()
            ->active()
            ->andWhere([
                'id' => $id,
                'user_id' => Yii::$app->user->id,
            ])
            ->one();
        
        if ($model) {
            $model->updateAttributes([
                'progress' => 0,
                'performance' => 0,
            ]);
            
            UserCourseUnit::updateAll([
                'is_unlocked' => 0,
                'is_passed' => 0,
                'is_current' => 0,
                'performance' => [],
            ], ['tree' => $model->id]);
            
            UserCourseUnit::updateAll([
                'is_unlocked' => 1,
            ], [
                'and',
                ['tree' => $model->id],
                ['<=', 'lft', UserCourseUnit::find()->andWhere(['tree' => $model->id, 'depth' => 3])->min('lft')],
            ]);
            
            Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Прогресс курса был сброшен'));
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}
