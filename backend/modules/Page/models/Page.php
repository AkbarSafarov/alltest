<?php

namespace backend\modules\Page\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use speedrunner\validators\SlugValidator;


class Page extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%page}}';
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
                'attributes' => ['name', 'description'],
            ],
            'seo_meta' => [
                'class' => \speedrunner\behaviors\SeoMetaBehavior::className(),
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string'],
            
            [['slug'], SlugValidator::className()],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'slug' => Yii::t('app', 'Ссылка'),
            'description' => Yii::t('app', 'Описание'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function beforeSave($insert)
    {
        if (in_array($this->id, [2, 7])) {
            $this->slug = $this->oldAttributes['slug'];
        }
        
        return parent::beforeSave($insert);
    }
    
    public function beforeDelete()
    {
        if (in_array($this->id, [2])) {
            Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Вы не можете удалить эту запись'));
            return false;
        }
        
        return parent::beforeDelete();
    }
}
