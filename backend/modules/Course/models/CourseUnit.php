<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class CourseUnit extends ActiveRecord
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    use \backend\modules\Library\traits\LibraryAttachmentTrait {
        behaviors as libraryAttachmentBehaviors;
    }
    
    public $children;
    
    public static function tableName()
    {
        return '{{%course_unit}}';
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge($this->libraryAttachmentBehaviors(), [
            'tree' => [
                'class' => \creocoder\nestedsets\NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
            'htmlTree' => [
                'class' => \wokster\treebehavior\NestedSetsTreeBehavior::className(),
                'labelAttribute' => 'name',
                'isAttributeTranslatable' => false,
            ],
            'moderation' => [
                'class' => \backend\modules\Moderation\behaviors\ModerationStatusBehavior::className(),
                'scenarios' => ['default', 'update_section', 'update_unit', 'move'],
            ],
        ]);
    }
    
    public function transactions()
    {
        return [
            static::SCENARIO_DEFAULT => static::OP_ALL,
        ];
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'update_section' => ['name'],
            'update_unit' => ['name', 'type_id', 'description', 'default_time_for_test'],
            'empty' => [],
            'move' => [],
        ]);
    }
    
    public function rules()
    {
        return [
            [['name', 'type_id'], 'required'],
            [['name'], 'string', 'max' => 200],
            [['description'], 'string'],
            [['default_time_for_test'], 'integer', 'min' => 0],
            [['default_time_for_test'], 'default', 'value' => 0],
            
            [['type_id'], 'exist', 'targetClass' => CourseUnitType::className(), 'targetAttribute' => 'id'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'type_id' => Yii::t('app', 'Тип'),
            'status' => Yii::t('app', 'Статус'),
            'description' => Yii::t('app', 'Описание'),
            'default_time_for_test' => Yii::t('app', 'Рекомендуемое время для прохождения теста (в мниутах)'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'tree']);
    }
    
    public function getType()
    {
        return $this->hasOne(CourseUnitType::className(), ['id' => 'type_id']);
    }
    
    public static function find()
    {
        return (new \speedrunner\db\NestedSetsQuery(get_called_class()));
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if (isset($changedAttributes['status']) && $this->status == 'active') {
            $this->service->updateUserCourseUnit();
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }
}
