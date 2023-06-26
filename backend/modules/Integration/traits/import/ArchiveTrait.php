<?php

namespace backend\modules\Integration\traits\import;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;


trait ArchiveTrait
{
    public $archive_base_path;
    public $archive_drafts_path;
    
    public function checkSubfolders($subfolders)
    {
        foreach ($subfolders as $subfolder) {
            if (!is_dir("$this->archive_base_path/$subfolder")) return false;
        }
        
        return true;
    }
    
    public function parseTests($test_paths, $media_files = [])
    {
        foreach ($test_paths as $test_path) {
            $id = Yii::$app->services->string->xmlAttribute($test_path, 'url_name');
            $file = "$this->archive_drafts_path/problem/$id.xml";
            
            if (!is_file($file)) return false;
            
            $file_content = new \SimpleXMLElement(file_get_contents($file));
            $type = array_key_first((array)$file_content->children());
            
            if (!in_array($type, ['stringresponse', 'numericalresponse', 'multiplechoiceresponse', 'optionresponse', 'choiceresponse'])) {
                continue;
            }
            
            switch ($type) {
                case 'stringresponse':
                case 'numericalresponse':
                    $question = $file_content->xpath("/problem/$type")[0]->asXML();
                    preg_match_all('(answer="([\w\dа-яёА-ЯЁ_ ]*)")', $question, $answers);
                    
                    $answers = array_map(function($value) use ($media_files) {
                        $result = html_entity_decode($value);
                        $result = Yii::$app->services->html->purify($result);
                        return trim(strtr($result, $media_files));
                    }, $answers[1]);
                    
                    $result[$id]['input_type'] = 'text_area';
                    $result[$id]['options']['answers'] = $answers;
                    
                    break;
                    
                case 'multiplechoiceresponse':
                case 'optionresponse':
                    $options_block = $file_content->xpath("/problem/$type/choicegroup | /problem/$type/optioninput")[0];
                    
                    $question = $file_content->xpath("/problem/$type")[0]->asXML();
                    $question = str_replace($options_block->asXML(), null, $question);
                    
                    $options_block = $options_block->xpath('choice | option');
                    
                    $options = array_map(function($value) use ($media_files) {
                        $result = html_entity_decode($value->asXML());
                        $result = Yii::$app->services->html->purify($result);
                        return trim(strtr($result, $media_files));
                    }, $options_block);
                    
                    $answers = array_map(fn($value) => Yii::$app->services->string->xmlAttribute($value, 'correct'), $options_block);
                    
                    $result[$id]['input_type'] = 'radio';
                    $result[$id]['options'] = [
                        'answers' => array_keys($answers, 'true') ?: [0],
                        'options' => $options,
                    ];
                    
                    break;
                    
                case 'choiceresponse':
                    $options_block = $file_content->xpath("/problem/$type/checkboxgroup")[0];
                    
                    $question = $file_content->xpath("/problem/$type")[0]->asXML();
                    $question = str_replace($options_block->asXML(), null, $question);
                    
                    $options_block = $options_block->xpath('choice');
                    
                    $options = array_map(function($value) use ($media_files) {
                        $result = html_entity_decode($value->asXML());
                        $result = Yii::$app->services->html->purify($result);
                        return trim(strtr($result, $media_files));
                    }, $options_block);
                    
                    $answers = array_map(fn($value) => Yii::$app->services->string->xmlAttribute($value, 'correct'), $options_block);
                    
                    $result[$id]['input_type'] = 'checkbox';
                    $result[$id]['options'] = [
                        'answers' => array_keys($answers, 'true') ?: [0],
                        'options' => $options,
                    ];
                    
                    break;
            }
            
            $question = strtr($question, $media_files);
            $question = Yii::$app->services->html->purify($question);
            $result[$id]['question'] = trim($question);
        }
        
        return $result ?? [];
    }
    
    public function deleteFiles($archive_base_path)
    {
        unlink("$archive_base_path.tar.gz");
        FileHelper::removeDirectory($archive_base_path);
    }
}
