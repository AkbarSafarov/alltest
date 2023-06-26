<?php

namespace backend\modules\Order\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\Order\models\Order;


class OrderSearch extends Order
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public $search_created_at_from;
    public $search_created_at_to;
    public $search_checkout_price_from;
    public $search_checkout_price_to;
    public $search_course_ids;
    public $search_package_ids;
    
    public function rules()
    {
        return [
            [['id', 'student_id', 'checkout_price'], 'integer'],
            [['status', 'promocode_id', 'key', 'created_at'], 'safe'],
            [['search_created_at_from', 'search_created_at_to', 'search_checkout_price_from', 'search_checkout_price_to'], 'safe'],
            [['search_course_ids', 'search_package_ids'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'search_created_at_from' => Yii::t('app', 'Дата создания (от)'),
            'search_created_at_to' => Yii::t('app', 'Дата создания (до)'),
            'search_checkout_price_from' => Yii::t('app', 'Итоговая сумма (от)'),
            'search_checkout_price_to' => Yii::t('app', 'Итоговая сумма (до)'),
            'search_course_ids' => Yii::t('app', 'Курсы'),
            'search_package_ids' => Yii::t('app', 'Пакеты'),
        ]);
    }
    
    public function search()
    {
        $query = Order::find()
            ->joinWith(['products'])
            ->with([
                'student', 'promocode',
            ])
            ->select([
                'order.*',
                "(order.total_price) - (order.discount_price) - (order.promocode_price) as checkout_price",
            ])
            ->groupBy('order.id');
        
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
            'order.id' => $this->id,
            'order.student_id' => $this->student_id,
            'order.status' => $this->status ?: ['paid_paycom', 'paid_click'],
            'order.promocode_id' => $this->promocode_id,
            '(order.total_price) - (order.discount_price) - (order.promocode_price)' => $this->checkout_price,
        ]);
        
        $query->andFilterWhere(['like', 'order.created_at', $this->created_at]);
        
        $query->andFilterWhere([
            'and',
            ['>=', 'order.created_at', $this->search_created_at_from ? date('Y-m-d H:i:s', strtotime($this->search_created_at_from)) : null],
            ['<=', 'order.created_at', $this->search_created_at_to ? date('Y-m-d H:i:s', strtotime($this->search_created_at_to)) : null],
            ['>=', '(order.total_price) - (order.discount_price) - (order.promocode_price)', $this->search_checkout_price_from],
            ['<=', '(order.total_price) - (order.discount_price) - (order.promocode_price)', $this->search_checkout_price_to],
            ["IF(model_class = 'Course', order_product.model_id, false)" => $this->search_course_ids],
            ["IF(model_class = 'CoursePackage', order_product.model_id, false)" => $this->search_package_ids],
        ]);
        
        $dataProvider->sort->attributes['checkout_price'] = [
            'asc' => ['checkout_price' => SORT_ASC],
            'desc' => ['checkout_price' => SORT_DESC],
        ];
        
		return $dataProvider;
    }
}
