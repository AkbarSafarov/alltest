<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\Library\enums\LibraryTestEnums;


class LibraryAttachmentTestPack extends ActiveRecord
{
    public function init()
    {
        $this->enums = new LibraryTestEnums();
        return parent::init();
    }
    
    public static function tableName()
    {
        return '{{%library_attachment_test_pack}}';
    }
    
    public function rules()
    {
        return [
            [['subject_id', 'input_types', 'quantity'], 'required'],
            [['input_types'], 'in', 'range' => array_keys($this->enums->inputTypes()), 'allowArray' => true],
            [['quantity'], 'integer', 'min' => 0, 'max' => 100],
            
            [['subject_id'], 'exist', 'targetClass' => LibraryTestSubject::className(), 'targetAttribute' => 'id', 'filter' => function($query) {
                ArrayHelper::remove($query->where, 1);
                
                if (Yii::$app->user->identity->role == 'teacher')
                    $query->joinWith(['teachers'], false)->andWhere(['user.id' => Yii::$app->user->id]);
            }],
            
            [['category_ids'], 'each', 'rule' => [
                'exist',
                'targetClass' => LibraryTestCategory::className(),
                'targetAttribute' => 'id',
                'filter' => function($query) {
                    ArrayHelper::remove($query->where, 1);
                    $query->andWhere(['subject_id' => $this->subject_id]);
                    
                    if (Yii::$app->user->identity->role == 'teacher') {
                        $query->andWhere([
                            'or',
                            ['creator_id' => Yii::$app->user->id],
                            ['is_default' => 1],
                        ]);
                    }
                },
            ]],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'subject_id' => Yii::t('app', 'Предмет'),
            'category_ids' => Yii::t('app', 'Темы предмета'),
            'input_types' => Yii::t('app', 'Типы полей'),
            'quantity' => Yii::t('app', 'Количество'),
        ];
    }
    
    public function getSubject()
    {
        return $this->hasOne(LibraryTestSubject::className(), ['id' => 'subject_id']);
    }
    
    public function beforeSave($insert)
    {
        $this->category_ids = $this->category_ids ?: [];
        return parent::beforeSave($insert);
    }
}
