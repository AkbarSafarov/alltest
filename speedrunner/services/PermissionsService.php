<?php

namespace speedrunner\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii2mod\rbac\filters\AccessControl;

use yii\base\Module;
use yii\web\Controller;
use yii\base\Action;


class PermissionsService
{
    public static function isAllowedByRoute($route)
    {
        $route = explode('/', $route);
        $module_id = $route[count($route) - 3] ?? Yii::$app->id;
        $controller_id = $route[count($route) - 2];
        $action_id = $route[count($route) - 1];
        
        $module = new Module($module_id, Yii::$app);
        $controller = new Controller($controller_id, $module);
        $action = new Action($action_id, $controller);
        
        return (new AccessControl())->beforeAction($action, true);
    }
    
    public static function isUpdatable($model)
    {
        $user = Yii::$app->user->identity;
        
        switch (StringHelper::basename($model->className())) {
            case 'LibraryTest':
                return $user->role != 'teacher' || $model->status != 'moderating';
            default:
                return true;
        }
    }
    
    public static function isDeletable($model)
    {
        $user = Yii::$app->user->identity;
        
        switch (StringHelper::basename($model->className())) {
            case 'Course':
            case 'CoursePackage':
                return !$model->service->isActive();
            case 'CourseUnit':
                return $user->role != 'teacher' || !($model->status == 'active' || $model->children()->andWhere(['status' => 'active'])->exists());
            case 'LibraryTest':
                return ($user->role != 'teacher' || $model->creator_id == $user->id) && in_array($model->status, ['development', 'rejected']);
            case 'LibraryTestCategory':
                return ($user->role != 'teacher' || $model->creator_id == $user->id) && !$model->is_default;
            default:
                return true;
        }
    }
}
