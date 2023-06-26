<?php

namespace backend\modules\Course\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;


class CoursePackageService extends ActiveService
{
    public function realPrice()
    {
        $percent = ArrayHelper::getValue($this->model, 'activeDiscount.percent', 0);
        return $this->model->price - ($this->model->price * $percent / 100);
    }
    
    public function isActive()
    {
        $conditions_1 = date('Y-m-d', strtotime($this->model->date_from)) <= date('Y-m-d');
        $conditions_2 = date('Y-m-d', strtotime($this->model->date_to)) >= date('Y-m-d');
        return $conditions_1 && $conditions_2;
    }
}