<?php

namespace backend\modules\Integration\queue\import;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;


class NotificationQueue extends BaseObject implements JobInterface
{
    public $form;
    public $user_id;
    public $message;
    public $archive_base_path;
    
    public function execute($queue)
    {
        Yii::$app->services->notification->create(
            [$this->user_id],
            'message_user', 0,
            [
                'theme' => $this->message,
            ]
        );
        
        $this->form->deleteFiles($this->archive_base_path);
    }
}