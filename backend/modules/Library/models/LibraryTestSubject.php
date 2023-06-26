<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\User;


class LibraryTestSubject extends ActiveRecord
{
    public $teachers_tmp;
    
    public static function tableName()
    {
        return '{{%library_test_subject}}';
    }
    
    public function behaviors()
    {
        return [
            'relations_many_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'manyMany',
                'attributes' => [
                    'teachers_tmp' => [
                        'model' => new LibraryTestSubjectTeacherRef(),
                        'relation' => 'teachers',
                        'attributes' => [
                            'main' => 'subject_id',
                            'relational' => 'teacher_id',
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            
            [['teachers_tmp'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id', 'allowArray' => true, 'filter' => function($query) {
                ArrayHelper::remove($query->where, 1);
                $query->andWhere(['role' => 'teacher']);
            }],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'teachers_tmp' => Yii::t('app', 'Учителя'),
        ];
    }
    
    public function getTeachers()
    {
        return $this->hasMany(User::className(), ['id' => 'teacher_id'])
            ->viaTable('library_test_subject_teacher_ref', ['subject_id' => 'id']);
    }
    
    public function afterSave($insert, $changed_attributes)
    {
        if ($insert) {
            $default_category = new LibraryTestCategory();
            $default_category->subject_id = $this->id;
            $default_category->name = 'Неотсортированные';
            $default_category->is_default = 1;
            $default_category->save();
        }
        
        return parent::afterSave($insert, $changed_attributes);
    }
}
