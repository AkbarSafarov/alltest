<?php

namespace backend\modules\Integration\queue\import;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\CourseUnit;
use backend\modules\Library\models\LibraryAttachmentTest;


class CourseQueue extends BaseObject implements JobInterface
{
    public $form;
    public $unit_file_group;
    
    public function execute($queue)
    {
        $units = [];
        
        foreach ($this->unit_file_group as $file) {
            $file_content = new \SimpleXMLElement(file_get_contents($file));
            
            if (!($unit_label = Yii::$app->services->string->xmlAttribute($file_content, 'display_name'))) return false;
            if (!($subsection_id = Yii::$app->services->string->xmlAttribute($file_content, 'parent_url'))) return false;
            if (($subsection_index = Yii::$app->services->string->xmlAttribute($file_content, 'index_in_children_list')) === null) return false;
            
            $subsection_id = explode('@', $subsection_id);
            $subsection_id = end($subsection_id);
            
            $unit = [
                'label' => Html::encode($unit_label),
                'html' => null,
            ];
            
            //        HTML
            
            foreach ($file_content->xpath('/vertical/html') as $html) {
                $html_id = Yii::$app->services->string->xmlAttribute($html, 'url_name');
                $html_file = "{$this->form->archive_drafts_path}/html/$html_id.html";
                
                if (!is_file($html_file)) return false;
                
                $unit['html'] .= file_get_contents($html_file);
            }
            
            //        Videos
            
            foreach ($file_content->xpath('/vertical/video') as $video) {
                $video_id = Yii::$app->services->string->xmlAttribute($video, 'url_name');
                $video_file = "{$this->form->archive_drafts_path}/video/$video_id.xml";
                
                if (!is_file($video_file)) return false;
                
                $video_file_content = new \SimpleXMLElement(file_get_contents($video_file));
                
                if ($video_youtube_id = Yii::$app->services->string->xmlAttribute($video_file_content, 'youtube_id_1_0')) {
                    $video_html = Html::tag('iframe', null, ['src' => "https://www.youtube.com/embed/$video_youtube_id"]);
                } else {
                    $video_html = file_get_contents($video_file);
                    preg_match_all('(src="([^^]*)")', $video_html, $video_replace);
                    
                    $video_replace = array_flip($video_replace[1]);
                    
                    foreach ($video_replace as $key => &$value) {
                        $value = explode('@', $key);
                        $value = end($value);
                        $value = "/static/$value";
                    }
                    
                    $video_html = strtr($video_html, $video_replace);
                    $video_html = str_replace('<video ', '<video controls ', $video_html);
                }
                
                $unit['html'] .= $video_html;
            }
            
            //        Tests
            
            $unit['tests'] = $this->form->parseTests($file_content->xpath('/vertical/problem'), $this->form->media_files);
            
            //        Assigning
            
            $unit['html'] = strtr($unit['html'], $this->form->media_files);
            
            $units[$subsection_id][$subsection_index] = $unit;
        }
        
        //        Merging structure
        
        foreach ($this->form->saved_subsections as $subsection_id => &$subsection_key) {
            $subsection_key = $units[$subsection_key] ?? [];
            sort($subsection_key);
        }
        
        $this->save($units);
    }
    
    private function save($units)
    {
        $subsection_models = CourseUnit::find()->andWhere(['id' => array_keys($this->form->saved_subsections)])->indexBy('id')->all();
        
        foreach ($this->form->saved_subsections as $subsection_id => $units) {
            if (!($subsection_model = $subsection_models[$subsection_id])) continue;
            
            foreach ($units as $unit) {
                $unit_model = new CourseUnit();
                $unit_model->scenario = 'empty';
                $unit_model->name = $unit['label'];
                $unit_model->type_id = 2;
                $unit_model->description = $unit['html'];
                $unit_model->appendTo($subsection_model);
                
                $counter = 0;
                
                foreach ($unit['tests'] as $test) {
                    $test_model = new LibraryAttachmentTest();
                    $test_model->model_class = 'CourseUnit';
                    $test_model->model_id = $unit_model->id;
                    $test_model->setAttributes($test);
                    $test_model->sort = $counter;
                    $test_model->save();
                    
                    $counter++;
                }
            }
        }
    }
}