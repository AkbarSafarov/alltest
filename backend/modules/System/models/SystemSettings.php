<?php

namespace backend\modules\System\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class SystemSettings extends ActiveRecord
{
    public $service = false;
    
    public static function tableName()
    {
        return '{{%system_settings}}';
    }
    
    public function behaviors()
    {
        return [
            'cache' => [
                'class' => \speedrunner\behaviors\CacheBehavior::className(),
                'tags' => ['system_settings'],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['value'], 'string', 'max' => '100', 'when' => function ($model) {
                return in_array($model->type, ['text_input', 'text_area', 'tinymce', 'elfinder']);
            }],
            [['value'], 'boolean', 'when' => function ($model) {
                return in_array($model->type, ['checkbox']);
            }],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'label' => Yii::t('app', 'Лейбл'),
            'value' => Yii::t('app', 'Значение'),
        ];
    }
}
