<?php

namespace backend\modules\Reward\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use backend\modules\Reward\models\RewardAchievement;


class RewardAchievementSearch extends RewardAchievement
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['key', 'name', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = RewardAchievement::find()
            ->orderBy('sort');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 100,
                'pageSizeLimit' => [100, 100],
            ],
            'sort' => false,
        ]);
        
        if (!$this->validate()) {
            $query->andWhere('false');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'key' => $this->key,
        ]);
        
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        
        //        Translations
        
        $lang = Yii::$app->language;
        
        foreach ($this->behaviors['translation']->attributes as $t_a) {
            $query->andFilterWhere(['like', new Expression("LOWER(JSON_EXTRACT($t_a, '$.$lang'))"), mb_strtolower($this->{$t_a})]);
            $query->addSelect(['*', new Expression("$t_a->>'$.$lang' as json_$t_a")]);
        }
        
		return $dataProvider;
    }
}