<?php

namespace backend\modules\Menu\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class Menu extends ActiveRecord
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public $parent_id;
    
    public static function tableName()
    {
        return '{{%menu}}';
    }
    
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => \creocoder\nestedsets\NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
            'htmlTree' => [
                'class' => \wokster\treebehavior\NestedSetsTreeBehavior::className(),
                'labelAttribute' => 'name',
                'isAttributeTranslatable' => true,
            ],
            'translation' => [
                'class' => \speedrunner\behaviors\TranslationBehavior::className(),
                'attributes' => ['name', 'url'],
            ],
        ];
    }
    
    public function transactions()
    {
        return [
            static::SCENARIO_DEFAULT => static::OP_ALL,
        ];
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'url'], 'string', 'max' => 100],
            [['parent_id'], 'required', 'when' => fn ($model) => $model->isNewRecord],
            
            [['parent_id'], 'exist', 'targetClass' => self::className(), 'targetAttribute' => 'id'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'url' => Yii::t('app', 'Ссылка'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'parent_id' => Yii::t('app', 'Родитель'),
        ];
    }
    
    public static function find()
    {
        return new \speedrunner\db\NestedSetsQuery(get_called_class());
    }
}
