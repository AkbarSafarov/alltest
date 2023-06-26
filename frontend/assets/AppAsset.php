<?php

namespace frontend\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $baseUrl = '@web';
    
    public $css = [
        'css/libs.min.css',
        'css/style.min.css',
        'css/jquery.toast.min.css',
        'css/re-style.css',
    ];
    
    public $js = [
        'js/app.min.js',
        'js/jquery.toast.min.js',
        
        'admin/js/sr-triggers.js',
        'js/re-script.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
