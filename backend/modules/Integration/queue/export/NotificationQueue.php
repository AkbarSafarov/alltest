<?php

namespace backend\modules\Integration\queue\export;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;


class NotificationQueue extends BaseObject implements JobInterface
{
    public $user_id;
    public $archive_url;
    
    public function execute($queue)
    {
        $tar_archive_url = rtrim($this->archive_url, '.zip') . '.tar';
        
        $archive = new \PharData($this->archive_url);
        $archive = $archive->convertToData(\Phar::TAR);
        $archive->compress(\Phar::GZ);
        
        Yii::$app->services->notification->create(
            [$this->user_id],
            'integration_export_archive', 0,
            [
                'url' => "$tar_archive_url.gz",
                'download' => true,
            ]
        );
        
        unlink($this->archive_url);
        unlink($tar_archive_url);
    }
}