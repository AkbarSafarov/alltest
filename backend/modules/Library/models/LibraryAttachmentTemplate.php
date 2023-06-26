<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use backend\modules\Library\enums\LibraryTemplateEnums;


class LibraryAttachmentTemplate extends ActiveRecord
{
    public $upload_name;
    
    public static function tableName()
    {
        return '{{%library_attachment_template}}';
    }
    
    public function rules()
    {
        return [
            [['label', 'input_type', 'group'], 'required'],
            [['label', 'group'], 'string', 'max' => 100],
            [['input_type'], 'in', 'range' => array_merge(array_keys(LibraryTemplateEnums::inputTypes()), ['static'])],
            
            [['value'], 'string', 'when' => fn ($model) => $model->input_type != 'file_input'],
            [
                ['value'],
                'file',
                'extensions' => Yii::$app->services->array->leaves(Yii::$app->params['extensions']),
                'maxSize' => 1024 * 1024 * 100,
                'when' => fn ($model) => $model->input_type == 'file_input'
            ],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'label' => Yii::t('app', 'Лейбл'),
            'input_type' => Yii::t('app', 'Тип поля'),
            'value' => Yii::t('app', 'Значение'),
        ];
    }
    
    public function beforeValidate()
    {
        if ($this->input_type == 'file_input' && $file = UploadedFile::getInstanceByName($this->upload_name)) {
            $this->value = $file;
        }
        
        return parent::beforeValidate();
    }
    
    public function beforeSave($insert)
    {
        if ($this->input_type == 'file_input') {
            $old_value = ArrayHelper::getValue($this, 'oldAttributes.value');
            
            if ($value = UploadedFile::getInstanceByName($this->upload_name)) {
                $this->value = Yii::$app->services->file->save($value);
                Yii::$app->services->file->delete($old_value);
            } else {
                $this->value = $this->value ?: $old_value;
            }
        }
        
        return parent::beforeSave($insert);
    }
}
