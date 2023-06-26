<?php

namespace backend\modules\Library\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\Library\models\LibraryTestSubject;


class LibraryTestSubjectSearch extends LibraryTestSubject
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'created_at', 'updated_at', 'teachers_tmp'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = LibraryTestSubject::find()
            ->joinWith(['teachers'])
            ->select(['library_test_subject.*'])
            ->groupBy('id');
        
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
            'library_test_subject.id' => $this->id,
            'user.id' => $this->teachers_tmp,
        ]);

        $query->andFilterWhere(['like', 'library_test_subject.name', $this->name])
            ->andFilterWhere(['like', 'library_test_subject.created_at', $this->created_at])
            ->andFilterWhere(['like', 'library_test_subject.updated_at', $this->updated_at]);
        
		return $dataProvider;
    }
}