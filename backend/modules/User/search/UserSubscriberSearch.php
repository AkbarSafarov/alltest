<?php

namespace backend\modules\User\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\User\models\User;


class UserSubscriberSearch extends User
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public $search_course_id;
    public $search_subscribed_from;
    public $search_subscribed_to;
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'last_activity', 'full_name', 'phone'], 'safe'],
            [['search_course_id', 'search_subscribed_from', 'search_subscribed_to'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'search_course_id' => Yii::t('app', 'Курс'),
            'search_subscribed_from' => Yii::t('app', 'Дата подписки (от)'),
            'search_subscribed_to' => Yii::t('app', 'Дата подписки (до)'),
        ]);
    }
    
    public function search()
    {
        $query = User::find()
            ->joinWith(['profile', 'courses'])
            ->groupBy('user.id')
            ->andWhere([
                'and',
                ['user.role' => 'student'],
            ]);
        
        $query->byState($this->state == 'archive');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
                'pageSizeLimit' => [1, 100],
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
        ]);
        
        if (!$this->validate()) {
            $query->andWhere('false');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'user.id' => $this->id,
        ]);
        
        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.last_activity', $this->last_activity])
            ->andFilterWhere(['like', 'user_profile.full_name', $this->full_name]);
        
        $query->andFilterWhere([
            'and',
            ['user_course.course_id' => $this->search_course_id],
            ['>=', 'user_course.demo_datetime_to', $this->search_subscribed_from ? date('Y-m-d H:i:s', strtotime($this->search_subscribed_from)) : null],
            ['<=', 'user_course.demo_datetime_to', $this->search_subscribed_to ? date('Y-m-d H:i:s', strtotime($this->search_subscribed_to)) : null],
            'user_course.demo_datetime_to IS NOT NULL',
        ]);
        
        foreach ($this->profile_attributes as $p_a) {
            $dataProvider->sort->attributes[$p_a] = [
                'asc' => ["user_profile.$p_a" => SORT_ASC],
                'desc' => ["user_profile.$p_a" => SORT_DESC],
            ];
        }
        
		return $dataProvider;
    }
}
