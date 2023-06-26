<?php

namespace backend\modules\Course\search;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use backend\modules\Course\models\Course;


class CourseSearch extends Course
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public $search_date_from;
    public $search_date_to;
    public $search_updated_at_from;
    public $search_updated_at_to;
    public $search_price_from;
    public $search_price_to;
    
    public function init()
    {
        $this->default_state = 'development';
        return parent::init();
    }
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['sluggable']);
        
        return $behaviors;
    }
    
    public function rules()
    {
        return [
            [['id', 'price', 'demo_time', 'students_start_quantity'], 'integer'],
            [['name', 'slug', 'type', 'language_id', 'teachers_tmp', 'date_from', 'date_to', 'created_at', 'updated_at'], 'safe'],
            [['search_updated_at_from', 'search_updated_at_to', 'search_date_from', 'search_date_to'], 'safe'],
            [['search_price_from', 'search_price_to'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'search_date_from' => Yii::t('app', 'Период действия (от)'),
            'search_date_to' => Yii::t('app', 'Период действия (до)'),
            'search_updated_at_from' => Yii::t('app', 'Дата изменения (от)'),
            'search_updated_at_to' => Yii::t('app', 'Дата изменения (до)'),
            'search_price_from' => Yii::t('app', 'Цена (от)'),
            'search_price_to' => Yii::t('app', 'Цена (до)'),
        ]);
    }
    
    public function search()
    {
        $query = Course::find()
            ->joinWith(['teachers'])
            ->with(['language']);
        
        switch ($this->state) {
            case 'development':
                $query->andFilterWhere(['>', 'course.date_from', date('Y-m-d')]);
                break;
                
            case 'active':
                $query->andFilterWhere([
                    'and',
                    ['<=', 'course.date_from', date('Y-m-d')],
                    ['>=', 'course.date_to', date('Y-m-d')],
                ]);
                break;
                
            case 'outdated':
                $query->andFilterWhere(['<', 'course.date_to', date('Y-m-d')]);
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
            'course.id' => $this->id,
            'course.type' => $this->type,
            'course.language_id' => $this->language_id,
            'course.price' => $this->price,
            'course.demo_time' => $this->demo_time,
            'course.students_start_quantity' => $this->students_start_quantity,
            'user.id' => $this->teachers_tmp,
        ]);
        
        $query->andFilterWhere(['like', 'course.name', $this->name])
            ->andFilterWhere(['like', 'course.slug', $this->slug])
            ->andFilterWhere(['like', 'course.created_at', $this->created_at])
            ->andFilterWhere(['like', 'course.updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['course.date_from' => $this->date_from],
            ['course.date_to' => $this->date_from],
        ]);
        
        $query->andFilterWhere([
            'and',
            [
                'or',
                [
                    'and',
                    ['<=', 'course.date_from', $this->search_date_from ? date('Y-m-d', strtotime($this->search_date_from)) : null],
                    ['>=', 'course.date_to', $this->search_date_from ? date('Y-m-d', strtotime($this->search_date_from)) : null],
                ],
                [
                    'and',
                    ['<=', 'course.date_from', $this->search_date_to ? date('Y-m-d', strtotime($this->search_date_to)) : null],
                    ['>=', 'course.date_to', $this->search_date_to ? date('Y-m-d', strtotime($this->search_date_to)) : null],
                ],
            ],
            ['>=', 'course.updated_at', $this->search_updated_at_from ? date('Y-m-d H:i:s', strtotime($this->search_updated_at_from)) : null],
            ['<=', 'course.updated_at', $this->search_updated_at_to ? date('Y-m-d H:i:s', strtotime($this->search_updated_at_to)) : null],
            ['>=', 'course.price', $this->search_price_from],
            ['<=', 'course.price', $this->search_price_to],
        ]);
        
		return $dataProvider;
    }
}