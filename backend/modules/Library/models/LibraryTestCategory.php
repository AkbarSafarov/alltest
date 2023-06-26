<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use speedrunner\validators\UnchangeableValidator;


class LibraryTestCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%library_test_category}}';
    }
    
    public function rules()
    {
        return [
            [['subject_id', 'name'], 'required'],
            [['name'], 'string', 'max' => 100],
            
            [['subject_id'], UnchangeableValidator::className(), 'when' => fn($model) => $model->is_default],
            
            [['subject_id'], 'exist', 'targetClass' => LibraryTestSubject::className(), 'targetAttribute' => 'id', 'filter' => function($query) {
                ArrayHelper::remove($query->where, 1);
                
                if (Yii::$app->user->identity->role == 'teacher') {
                    $query->joinWith(['teachers'], false)->andWhere(['user.id' => Yii::$app->user->id]);
                }
            }],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'subject_id' => Yii::t('app', 'Предмет'),
            'name' => Yii::t('app', 'Название'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
    
    public function getSubject()
    {
        return $this->hasOne(LibraryTestSubject::className(), ['id' => 'subject_id']);
    }
    
    public static function find()
    {
        switch (ArrayHelper::getValue(Yii::$app->user, 'identity.role')) {
            case 'teacher':
                return parent::find()
                    ->andWhere([
                        'or',
                        ['creator_id' => Yii::$app->user->id],
                        ['is_default' => 1],
                    ]);
            default:
                return parent::find();
        }
    }
    
    public function beforeSave($insert)
    {
        $this->creator_id = $this->creator_id ?: Yii::$app->user->id;
        return parent::beforeSave($insert);
    }
    
    public function beforeDelete()
    {
        if ($this->is_default) {
            Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Вы не можете удалить эту запись'));
            return false;
        }
        return parent::beforeDelete();
    }
    
    public function afterDelete()
    {
        $default_category = self::find()->andWhere(['is_default' => 1, 'subject_id' => $this->subject_id])->one();
        
        if ($default_category) {
            LibraryTest::updateAll(['category_id' => $default_category->id], ['category_id' => $this->id]);
        }
        
        return parent::afterDelete();
    }
}
