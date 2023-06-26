<?php

namespace backend\modules\Library\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\Library\models\LibraryTemplate;


class LibraryTemplateSearch extends LibraryTemplate
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['sluggable']);
        
        return $behaviors;
    }
    
    public function rules()
    {
        return [
            [['id', 'category_id'], 'integer'],
            [['name', 'image', 'language_id', 'created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = LibraryTemplate::find()
            ->with(['category', 'language']);
        
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
            'id' => $this->id,
            'category_id' => $this->category_id,
            'language_id' => $this->language_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        
		return $dataProvider;
    }
}