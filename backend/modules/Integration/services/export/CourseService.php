<?php

namespace backend\modules\Integration\services\export;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

use backend\modules\Integration\queue\export\CourseQueue;


class CourseService
{
    use \backend\modules\Integration\traits\export\ArchiveTrait;
    
    public $course;
    public $structure;
    
    public function __construct($course, $structure)
    {
        $this->course = $course;
        $this->structure = $structure;
        
        $this->archive_base_path = 'course';
        $this->archive_drafts_path = 'course/drafts';
        
        $this->model_class = 'Course';
        $this->wiki = [
            'author' => Inflector::slug($course->author),
            'slug' => $course->slug,
            'year' => date('Y', strtotime($course->date_from)),
        ];
    }
    
    public function addInformation()
    {
        //        Config
        
        $file_content = new \SimpleXMLElement('<course/>');
        $file_content->addAttribute('url_name', $this->wiki['year']);
        $file_content->addAttribute('org', $this->wiki['author']);
        $file_content->addAttribute('course', $this->wiki['slug']);
        
        $this->archive->addFromString("$this->archive_base_path/course.xml", $file_content->asXML());
        
        //        Information
        
        $file_content = new \SimpleXMLElement('<course/>');
        $file_content->addAttribute('display_name', $this->course->name);
        $file_content->addAttribute('language', $this->course->language->code);
        $file_content_child = $file_content->addChild('wiki');
        $file_content_child->addAttribute('slug', implode('.', $this->wiki));
        
        foreach ($this->structure as $section) {
            $file_content_child = $file_content->addChild('chapter');
            $file_content_child->addAttribute('url_name', $section->lft);
        }
        
        $this->archive->addFromString("$this->archive_base_path/course/{$this->wiki['year']}.xml", $file_content->asXML());
        
        return true;
    }
    
    public function addStructure()
    {
        foreach ($this->structure as $section) {
            Yii::$app->queue->push(new CourseQueue([
                'service' => $this,
                'section' => $section,
            ]));
        }
        
        return true;
    }
}
