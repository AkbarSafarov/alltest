<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;
use yii\helpers\ArrayHelper;


class AppAsset extends AssetBundle
{
    public $baseUrl = '@web';
    
    public $css = [
        'libs/flatpickr/flatpickr.min.css',
        'libs/select2/css/select2.min.css',
        'libs/jquery-toast-plugin/jquery.toast.min.css',
        
        'css/bootstrap.min.css',
        'css/app.min.css',
        'css/icons.min.css',
        
        'css/re-style.css',
        'css/last-style.css',
    ];
    
    public $js = [
        'libs/flatpickr/flatpickr.min.js',
        'libs/select2/js/select2.min.js',
        'libs/jquery-toast-plugin/jquery.toast.min.js',
        
        'js/app.min.js',
        
        'js/sr-triggers.js',
        'js/re-script.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
