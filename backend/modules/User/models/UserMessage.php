<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use backend\modules\User\search\UserSearch;
use backend\modules\User\search\UserSubscriberSearch;
use backend\modules\User\queue\UserMessageNotificationQueue;
use backend\modules\User\queue\UserMessageMailQueue;


class UserMessage extends ActiveRecord
{
    public $user_search_params;
    public $user_search_result;
    
    public static function tableName()
    {
        return '{{%user_message}}';
    }
    
    public function behaviors()
    {
        return [
            'file' => [
                'class' => \speedrunner\behaviors\FileBehavior::className(),
                'attributes' => ['file'],
                'multiple' => false,
                'save_dir' => 'uploaded/messages',
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['theme', 'user_search_params'], 'required'],
            [['theme'], 'string', 'max' => 100],
            [['text'], 'string'],
            [['file'], 'file', 'extensions' => Yii::$app->services->array->leaves(Yii::$app->params['extensions']), 'maxSize' => 1024 * 1024 * 100],
            
            [['user_search_params'], 'userSearchValidation'],
        ];
    }
    
    public function userSearchValidation($attribute, $params, $validator)
    {
        $user_search_params = json_decode($this->user_search_params, true);
        array_walk_recursive($user_search_params, fn (&$v) => $v = trim($v));
        
        switch ($user_search_params['model'] ?? null) {
            case 'user':
                $user_search_model = new UserSearch();
                break;
            case 'user_subscriber':
                $user_search_model = new UserSubscriberSearch();
                break;
            default:
                return $this->addError($attribute, Yii::t('app', 'Пользователи не найдены'));
        }
        
        $user_search_model->load([$user_search_model->formName() => $user_search_params]);
        $dataProvider = $user_search_model->search();
        
        $total_count = $dataProvider->totalCount;
        $limit = $this->text ? 1000 : 1000000;
        
        if (!$total_count || $total_count > $limit) {
            return $this->addError($attribute, Yii::t('app', 'Пользователей должно быть не менее {min} и не более {max}', [
                'min' => 1,
                'max' => $limit,
            ]));
        }
        
        $this->user_search_result = [
            'query' => $dataProvider->query,
            'total_count' => $total_count,
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'theme' => Yii::t('app', 'Тема'),
            'text' => Yii::t('app', 'Текст'),
            'file' => Yii::t('app', 'Файл'),
            'created_at' => Yii::t('app', 'Создано'),
        ];
    }
    
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_from_id']);
    }
    
    public function beforeValidate()
    {
        if ($file = UploadedFile::getInstance($this, 'file')) {
            $this->file = $file;
        }
        
        return parent::beforeValidate();
    }
    
    public function beforeSave($insert)
    {
        $this->user_from_id = Yii::$app->user->id;
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        //        Notifications
        
        for ($i = 0; $i < $this->user_search_result['total_count']; $i += 30000) {
            Yii::$app->queue->push(new UserMessageNotificationQueue([
                'model' => Yii::$app->services->array->toObjects($this->attributes),
                'user_search_query' => (clone($this->user_search_result['query']))->offset($i)->limit(30000),
            ]));
        }
        
        //        Mailing
        
        if ($this->text) {
            for ($i = 0; $i < $this->user_search_result['total_count']; $i++) {
                Yii::$app->queue->push(new UserMessageMailQueue([
                    'model' => Yii::$app->services->array->toObjects($this->attributes),
                    'user_search_query' => (clone($this->user_search_result['query']))->offset($i)->limit(1),
                ]));
            }
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }
}
