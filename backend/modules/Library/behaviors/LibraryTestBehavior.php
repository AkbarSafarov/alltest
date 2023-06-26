<?php

namespace backend\modules\Library\behaviors;

use Yii;
use yii\base\Behavior;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;


class LibraryTestBehavior extends Behavior
{
    public string $relation;
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
        ];
    }
    
    public function beforeSave($event)
    {
        if ($this->owner->isAttributeActive('options')) {
            $options = $this->owner->options;
            
            switch ($this->owner->input_type) {
                case 'text_area':
                    $options['answers'] = array_values($options['answers']);
                    break;
                    
                case 'radio':
                case 'checkbox':
                    $options['answers'] = [];
                    
                    foreach ($this->owner->options['answers'] as $answer) {
                        $options['answers'][] = array_search($answer, array_keys($options['options']));
                    }
                    
                    $options['options'] = array_values($options['options']);
                    break;
                    
                case 'match':
                    $options['answers'] = array_map(fn ($value) => ['key' => $value['key'], 'value' => $value['value']], $options['answers']);
                    $options['answers'] = array_values($options['answers']);
                    break;
                    
                case 'sequence':
                    $options = ArrayHelper::getValue($this->owner->oldAttributes, 'options', ['answers' => []]);
                    
                    if ($answers = UploadedFile::getInstancesByName($this->owner->upload_name)) {
                        foreach ($answers as $answer) {
                            $answer_url = Yii::$app->services->file->save($answer);
                            $answers_arr[] = $answer_url;
                        }
                        
                        $options = ArrayHelper::merge($options, ['answers' => $answers_arr]);
                    }
                    
                    break;
            }
            
            $this->owner->options = $options;
        }
    }
}
