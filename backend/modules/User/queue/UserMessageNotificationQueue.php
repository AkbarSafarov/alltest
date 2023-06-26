<?php

namespace backend\modules\User\queue;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;


class UserMessageNotificationQueue extends BaseObject implements JobInterface
{
    public $model;
    public $user_search_query;
    
    public function execute($queue)
    {
        Yii::$app->services->notification->create(
            $this->user_search_query->select('user.id')->column(),
            'message_user', $this->model->id,
            [
                'theme' => $this->model->theme,
                'text' => $this->model->text,
                'file' => $this->model->file,
            ]
        );
    }
}