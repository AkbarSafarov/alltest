<?php

namespace backend\modules\Moderation\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use backend\modules\Moderation\enums\ModerationEnums;
use backend\modules\Course\models\CourseUnit;
use backend\modules\Library\models\LibraryTest;


class ModerationStatus extends ActiveRecord
{
    public function init()
    {
        $this->enums = new ModerationEnums();
        return parent::init();
    }
    
    public static function tableName()
    {
        return '{{%moderation_status}}';
    }
    
    public function rules()
    {
        return [
            [['model_class', 'model_id', 'new_value'], 'required'],
            [['model_class'], 'in', 'range' => array_keys($this->enums->modelClasses())],
            [['new_value'], 'valueValidation'],
        ];
    }
    
    public function valueValidation($attribute, $params, $validator)
    {
        if (!$this->model) {
            return $this->addError('model_id', Yii::t('app', '{attribute} не найден', [
                'attribute' => $this->getAttributeLabel('model_id'),
            ]));
        }
        
        $this->old_value = $this->model->status;
        $avalable_values = ArrayHelper::getValue($this->enums->statuses(), "$this->old_value.can_change", []);
        
        if (!in_array($this->new_value, $avalable_values)) {
            return $this->addError('model_id', Yii::t('app', 'Невозможно сменить статус для данной записи'));
        }
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'model_id' => Yii::t('app', 'Запись'),
            'old_value' => Yii::t('app', 'Старое значение'),
            'new_value' => Yii::t('app', 'Новое значение'),
            'created_at' => Yii::t('app', 'Создано'),
        ];
    }
    
    public function getModel()
    {
        switch ($this->model_class) {
            case 'CourseUnit':
                return $this->hasOne(CourseUnit::className(), ['id' => 'model_id'])->byState(false)->onCondition(['>=', 'depth', 1]);
            case 'LibraryTest':
                $query = $this->hasOne(LibraryTest::className(), ['id' => 'model_id'])->byState(false);
                return Yii::$app->user->identity->role == 'teacher' ? $query->byUser('creator_id') : $query;
            default:
                return null;
        }
    }
    
    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->id;
        $this->old_value = $this->model->status;
        
        if ($this->new_value == 'return_from_moderation') {
            $old_model = self::find()
                ->andWhere([
                    'model_class' => $this->model_class,
                    'model_id' => $this->model_id,
                ])
                ->orderBy('id DESC')
                ->one();
            
            $this->new_value = ArrayHelper::getValue($old_model, 'old_value', 'development');
        }
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        switch ($this->model_class) {
            case 'CourseUnit':
                $models = $this->new_value == 'development' ? [$this->model] : array_merge([$this->model], $this->model->children()->all());
                break;
            case 'LibraryTest':
                $models = [$this->model];
                break;
        }
        
        foreach ($models as $model) {
            $model->scenario = 'empty';
            $model->status = $this->new_value;
            $model->save();
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }
}
