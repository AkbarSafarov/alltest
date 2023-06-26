<?php

namespace backend\modules\Integration\traits\export;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;

use backend\modules\Integration\queue\export\NotificationQueue;


trait ArchiveTrait
{
    public $archive;
    public $archive_url;
    public $archive_base_path;
    public $archive_drafts_path;
    
    public $model_class;
    public $wiki;
    public $assets = [];
    
    public function process()
    {
        $this->archive_url = Yii::getAlias('@backend/runtime/integration');
        FileHelper::createDirectory($this->archive_url, 0777);
        $this->archive_url .= '/' . uniqid() . '.zip';
        file_put_contents($this->archive_url, null);
        chmod($this->archive_url, 0777);
        $this->archive = new \ZipArchive();
        $this->archive->open($this->archive_url, \ZipArchive::OVERWRITE);
        
        if (!$this->addInformation() || !$this->addStructure()) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Произошла ошибка'));
            return false;
        }
        
        Yii::$app->queue->push(new NotificationQueue([
            'user_id' => Yii::$app->user->id,
            'archive_url' => $this->archive_url,
        ]));
        
        return true;
    }
    
    public function addTests($tests, $file_content)
    {
        foreach ($tests as $test) {
            if (!in_array($test->input_type, ['text_area', 'radio', 'checkbox'])) continue;
            
            $file_content->addChild('problem')->addAttribute('url_name', $test->sort);
            
            $test_file_content = new \SimpleXMLElement('<problem/>');
            $test_file_content->addAttribute('display_name', 'Test ' . ($test->sort + 1));
            
            $question = $this->replaceMediaFiles($test->question);
            $question = $this->model_class == 'LibraryTestCategory' ? strip_tags($question) : $question;
            
            $options = json_decode($test->options);
            
            switch ($test->input_type) {
                case 'text_area':
                    $test_file_content_child = $test_file_content->addChild('stringresponse');
                    $test_file_content_child->addAttribute('answer', $options->answers[0] ?? null);
                    $test_file_content_child->addAttribute('type', 'ci');
                    
                    $test_file_content_child->addChild('p', $question);
                    $test_file_content_child->addChild('textline')->addAttribute('size', 20);
                    
                    foreach (array_slice($options->answers, 1) as $answer) {
                        $answer = $this->replaceMediaFiles($answer);
                        $test_file_content_child->addChild('additional_answer')->addAttribute('answer', $answer);
                    }
                    
                    $test_file_content->addAttribute('markdown', "$question\n= " . implode("\nor= ", $options->answers));
                    break;
                    
                case 'radio':
                    $test_file_content_child = $test_file_content->addChild('multiplechoiceresponse');
                    
                    $test_file_content_child->addChild('p', $question);
                    $test_file_content_subchild = $test_file_content_child->addChild('choicegroup');
                    $test_file_content_subchild->addAttribute('type', 'MultipleChoice');
                    
                    foreach ($options->options as $option_key => $option) {
                        $option = $this->replaceMediaFiles($option);
                        $correct_params = in_array($option_key, $options->answers) ? ['x', 'true'] : [' ', 'false'];
                        $question .= "\n({$correct_params[0]}) $option";
                        
                        $test_file_content_subchild->addChild('choice', $option)->addAttribute('correct', $correct_params[1]);
                    }
                    
                    $test_file_content->addAttribute('markdown', $question);
                    break;
                    
                case 'checkbox':
                    $test_file_content_child = $test_file_content->addChild('choiceresponse');
                    
                    $test_file_content_child->addChild('p', $question);
                    $test_file_content_subchild = $test_file_content_child->addChild('checkboxgroup');
                    
                    foreach ($options->options as $option_key => $option) {
                        $option = $this->replaceMediaFiles($option);
                        $correct_params = in_array($option_key, $options->answers) ? ['x', 'true'] : [' ', 'false'];
                        $question .= "\n[{$correct_params[0]}] $option";
                        
                        $test_file_content_subchild->addChild('choice', $option)->addAttribute('correct', $correct_params[1]);
                    }
                    
                    $test_file_content->addAttribute('markdown', $question);
                    break;
            }
            
            if ($this->model_class == 'LibraryTestCategory') {
                $test_file_content = $test_file_content->asXML();
            } else {
                $test_file_content = preg_replace_callback(
                    '(<p>([^<>]*)<\/p>)',
                    function($matches) {
                        return htmlspecialchars_decode(strip_tags($matches[0]));
                    },
                    $test_file_content->asXML(),
                    1
                );
            }
            
            $this->archive->addFromString("$this->archive_drafts_path/problem/$test->sort.xml", $test_file_content);
        }
        
        return $file_content;
    }
    
    public function addAssets()
    {
        $assets_path = "$this->archive_base_path/policies/assets.json";
        
        $assets = ArrayHelper::merge(json_decode($this->archive->getFromName($assets_path), true), $this->assets);
        $assets = json_encode($assets, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        
        $this->archive->addFromString($assets_path, $assets);
        
        return true;
    }
    
    public function replaceMediaFiles($html)
    {
        if ($this->model_class == 'LibraryTestCategory') return $html;
        
        $media_files = [];
        preg_match_all('(src="([^ ]*)")', $html, $urls);
        
        foreach ($urls[1] as $url) {
            $file = Yii::getAlias("@frontend/web$url");
            
            if (!is_file($file) || filesize($file) > Yii::$app->params['integration']['max_file_size']) {
                continue;
            }
            
            $name = uniqid() . '.' . pathinfo($file, PATHINFO_EXTENSION);
            $html = str_replace($url, "/static/$name", $html);
            
            $this->assets[$name] = $this->addMediaFile($file, $name);
            $this->archive->addFile($file, "$this->archive_base_path/static/$name");
        }
        
        return $html;
    }
    
    public function addMediaFile($file, $name)
    {
        $wiki = implode('+', $this->wiki);
        
        return [
            'contentType' => mime_content_type($file),
            'content_son' => [
                'category' => 'asset',
                'course' => $this->wiki['slug'],
                'name' =>  $name,
                'org' => $this->wiki['author'],
                'revision' => null,
                'run' => $this->wiki['year'],
                'tag' => 'c4x',
            ],
            'displayname' => $name,
            'filename' => "asset-v1:$wiki+type@asset+block@$name",
            'import_path' => null,
            'locked' => false,
            'thumbnail_location' => null,
        ];
    }
}
