<?php

namespace backend\modules\User\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\User\models\User;


class UserSearch extends User
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public $search_common;
    public $search_course_id;
    public $search_course_status;
    public $search_created_at_from;
    public $search_created_at_to;
    public $search_last_activity_from;
    public $search_last_activity_to;
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'nickname', 'role', 'last_activity', 'created_at', 'updated_at'], 'safe'],
            [['full_name', 'gender', 'birth_date', 'phone', 'parent_phone', 'address'], 'safe'],
            [['search_common', 'search_course_id', 'search_course_status'], 'safe'],
            [['search_created_at_from', 'search_created_at_to', 'search_last_activity_from', 'search_last_activity_to'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'search_common' => Yii::t('app', 'Общий поиск'),
            'search_course_id' => Yii::t('app', 'Курс'),
            'search_course_status' => Yii::t('app', 'Статус'),
            'search_created_at_from' => Yii::t('app', 'Дата регистрации (от)'),
            'search_created_at_to' => Yii::t('app', 'Дата регистрации (до)'),
            'search_last_activity_from' => Yii::t('app', 'Дата последней активности (от)'),
            'search_last_activity_to' => Yii::t('app', 'Дата последней активности (до)'),
        ]);
    }
    
    public function search()
    {
        $query = User::find()
            ->joinWith(['profile', 'courses'])
            ->groupBy('user.id');
        
        $query->byState($this->state == 'archive');
        
        if (Yii::$app->user->identity->role == 'moderator') {
            $user = Yii::$app->user->identity;
            $query->andWhere([
                'and',
                ['user.role' => ['moderator', 'teacher', 'student']],
                "IF(user.role = 'moderator', user.id = $user->id, true)",
            ]);
        }
        
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
            'user.role' => $this->role,
            'user_profile.gender' => $this->gender,
            'user_profile.birth_date' => $this->birth_date,
        ]);
        
        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.nickname', $this->nickname])
            ->andFilterWhere(['like', 'user.last_activity', $this->last_activity])
            ->andFilterWhere(['like', 'user_profile.full_name', $this->full_name])
            ->andFilterWhere(['like', 'user_profile.phone', $this->phone])
            ->andFilterWhere(['like', 'user_profile.parent_phone', $this->parent_phone])
            ->andFilterWhere(['like', 'user_profile.address', $this->address])
            ->andFilterWhere(['like', 'user.created_at', $this->created_at])
            ->andFilterWhere(['like', 'user.updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['like', 'user.username', $this->search_common],
            ['like', 'user.nickname', $this->search_common],
            ['like', 'user_profile.full_name', $this->search_common],
            ['like', 'user_profile.phone', $this->search_common],
            ['like', 'user_profile.parent_phone', $this->search_common],
            ['like', 'user_profile.address', $this->search_common],
        ]);
        
        $query->andFilterWhere([
            'and',
            ['user_course.course_id' => $this->search_course_id],
            ['>=', 'user.created_at', $this->search_created_at_from ? date('Y-m-d H:i:s', strtotime($this->search_created_at_from)) : null],
            ['<=', 'user.created_at', $this->search_created_at_to ? date('Y-m-d H:i:s', strtotime($this->search_created_at_to)) : null],
            ['>=', 'user.last_activity', $this->search_last_activity_from ? date('Y-m-d H:i:s', strtotime($this->search_last_activity_from)) : null],
            ['<=', 'user.last_activity', $this->search_last_activity_to ? date('Y-m-d H:i:s', strtotime($this->search_last_activity_to)) : null],
        ]);
        
        switch ($this->search_course_status) {
            case 'active':
                $query->andWhere([
                    'and',
                    ['is', 'user_course.demo_datetime_to', null],
                    ['>=', 'user_course.last_visit', date('Y-m-d H:i:s', strtotime('-1 week'))],
                ]);
                break;
                
            case 'inactive':
                $query->andWhere([
                    'and',
                    ['is', 'user_course.demo_datetime_to', null],
                    [
                        'or',
                        ['<', 'user_course.last_visit', date('Y-m-d H:i:s', strtotime('-1 week'))],
                        ['is', 'user_course.last_visit', null],
                    ],
                ]);
                break;
                
            case 'finished':
                $query->andWhere(['user_course.progress' => 100]);
                break;
                
            case 'subscribers':
                $query->andWhere(['is not', 'user_course.demo_datetime_to', null]);
                break;
        }
        
        foreach ($this->profile_attributes as $p_a) {
            $dataProvider->sort->attributes[$p_a] = [
                'asc' => ["user_profile.$p_a" => SORT_ASC],
                'desc' => ["user_profile.$p_a" => SORT_DESC],
            ];
        }
        
		return $dataProvider;
    }
}
