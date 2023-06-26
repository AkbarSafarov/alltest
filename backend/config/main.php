<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/admin',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'analytics' => ['class' => 'backend\modules\Analytics\Module'],
        'blog' => ['class' => 'backend\modules\Blog\Module'],
        'course' => ['class' => 'backend\modules\Course\Module'],
        'integration' => ['class' => 'backend\modules\Integration\Module'],
        'library' => ['class' => 'backend\modules\Library\Module'],
        'menu' => ['class' => 'backend\modules\Menu\Module'],
        'moderation' => ['class' => 'backend\modules\Moderation\Module'],
        'order' => ['class' => 'backend\modules\Order\Module'],
        'page' => ['class' => 'backend\modules\Page\Module'],
        'reward' => ['class' => 'backend\modules\Reward\Module'],
        'seo' => ['class' => 'backend\modules\Seo\Module'],
        'staticpage' => ['class' => 'backend\modules\Staticpage\Module'],
        'system' => ['class' => 'backend\modules\System\Module'],
        'trash' => ['class' => 'backend\modules\Trash\Module'],
        'translation' => ['class' => 'backend\modules\Translation\Module'],
        'user' => ['class' => 'backend\modules\User\Module'],
        
        'rbac' => ['class' => 'yii2mod\rbac\Module'],
    ],
    'as access' => [
        'class' => 'yii2mod\rbac\filters\AccessControl',
        'allowActions' => [
            'auth/login',
            'auth/logout',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
            'cookieValidationKey' => 'KkYlBx33UD4_1j2utpYAEVmxSb3oXbRr',
        ],
        'user' => [
            'identityClass' => 'backend\modules\User\models\User',
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['auth/login'],
            'enableAutoLogin' => true,
        ],
        'session' => [
            'name' => 'advanced-backend',
            'class' => 'yii\web\DbSession',
            'timeout' => 3600 * 24 * 30,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 4 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/vendor.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
            ],
        ],
    ],
    'params' => $params,
];
