<?php

namespace backend\modules\Reward\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class RewardLeague extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%reward_league}}';
    }
    
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => \speedrunner\behaviors\TranslationBehavior::className(),
                'attributes' => ['name', 'description'],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'icon'], 'required'],
            [['name', 'icon'], 'string', 'max' => 100],
            [['description'], 'string'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'key' => Yii::t('app', 'Ключ'),
            'name' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'icon' => Yii::t('app', 'Иконка'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }
}
