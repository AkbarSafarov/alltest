<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class CourseUnitType extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%course_unit_type}}';
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
            [['name', 'icon'], 'required'],
            [['name', 'icon'], 'string', 'max' => 100],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'icon' => Yii::t('app', 'Иконка'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
}
