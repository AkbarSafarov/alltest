<?php

namespace backend\modules\Integration\services\export;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

use backend\modules\Integration\queue\export\LibraryTestCategoryQueue;


class LibraryTestCategoryService
{
    use \backend\modules\Integration\traits\export\ArchiveTrait;
    
    public $category;
    public $tests_query;
    
    public function __construct($category, $tests_query)
    {
        $this->category = $category;
        $this->tests_query = $tests_query;
        
        $this->archive_base_path = 'library';
        $this->archive_drafts_path = 'library';
        
        $this->model_class = 'LibraryTestCategory';
        $this->wiki = [
            'author' => Inflector::slug(Yii::$app->services->settings->site_name),
            'slug' => Inflector::slug($category->subject->name . '-' . $category->name),
            'year' => date('Y'),
        ];
    }
    
    private function addInformation()
    {
        //        Config and structure
        
        $file_content = new \SimpleXMLElement('<library/>');
        $file_content->addAttribute('url_name', $this->wiki['year']);
        $file_content->addAttribute('org', $this->wiki['author']);
        $file_content->addAttribute('library', $this->wiki['slug']);
        $file_content->addAttribute('display_name', $this->category->name);
        
        $this->archive->addFromString("$this->archive_base_path/library.xml", $file_content->asXML());
        
        //        Tests
        
        $tests_count = $this->tests_query->count();
        $tests_limit = 10000;
        
        if ($tests_count > $tests_limit) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Максимальное количество тестов - {limit}, при попытке экспорта - {count}', [
                'limit' => $tests_limit,
                'count' => $tests_count,
            ]));
            
            return false;
        }
        
        $query_limit = 500;
        
        for ($i = 0; $i <= $tests_count; $i += $query_limit) {
            Yii::$app->queue->push(new LibraryTestCategoryQueue([
                'service' => $this,
                'query' => $this->tests_query,
                'limit' => $query_limit,
                'offset' => $i,
            ]));
        }
        
        return true;
    }
    
    private function addStructure() { return true; }
}
