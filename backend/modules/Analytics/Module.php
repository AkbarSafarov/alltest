<?php

namespace backend\modules\Analytics;


class Module extends \yii\base\Module
{
    public $defaultRoute = 'chart/orders';
    
    public function init()
    {
        Asset::register(\Yii::$app->view);
        return parent::init();
    }
}
