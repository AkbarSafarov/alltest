<?php

namespace backend\modules\Blog\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use speedrunner\validators\SlugValidator;
use speedrunner\validators\DatesCompareValidator;


class Blog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%blog}}';
    }
    
    public function behaviors()
    {
        return [
            'sluggable' => [
                'class' => \yii\behaviors\SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
            ],
            'translation' => [
                'class' => \speedrunner\behaviors\TranslationBehavior::className(),
                'attributes' => ['name', 'short_description', 'full_description'],
            ],
            'seo_meta' => [
                'class' => \speedrunner\behaviors\SeoMetaBehavior::className(),
            ],
        ];
    }
    
    public function rules()
    {
        $regex_pattern = '#$%^&*()+=\-\[\]\';,.\/{}|":<>?!~\\\\';
        
        return [
            [['name', 'image'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['image', 'short_description'], 'string', 'max' => 1000],
//            [['name', 'short_description'], 'match', 'pattern' => "/^[\w\d\x{0410}-\x{042F} '$regex_pattern]+$/u", 'enableClientValidation' => false],
            [['full_description'], 'string'],
            [['published_at_from', 'published_at_to'], 'date', 'format' => 'php: d.m.Y H:i'],
            
            [['slug'], SlugValidator::className()],
            [['published_at_to'], DatesCompareValidator::className(), 'params' => [
                'from' => 'published_at_from',
                'to' => 'published_at_to',
            ]],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'slug' => Yii::t('app', 'Ссылка'),
            'image' => Yii::t('app', 'Изображение'),
            'short_description' => Yii::t('app', 'Краткое описание'),
            'full_description' => Yii::t('app', 'Полное описание'),
            'published_at_from' => Yii::t('app', 'Опубликовано (от)'),
            'published_at_to' => Yii::t('app', 'Опубликовано (до)'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function beforeValidate()
    {
        if (!method_exists($this, 'search')) {
            $this->published_at_from = $this->published_at_from ?: date('d.m.Y H:i');
        }
        
        return parent::beforeValidate();
    }
}
