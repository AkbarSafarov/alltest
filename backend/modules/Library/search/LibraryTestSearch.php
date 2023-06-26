<?php

namespace backend\modules\Library\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\Library\models\LibraryTest;


class LibraryTestSearch extends LibraryTest
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public $search_common;
    public $search_created_at_from;
    public $search_created_at_to;
    public $search_updated_at_from;
    public $search_updated_at_to;
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['subject_id', 'category_id', 'input_type', 'question', 'status', 'created_at', 'updated_at'], 'safe'],
            [['search_common'], 'safe'],
            [['search_created_at_from', 'search_created_at_to', 'search_updated_at_from', 'search_updated_at_to'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'search_common' => Yii::t('app', 'Общий поиск'),
            'search_created_at_from' => Yii::t('app', 'Дата создания (от)'),
            'search_created_at_to' => Yii::t('app', 'Дата создания (до)'),
            'search_updated_at_from' => Yii::t('app', 'Дата изменения (от)'),
            'search_updated_at_to' => Yii::t('app', 'Дата изменения (до)'),
        ]);
    }
    
    public function search()
    {
        $query = LibraryTest::find()
            ->with(['subject', 'category']);
        
        $query->byState($this->state == 'archive');
        
        if (Yii::$app->user->identity->role == 'teacher') {
            $query->byUser('creator_id');
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
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'category_id' => $this->category_id,
            'input_type' => $this->input_type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['like', 'question', $this->search_common],
            ['like', 'options', $this->search_common],
        ]);
        
        $query->andFilterWhere([
            'and',
            ['>=', 'created_at', $this->search_created_at_from ? date('Y-m-d H:i:s', strtotime($this->search_created_at_from)) : null],
            ['<=', 'created_at', $this->search_created_at_to ? date('Y-m-d H:i:s', strtotime($this->search_created_at_to)) : null],
            ['>=', 'updated_at', $this->search_updated_at_from ? date('Y-m-d H:i:s', strtotime($this->search_updated_at_from)) : null],
            ['<=', 'updated_at', $this->search_updated_at_to ? date('Y-m-d H:i:s', strtotime($this->search_updated_at_to)) : null],
        ]);
        
		return $dataProvider;
    }
}