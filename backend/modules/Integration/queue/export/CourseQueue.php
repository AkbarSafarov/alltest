<?php

namespace backend\modules\Integration\queue\export;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\ArrayHelper;


class CourseQueue extends BaseObject implements JobInterface
{
    public $service;
    public $section;
    
    public function execute($queue)
    {
        $this->service->archive = new \ZipArchive();
        $this->service->archive->open($this->service->archive_url, \ZipArchive::CREATE);
        
        $service = $this->service;
        
        //        Section
        
        $wiki = implode('+', $service->wiki);
        
        $section_file_content = new \SimpleXMLElement('<chapter/>');
        $section_file_content->addAttribute('display_name', $this->section->name);
        
        foreach ($this->section->children as $subsection) {
            
            //        Subsection
            
            $section_file_content->addChild('sequential')->addAttribute('url_name', $subsection->lft);
            
            $subsection_file_content = new \SimpleXMLElement('<sequential/>');
            $subsection_file_content->addAttribute('display_name', $subsection->name);
            
            $service->archive->addFromString("$service->archive_base_path/sequential/$subsection->lft.xml", $subsection_file_content->asXML());
            
            foreach ($subsection->children as $unit_key => $unit) {
                
                //        Unit
                
                $unit_file_content = new \SimpleXMLElement('<vertical/>');
                $unit_file_content->addAttribute('display_name', $unit->name);
                $unit_file_content->addAttribute('index_in_children_list', $unit_key);
                $unit_file_content->addAttribute('parent_url', "block-v1:$wiki+type@sequential+block@$subsection->lft");
                
                //        Html
                
                if ($unit->description) {
                    $unit_file_content->addChild('html')->addAttribute('url_name', $unit->lft);
                    $unit_html_file_content = new \SimpleXMLElement('<html/>');
                    $unit_html_file_content->addAttribute('filename', $unit->lft);
                    
                    $service->archive->addFromString("$service->archive_drafts_path/html/$unit->lft.xml", $unit_html_file_content->asXML());
                    $service->archive->addFromString("$service->archive_drafts_path/html/$unit->lft.html", $service->replaceMediaFiles($unit->description));
                }
                
                //        Tests
                
                $unit_file_content = $service->addTests($unit->attachmentTests, $unit_file_content);
                $service->archive->addFromString("$service->archive_drafts_path/vertical/$unit->lft.xml", $unit_file_content->asXML());
            }
        }
        
        $service->archive->addFromString("$service->archive_base_path/chapter/{$this->section->lft}.xml", $section_file_content->asXML());
        $service->addAssets();
    }
}