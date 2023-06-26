<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use speedrunner\services\StringService;

use backend\modules\System\models\SystemLanguage;


class LibraryTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%library_template}}';
    }
    
    public function rules()
    {
        return [
            [['category_id', 'name', 'image', 'content'], 'required'],
            [['name', 'image'], 'string', 'max' => 100],
            [['content'], 'string'],
            
            [['category_id'], 'exist', 'targetClass' => LibraryTemplateCategory::className(), 'targetAttribute' => 'id'],
            [['language_id'], 'exist', 'targetClass' => SystemLanguage::className(), 'targetAttribute' => 'id'],
            
            [['content'], 'contentValidation'],
        ];
    }
    
    public function contentValidation($attribute, $params, $validator)
    {
        //        Special chars
        
        $content_inputs_all = StringService::getInbetweenStrings($this->content, '{([\w\dа-яёА-ЯЁ_ ]+):([\w\d_]+)}');
        $content_inputs = StringService::getInbetweenStrings($this->content);
        
        if ($content_inputs_all != $content_inputs) {
            return $this->addError($attribute, Yii::t('app', 'Доступны только буквы, цифры и пробел'));
        }
        
        //        Input types
        
        $available_input_types = array_keys($this->enums->inputTypes());
        
        $inputs = array_combine($content_inputs[1], $content_inputs[2]);
        
        foreach ($inputs as $input) {
            if (!in_array($input, $available_input_types)) {
                return $this->addError(
                    $attribute,
                    Yii::t('app', '"{input_type}" - неверный тип поля', ['input_type' => $input])
                    . ". " . Yii::t('app', 'Доступные типы: {input_types}', ['input_types' => implode(', ', $available_input_types)])
                );
            }
        }
        
        $this->inputs = $inputs;
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'category_id' => Yii::t('app', 'Категория'),
            'language_id' => Yii::t('app', 'Язык'),
            'name' => Yii::t('app', 'Название'),
            'image' => Yii::t('app', 'Изображение'),
            'content' => Yii::t('app', 'Контент'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function getCategory()
    {
        return $this->hasOne(LibraryTemplateCategory::className(), ['id' => 'category_id']);
    }
    
    public function getLanguage()
    {
        return $this->hasOne(SystemLanguage::className(), ['id' => 'language_id']);
    }
}
