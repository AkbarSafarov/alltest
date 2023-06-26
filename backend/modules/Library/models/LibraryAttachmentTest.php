<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use speedrunner\validators\UnchangeableValidator;
use backend\modules\Library\validators\LibraryTestOptionsValidator;

use backend\modules\Library\enums\LibraryTestEnums;


class LibraryAttachmentTest extends ActiveRecord
{
    public $upload_name;
    
    public function init()
    {
        $this->options = [];
        $this->enums = new LibraryTestEnums();
        
        return parent::init();
    }
    
    public static function tableName()
    {
        return '{{%library_attachment_test}}';
    }
    
    public function behaviors()
    {
        return [
            'library_test' => [
                'class' => \backend\modules\Library\behaviors\LibraryTestBehavior::className(),
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['input_type', 'question'], 'required'],
            [['options'], 'required', 'enableClientValidation' => false, 'when' => fn ($model) => $model->input_type != 'sequence'],
            
            [['input_type'], 'in', 'range' => array_keys($this->enums->inputTypes())],
            [['question'], 'string'],
            
            [['input_type'], UnchangeableValidator::className()],
            [['options'], LibraryTestOptionsValidator::className()],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'input_type' => Yii::t('app', 'Тип поля'),
            'question' => Yii::t('app', 'Вопрос'),
            'options' => Yii::t('app', 'Варианты ответов'),
        ];
    }
}
