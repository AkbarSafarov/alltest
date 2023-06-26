<?php

namespace backend\modules\Library\validators;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use yii\validators\Validator;
use yii\validators\EachValidator;


class LibraryTestOptionsValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $options = (array)ArrayHelper::getValue($model, 'options.options');
        $answers = (array)ArrayHelper::getValue($model, 'options.answers');
        
        $default_error_message = Yii::t('app', 'Неверный {attribute}', [
            'attribute' => $model->getAttributeLabel($attribute),
        ]);
        
        //        Validating answers
        
        if (!$answers) {
            return $this->addError($model, $attribute, $default_error_message);
        }
        
        if ($model->input_type != 'match') {
            foreach ($answers as $answer) {
                if (!is_string($answer) && !is_int($answer)) {
                    return $this->addError($model, $attribute, $default_error_message);
                }
            }
        }
        
        //        Validating options and answers
        
        switch ($model->input_type) {
            case 'radio':
            case 'checkbox':
                if (!$options) {
                    return $this->addError($model, $attribute, $default_error_message);
                }
                
                foreach ($options as $option) {
                    if (!is_string($option)) {
                        return $this->addError($model, $attribute, $default_error_message);
                    }
                }
                
                break;
            case 'match':
                foreach ($answers as $answer) {
                    $answer_key = $answer['key'] ?? null;
                    $answer_value = $answer['value'] ?? null;
                    
                    if (!is_string($answer_key) || !is_string($answer_value)) {
                        return $this->addError($model, $attribute, $default_error_message);
                    }
                }
                
                break;
            case 'sequence':
                $answers = UploadedFile::getInstancesByName($model->upload_name);
                
                if ($answers = UploadedFile::getInstancesByName($model->upload_name)) {
                    (new EachValidator([
                        'rule' => [
                            'file',
                            'extensions' => Yii::$app->params['extensions']['image'],
                            'maxSize' => 1024 * 1024,
                        ],
                    ]))->validate($answers, $error_message);
                    
                    if ($error_message) {
                        return $this->addError($model, $attribute, $error_message);
                    }
                }
                
                break;
        }
    }
}
