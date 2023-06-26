<?php

namespace backend\modules\Order\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use backend\modules\Order\models\OrderPromocode;


class OrderPromocodeSearch extends OrderPromocode
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public function rules()
    {
        return [
            [['id', 'percent', 'max_activations', 'used_activations'], 'integer'],
            [['name', 'description', 'key', 'date_from', 'date_to', 'created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = OrderPromocode::find();
        
        $date = date('Y-m-d');
        
        switch ($this->state) {
            case 'active':
                $query->andFilterWhere([
                    'and',
                    "IF(date_to IS NOT NULL, date_to >= '$date', true)",
                    "IF(max_activations IS NOT NULL, IFNULL(used_activations, 0) < max_activations, true)",
                ]);
                break;
                
            case 'outdated':
                $query->andFilterWhere([
                    'or',
                    ['<', 'date_to', $date],
                    "used_activations >= max_activations",
                ]);
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
            'percent' => $this->percent,
            'max_activations' => $this->max_activations,
        ]);
        
        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['date_from' => $this->date_from],
            ['date_to' => $this->date_from],
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