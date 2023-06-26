<?php

namespace backend\modules\Order\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use backend\modules\Order\models\OrderDiscount;


class OrderDiscountSearch extends OrderDiscount
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public function rules()
    {
        return [
            [['id', 'percent', 'courses_tmp', 'packages_tmp'], 'integer'],
            [['name', 'date_from', 'date_to', 'created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = OrderDiscount::find()
            ->joinWith(['productsRef'], false)
            ->with(['courses', 'packages'])
            ->select(['order_discount.*'])
            ->groupBy('id');
        
        switch ($this->state) {
            case 'active':
                $query->andFilterWhere(['>=', 'order_discount.date_to', date('Y-m-d')]);
                break;
                
            case 'outdated':
                $query->andFilterWhere(['<', 'order_discount.date_to', date('Y-m-d')]);
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
            'order_discount.id' => $this->id,
            'order_discount.percent' => $this->percent,
        ]);
        
        $query->andFilterWhere(['like', 'order_discount.created_at', $this->created_at])
            ->andFilterWhere(['like', 'order_discount.updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['order_discount.date_from' => $this->date_from],
            ['order_discount.date_to' => $this->date_from],
        ]);
        
        if ($this->courses_tmp) {
            $query->andFilterWhere([
                'and',
                ['order_discount_product_ref.model_class' => 'Course'],
                ['order_discount_product_ref.model_id' => $this->courses_tmp],
            ]);
        }
        
        if ($this->packages_tmp) {
            $query->andFilterWhere([
                'and',
                ['order_discount_product_ref.model_class' => 'CoursePackage'],
                ['order_discount_product_ref.model_id' => $this->packages_tmp],
            ]);
        }
        
        //        Translations
        
        $lang = Yii::$app->language;
        
        foreach ($this->behaviors['translation']->attributes as $t_a) {
            $query->andFilterWhere(['like', new Expression("LOWER(JSON_EXTRACT(order_discount.$t_a, '$.$lang'))"), mb_strtolower($this->{$t_a})]);
            $query->addSelect([new Expression("order_discount.$t_a->>'$.$lang' as json_$t_a")]);
            
            $dataProvider->sort->attributes[$t_a] = [
                'asc' => ["json_$t_a" => SORT_ASC],
                'desc' => ["json_$t_a" => SORT_DESC],
            ];
        }
        
		return $dataProvider;
    }
}