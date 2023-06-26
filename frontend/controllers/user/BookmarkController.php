<?php

namespace frontend\controllers\user;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\UserCourseUnit;


class BookmarkController extends Controller
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
    
    public function actionView($id = null)
    {
        $model = UserCourseUnit::find()
            ->joinWith(['course'], false)
            ->andFilterWhere([
                'user_course.user_id' => Yii::$app->user->id,
                'user_course_unit.id' => $id,
                'user_course_unit.depth' => 3,
                'user_course_unit.is_unlocked' => 1,
                'user_course_unit.is_bookmarked' => 1,
            ])
            ->orderBy([
                'is_current' => SORT_DESC,
                'lft' => SORT_ASC,
            ])
            ->one();
        
        $unit_groups = UserCourseUnit::find()
            ->joinWith(['course'])
            ->with(['type'])
            ->andFilterWhere([
                'user_course.user_id' => Yii::$app->user->id,
                'user_course_unit.depth' => 3,
                'user_course_unit.is_unlocked' => 1,
                'user_course_unit.is_bookmarked' => 1,
            ])
            ->orderBy([
                'is_current' => SORT_DESC,
                'lft' => SORT_ASC,
            ])
            ->all();
        
        $unit_groups = ArrayHelper::index($unit_groups, null, 'tree');
        
        return $this->render('view', [
            'model' => $model,
            'unit_groups' => $unit_groups,
        ]);
    }
    
    public function actionToggle($id, $redirect_url = null)
    {
        $model = UserCourseUnit::find()
            ->joinWith(['course'], false)
            ->andFilterWhere([
                'user_course.user_id' => Yii::$app->user->id,
                'user_course_unit.id' => $id,
                'user_course_unit.depth' => 3,
                'user_course_unit.is_unlocked' => 1,
            ])
            ->one();
        
        if (!$model) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $max_quantity = 100;
        $total_quantity = UserCourseUnit::find()
            ->joinWith(['course'], false)
            ->andWhere([
                'user_course.user_id' => Yii::$app->user->id,
                'user_course_unit.is_bookmarked' => 1,
            ])
            ->count();
        
        if (!(bool)$model->is_bookmarked && $total_quantity >= $max_quantity) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Максимальное количество закладок - {max_quantity}', [
                'max_quantity' => $max_quantity,
            ]));
            
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $model->updateAttributes(['is_bookmarked' => !(bool)$model->is_bookmarked]);
        return $redirect_url ? $this->redirect($redirect_url) : $this->asJson(['is_bookmarked' => !(bool)$model->is_bookmarked]);
    }
}
