<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class CourseAdvantage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%course_advantage}}';
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['icon'], 'string', 'max' => 1000],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'course_id' => Yii::t('app', 'Курс'),
            'name' => Yii::t('app', 'Название'),
            'icon' => Yii::t('app', 'Иконка'),
        ];
    }
    
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
