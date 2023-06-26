<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class CourseAuthor extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%course_author}}';
    }
    
    public function rules()
    {
        return [
            [['full_name', 'image', 'experience'], 'required'],
            [['full_name'], 'string', 'max' => 100],
            [['image', 'experience'], 'string', 'max' => 1000],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'course_id' => Yii::t('app', 'Курс'),
            'full_name' => Yii::t('app', 'ФИО'),
            'image' => Yii::t('app', 'Изображение'),
            'experience' => Yii::t('app', 'Опыт'),
        ];
    }
    
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
