<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;
use backend\modules\System\models\SystemLanguage;


class UserCourse extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_course}}';
    }
    
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getLanguage()
    {
        return $this->hasOne(SystemLanguage::className(), ['id' => 'language_id']);
    }
    
    public function getUnits()
    {
        return $this->hasMany(UserCourseUnit::className(), ['tree' => 'id'])->orderBy('lft');
    }
    
    public function getCurrentUnit()
    {
        return $this->hasOne(UserCourseUnit::className(), ['tree' => 'id'])->onCondition(['is_passed' => 0, 'depth' => 3])->orderBy('lft');
    }
    
    public function getAchievements()
    {
        return $this->hasMany(UserCourseAchievement::className(), ['user_course_id' => 'id'])->orderBy('id DESC');
    }
    
    public function getLeagues()
    {
        return $this->hasMany(UserCourseLeague::className(), ['user_course_id' => 'id'])->orderBy('id DESC');
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $course = $this->course;
            
            $this->course_json = $course->attributes;
            $this->language_id = $course->language_id;
            $this->date_from = $course->date_from;
            $this->date_to = $course->date_to;
            
            $this->library_attachment_json = [
                'templates' => ArrayHelper::getColumn($course->attachmentTemplates, 'attributes'),
                'tests' => ArrayHelper::getColumn($course->attachmentTests, 'attributes'),
                'test_packs' => ArrayHelper::getColumn($course->attachmentTestPacks, 'attributes'),
            ];
        }
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $course = Course::find()->andWhere(['id' => $this->course_id])->addSelect(['active_structure'])->one();
            $units = Yii::$app->services->array->getChildrenRecursion($course->active_structure);
            $units = ArrayHelper::index($units ?: [], 'id');
            
            $is_unlocked = true;
            
            foreach ($units as $key => $unit) {
                $records[] = [
                    $unit['id'], $unit, $unit['type_id'],
                    $is_unlocked, [],
                    $this->id, $unit['lft'], $unit['rgt'], $unit['depth'],
                    [
                        'templates' => $unit['attachmentTemplates'],
                        'tests' => $unit['attachmentTests'],
                        'test_packs' => $unit['attachmentTestPacks'],
                    ],
                ];
                
                $is_unlocked = $unit['depth'] == 3 ? false : $is_unlocked;
            }
            
            $attributes = ['unit_id', 'unit_json', 'type_id', 'is_unlocked', 'performance', 'tree', 'lft', 'rgt', 'depth', 'library_attachment_json'];
            
            Yii::$app->db->createCommand()->batchInsert('user_course_unit', $attributes, $records ?? [])->execute();
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }
}
