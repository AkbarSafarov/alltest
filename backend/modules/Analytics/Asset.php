<?php

namespace backend\modules\Analytics;

use Yii;
use yii\web\AssetBundle;
use yii\helpers\ArrayHelper;


class Asset extends AssetBundle
{
    public $sourcePath = '@backend/modules/Analytics/assets';
    
    public $css = [
        'css/chart.css',
    ];
    
    public $js = [
        'js/chart.js',
    ];
}
