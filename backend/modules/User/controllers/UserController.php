<?php

namespace backend\modules\User\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use backend\modules\User\models\User;
use backend\modules\User\models\UserCourse;
use backend\modules\User\search\UserSubscriberSearch;


class UserController extends CrudController
{
    public function init()
    {
        $this->model = new User();
        return parent::init();
    }
    
    public function actions()
    {
        $lang = Yii::$app->language;
        $actions = ArrayHelper::filter(parent::actions(), ['create', 'update', 'delete']);
        
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => Actions\crud\DataProviderAction::className(),
                'render_params' => fn() => [
                    'user_search_params' => ArrayHelper::merge(Yii::$app->request->get('UserSearch', []), ['model' => 'user']),
                ],
            ],
            'view' => [
                'class' => Actions\crud\ViewAction::className(),
                'render_params' => fn() => [
                    'courses_query' => UserCourse::find()
                        ->joinWith(['user', 'course', 'currentUnit'], false)
                        ->with([
                            'leagues.league' => fn($query) => $query->select(['id', new Expression("name->>'$.$lang' as name")]),
                            'achievements.achievement' => fn($query) => $query->select(['id', new Expression("name->>'$.$lang' as name")]),
                        ])
                        ->andWhere(['user.id' => Yii::$app->request->get('id')])
                        ->select([
                            'course.name',
                            'user_course.id', 'user_course.progress', 'user_course.last_visit', 'user_course.created_at',
                            new Expression("user_course_unit.unit_json->>'$.name' as current_unit"),
                        ])
                        ->groupBy('user_course.id'),
                ],
            ],
            'subscribers' => [
                'class' => Actions\crud\DataProviderAction::className(),
                'model' => new UserSubscriberSearch(),
                'render_view' => 'subscribers',
                'render_params' => fn() => [
                    'user_search_params' => ArrayHelper::merge(Yii::$app->request->get('UserSubscriberSearch', []), ['model' => 'user_subscriber']),
                ],
            ],
            'file-delete' => [
                'class' => Actions\crud\FileDeleteAction::className(),
                'allowed_attributes' => ['image'],
            ],
            'profile-update' => [
                'class' => Actions\web\FormAction::className(),
                'model' => Yii::$app->user->identity,
                'render_view' => 'profile_update',
                'run_method' => 'updateProfile',
                'success_message' => 'Данные профиля были изменены',
                'redirect_route' => ['profile-update'],
            ],
        ]);
    }
    
    public function findModel($id)
    {
        $query = $this->model->find()->where(['id' => $id]);
        
        if (Yii::$app->user->identity->role == 'moderator') {
            $user = Yii::$app->user->identity;
            $query->andWhere([
                'and',
                ['role' => ['moderator', 'teacher', 'student']],
                "IF(role = 'moderator', id = $user->id, true)",
            ]);
        }
        
        return $query->one();
    }
    
    public function actionCheckPassword()
    {
        $user = Yii::$app->user->identity;
        $password = Yii::$app->request->post('password');
        return $password && $user->validatePassword($password) ? $user->password_hash : null;
    }
}
