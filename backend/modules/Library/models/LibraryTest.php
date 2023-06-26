<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\Library\validators\LibraryTestOptionsValidator;


class LibraryTest extends ActiveRecord
{
    public $upload_name = 'LibraryTest[options][answers]';
    
    public function init()
    {
        $this->options = [];
        return parent::init();
    }
    
    public static function tableName()
    {
        return '{{%library_test}}';
    }
    
    public function behaviors()
    {
        return [
            'library_test' => [
                'class' => \backend\modules\Library\behaviors\LibraryTestBehavior::className(),
            ],
            'moderation' => [
                'class' => \backend\modules\Moderation\behaviors\ModerationStatusBehavior::className(),
                'scenarios' => ['default'],
            ],
        ];
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'empty' => [],
        ]);
    }
    
    public function rules()
    {
        return [
            [['subject_id', 'category_id', 'input_type', 'question'], 'required'],
            [['options'], 'required', 'enableClientValidation' => false, 'when' => fn ($model) => $model->input_type != 'sequence'],
            
            [['input_type'], 'in', 'range' => array_keys($this->enums->inputTypes())],
            [['question'], 'string'],
            
            [['subject_id'], 'exist', 'targetClass' => LibraryTestSubject::className(), 'targetAttribute' => 'id', 'filter' => function($query) {
                ArrayHelper::remove($query->where, 1);
                
                if (Yii::$app->user->identity->role == 'teacher') {
                    $query->joinWith(['teachers'], false)->andWhere(['user.id' => Yii::$app->user->id]);
                }
            }],
            
            [['category_id'], 'exist', 'targetClass' => LibraryTestCategory::className(), 'targetAttribute' => 'id', 'filter' => function($query) {
                ArrayHelper::remove($query->where, 1);
                $query->andWhere(['subject_id' => $this->subject_id]);
                
                if (Yii::$app->user->identity->role == 'teacher') {
                    $query->andWhere([
                        'or',
                        ['creator_id' => Yii::$app->user->id],
                        ['is_default' => 1],
                    ]);
                }
            }],
            
            [['options'], LibraryTestOptionsValidator::className()],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'subject_id' => Yii::t('app', 'Предмет'),
            'category_id' => Yii::t('app', 'Тема предмета'),
            'input_type' => Yii::t('app', 'Тип поля'),
            'question' => Yii::t('app', 'Вопрос'),
            'options' => Yii::t('app', 'Варианты ответов'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function getSubject()
    {
        return $this->hasOne(LibraryTestSubject::className(), ['id' => 'subject_id']);
    }
    
    public function getCategory()
    {
        return $this->hasOne(LibraryTestCategory::className(), ['id' => 'category_id']);
    }
    
    public static function find()
    {
        switch (ArrayHelper::getValue(Yii::$app->user, 'identity.role')) {
            case 'teacher':
                return parent::find()->byUser('creator_id');
            default:
                return parent::find();
        }
    }
    
    public function beforeSave($insert)
    {
        $this->creator_id = $this->creator_id ?: Yii::$app->user->id;
        return parent::beforeSave($insert);
    }
}
