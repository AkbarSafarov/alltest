<?php

namespace backend\modules\Library\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\Library\models\LibraryTestCategory;


class LibraryTestCategorySearch extends LibraryTestCategory
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public function rules()
    {
        return [
            [['id', 'subject_id'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = LibraryTestCategory::find()
            ->with(['subject']);
        
        $query->byState($this->state == 'archive');
        
        $user = Yii::$app->user->identity;
        
        if ($user->role == 'teacher') {
            $query->andWhere([
                'creator_id' => $user->id,
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
            'id' => $this->id,
            'subject_id' => $this->subject_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        
		return $dataProvider;
    }
}