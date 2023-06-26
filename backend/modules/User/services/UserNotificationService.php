<?php

namespace backend\modules\User\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;

use backend\modules\User\models\UserNotification;


class UserNotificationService extends ActiveService
{
    public function __construct(?UserNotification $model = null)
    {
        $this->model = $model;
    }
    
    public static function create(array $user_ids, $action_type, $action_id, array $params = [])
    {
        $datetime = date('Y-m-d H:i:s');
        
        foreach ($user_ids as $user_id) {
            $records[] = [
                $user_id,
                $action_type,
                $action_id,
                $params,
                $datetime,
            ];
        }
        
        $attributes = ['user_id', 'action_type', 'action_id', 'params', 'created_at'];
        Yii::$app->db->createCommand()->batchInsert('user_notification', $attributes, $records ?? [])->execute();
    }
    
    public function actionData()
    {
        switch ($this->model->action_type) {
            case 'auth_reset_password':
                return [
                    'label' => Yii::t('app_notification', 'Новый пароль для аккаунта задан'),
                ];
            case 'auth_signup':
                return [
                    'label' => Yii::t('app_notification', 'Добро пожаловать, {full_name}!', [
                        'full_name' => ArrayHelper::getValue($this->model, 'params.full_name'),
                    ]),
                ];
            case 'course_demo':
                return [
                    'label' => Yii::t('app_notification', 'Вы получили демо доступ для курса "{course}" до {datetime}', [
                        'course' => ArrayHelper::getValue($this->model, 'params.course'),
                        'datetime' => ArrayHelper::getValue($this->model, 'params.datetime'),
                    ]),
                ];
            case 'integration_export_archive':
                return [
                    'label' => Yii::t('app_notification', 'Скачать архив'),
                    'url' => Yii::$app->urlManagerBackend->createUrl([
                        '/integration/download/file',
                        'url' => ArrayHelper::getValue($this->model, 'params.url'),
                    ]),
                ];
            case 'message_user':
                return [
                    'label' => ArrayHelper::getValue($this->model, 'params.theme'),
                ];
            case 'user_course_achievement_create':
                return [
                    'label' => Yii::t('app_notification', 'Поздравляем! Вы получили достижение "{achievement}" по курсу "{course}"', [
                        'course' => ArrayHelper::getValue($this->model, 'params.course'),
                        'achievement' => ArrayHelper::getValue($this->model, 'params.achievement'),
                    ]),
                ];
        }
    }
}