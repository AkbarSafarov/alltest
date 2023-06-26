<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class LibraryTemplateCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%library_template_category}}';
    }
    
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => \speedrunner\behaviors\TranslationBehavior::className(),
                'attributes' => ['name'],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function getTemplates()
    {
        return $this->hasMany(LibraryTemplate::className(), ['category_id' => 'id']);
    }
}
