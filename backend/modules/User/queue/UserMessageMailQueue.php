<?php

namespace backend\modules\User\queue;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;


class UserMessageMailQueue extends BaseObject implements JobInterface
{
    public $model;
    public $user_search_query;
    
    public function execute($queue)
    {
        Yii::$app->services->mail->send(
            $this->user_search_query->select('user.username')->column(),
            $this->model->theme,
            'message',
            (array)$this->model,
            $this->model->file ? ['file' => Yii::getAlias("@frontend/web/{$this->model->file}"), 'name' => 'File'] : null
        );
    }
}