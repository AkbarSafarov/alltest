<?php

namespace backend\modules\Integration\queue\export;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;


class LibraryTestCategoryQueue extends BaseObject implements JobInterface
{
    public $service;
    public $query;
    public $limit;
    public $offset;
    
    public function execute($queue)
    {
        $this->service->archive = new \ZipArchive();
        $this->service->archive->open($this->service->archive_url, \ZipArchive::CREATE);
        
        $service = $this->service;
        
        //        Tests
        
        $row_number = $this->offset - 1;
        Yii::$app->db->createCommand("SET @row_number = $row_number;")->execute();
        $tests = $this->query
            ->select(['id', 'input_type', 'question', 'options', '(@row_number:=@row_number + 1) as sort'])
            ->limit($this->limit)
            ->offset($this->offset)
            ->asObject()->all();
        
        $library_path = "$service->archive_base_path/library.xml";
        $file_content = new \SimpleXMLElement($service->archive->getFromName($library_path));
        $file_content = $service->addTests($tests, $file_content);
        
        $service->archive->addFromString($library_path, $file_content->asXML());
    }
}