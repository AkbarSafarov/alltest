<?php

namespace backend\modules\System\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class SystemLanguage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%system_language}}';
    }
    
    public function behaviors()
    {
        return [
            'cache' => [
                'class' => \speedrunner\behaviors\CacheBehavior::className(),
                'tags' => ['active_system_languages'],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'code', 'image'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 1000],
            [['code'], 'string', 'max' => 20],
            [['code'], 'unique'],
            [['code'], 'match', 'pattern' => '/^[a-zA-Z0-9\-]+$/'],
            [['is_active', 'is_main'], 'boolean'],
            [['is_main'], 'isMainValidation'],
        ];
    }
    
    public function isMainValidation($attribute, $params, $validator)
    {
        if (ArrayHelper::getValue($this->oldAttributes, $attribute) && !$this->{$attribute}) {
            return $this->addError($attribute, Yii::t('app', 'Один из языков должен быть основным'));
        }
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'code' => Yii::t('app', 'Код'),
            'image' => Yii::t('app', 'Изображение'),
            'is_active' => Yii::t('app', 'Активный'),
            'is_main' => Yii::t('app', 'Основной'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function beforeSave($insert)
    {
        if ($this->is_main) {
            SystemLanguage::updateAll(['is_main' => 0]);
            $this->is_active = 1;
        }
        
        return parent::beforeSave($insert);
    }
}
