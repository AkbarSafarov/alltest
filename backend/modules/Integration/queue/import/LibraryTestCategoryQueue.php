<?php

namespace backend\modules\Integration\queue\import;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;


class LibraryTestCategoryQueue extends BaseObject implements JobInterface
{
    public $form;
    public $category;
    public $tests_path_xml_group;
    
    public function execute($queue)
    {
        $this->tests_path_xml_group = array_map(fn($value) => new \SimpleXMLElement($value), $this->tests_path_xml_group);
        $datetime = date('Y-m-d H:i:s');
        
        $attributes = [
            'subject_id', 'category_id', 'creator_id',
            'input_type', 'question', 'options',
            'created_at', 'updated_at',
        ];
        
        foreach ($this->form->parseTests($this->tests_path_xml_group) as $test) {
            $records[] = [
                $this->category->subject_id,
                $this->category->id,
                $this->category->creator_id,
                $test['input_type'],
                $test['question'],
                $test['options'],
                $datetime,
                $datetime,
            ];
        }
        
        Yii::$app->db->createCommand()->batchInsert('library_test', $attributes, $records ?? [])->execute();
    }
}