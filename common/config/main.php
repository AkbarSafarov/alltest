<?php

$app = explode('/', $_SERVER['SCRIPT_NAME'])[1] ?? null;
$routes_file = __DIR__ . "/../../$app/config/routes.php";
$routes = $app && file_exists($routes_file) ? require $routes_file : [];

return [
    'timeZone' => 'Asia/Tashkent',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => ['i18n', 'queue'],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Asia/Tashkent',
            'dateFormat' => 'php: d.m.Y',
            'datetimeFormat' => 'php: d.m.Y H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'speedrunner\i18n\DbMessageSource',
                    'sourceMessageTable' => 'translation_source',
                    'messageTable' => 'translation_message',
                ],
                'yii2*' => [
                    'class' => 'speedrunner\i18n\DbMessageSource',
                    'sourceMessageTable' => 'translation_source',
                    'messageTable' => 'translation_message',
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],

        //        URL managers

        'urlManager' => [
            'class' => 'speedrunner\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $routes,
        ],
        'urlManagerBackend' => [
            'class' => 'speedrunner\web\UrlManager',
            'baseUrl' => '/admin',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/../../backend/config/routes.php',
        ],
        'urlManagerFrontend' => [
            'class' => 'speedrunner\web\UrlManager',
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/../../frontend/config/routes.php',
        ],
        
        //        Services
        
        'services' => [
            'class' => 'speedrunner\bootstrap\Components',
            'components' => [
                'array' => 'speedrunner\services\ArrayService',
                'file' => 'speedrunner\services\FileService',
                'html' => 'speedrunner\services\HtmlService',
                'i18n' => 'speedrunner\services\I18NService',
                'image' => 'speedrunner\services\ImageService',
                'mail' => 'speedrunner\services\MailService',
                'permissions' => 'speedrunner\services\PermissionsService',
                'string' => 'speedrunner\services\StringService',
                
                'cart' => 'backend\modules\User\services\UserCartService',
                'moderationStatus' => 'backend\modules\Moderation\services\ModerationStatusService',
                'notification' => 'backend\modules\User\services\UserNotificationService',
                'settings' => 'backend\modules\System\services\SystemSettingsService',
                'staticpage' => 'backend\modules\Staticpage\services\StaticpageService',
                'trash' => 'backend\modules\Trash\services\TrashService',
            ],
        ],
    ],
];
