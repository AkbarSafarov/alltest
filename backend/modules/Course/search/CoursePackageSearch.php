<?php

namespace backend\modules\Course\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\Course\models\CoursePackage;


class CoursePackageSearch extends CoursePackage
{
    use \backend\modules\Trash\traits\TrashStateTrait;
    
    public function rules()
    {
        return [
            [['id', 'price', 'language_id', 'students_start_quantity', 'courses_tmp'], 'integer'],
            [['name', 'date_from', 'date_to', 'created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function search()
    {
        $query = CoursePackage::find()
            ->joinWith(['courses'])
            ->with(['language'])
            ->select(['course_package.*'])
            ->groupBy('id');
        
        switch ($this->state) {
            case 'active':
                $query->andFilterWhere(['>=', 'course_package.date_to', date('Y-m-d')]);
                break;
                
            case 'outdated':
                $query->andFilterWhere(['<', 'course_package.date_to', date('Y-m-d')]);
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
            'course_package.id' => $this->id,
            'course_package.price' => $this->price,
            'course_package.language_id' => $this->language_id,
            'course_package.students_start_quantity' => $this->students_start_quantity,
            'course.id' => $this->courses_tmp,
        ]);

        $query->andFilterWhere(['like', 'course_package.name', $this->name])
            ->andFilterWhere(['like', 'course_package.created_at', $this->created_at])
            ->andFilterWhere(['like', 'course_package.updated_at', $this->updated_at]);
        
        $query->andFilterWhere([
            'or',
            ['course_package.date_from' => $this->date_from],
            ['course_package.date_to' => $this->date_from],
        ]);
        
		return $dataProvider;
    }
}