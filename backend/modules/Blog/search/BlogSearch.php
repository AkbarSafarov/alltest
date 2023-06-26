<?php

namespace backend\modules\Blog\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use backend\modules\Blog\models\Blog;


class BlogSearch extends Blog
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
            [['id'], 'integer'],
            [['name', 'slug', 'published_at_from', 'published_at_to', 'created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = Blog::find();
        
        $date = date('Y-m-d H:i:s');
        
        switch ($this->state) {
            case 'active':
                $query->andWhere("IF(published_at_to IS NOT NULL, published_at_to >= '$date', true)");
                break;
                
            case 'outdated':
                $query->andWhere(['<', 'published_at_to', $date]);
                break;
                
            case 'archive':
                $query->byState(true);
                break;
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
        ]);
        
        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['like', 'published_at_from', $this->published_at_from],
            ['like', 'published_at_to', $this->published_at_from],
        ]);
        
        //        Translations
        
        $lang = Yii::$app->language;
        
        foreach ($this->behaviors['translation']->attributes as $t_a) {
            $query->andFilterWhere(['like', new Expression("LOWER(JSON_EXTRACT($t_a, '$.$lang'))"), mb_strtolower($this->{$t_a})]);
            $query->addSelect(['*', new Expression("$t_a->>'$.$lang' as json_$t_a")]);
            
            $dataProvider->sort->attributes[$t_a] = [
                'asc' => ["json_$t_a" => SORT_ASC],
                'desc' => ["json_$t_a" => SORT_DESC],
            ];
        }
        
		return $dataProvider;
    }
}