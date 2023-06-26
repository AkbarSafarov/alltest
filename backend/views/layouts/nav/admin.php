<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

return [
    ['template' => Html::tag('li', Yii::t('app_menu', 'Меню'), ['class' => 'menu-title'])],
    [
        'label' => Yii::t('app_menu', 'Курсы'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-bookshelf']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Курсы'), 'url' => ['/course/course/index']],
            ['label' => Yii::t('app_menu', 'Пакеты курсов'), 'url' => ['/course/package/index']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'Тесты'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-format-list-checks']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Тесты'), 'url' => ['/library/test/index']],
            ['label' => Yii::t('app_menu', 'Предметы'), 'url' => ['/library/test-subject/index']],
            ['label' => Yii::t('app_menu', 'Темы предметов'), 'url' => ['/library/test-category/index']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'Контент'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-text-box-multiple']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Новости'), 'url' => ['/blog/blog/index']],
            [
                'label' => Yii::t('app_menu', $staticpages['home']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'home'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'home'),
            ],
            [
                'label' => Yii::t('app_menu', $staticpages['faq']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'faq'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'faq'),
            ],
            [
                'label' => Yii::t('app_menu', $staticpages['contact']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'contact'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'contact'),
            ],
            ['label' => Yii::t('app_menu', 'Прочие страницы'), 'url' => ['/page/page/index']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'Маркетинг'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-cart']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Скидки'), 'url' => ['/order/discount/index']],
            ['label' => Yii::t('app_menu', 'Промокоды'), 'url' => ['/order/promocode/index']],
            ['label' => Yii::t('app_menu', 'Лиги'), 'url' => ['/reward/league/index']],
            ['label' => Yii::t('app_menu', 'Достижения'), 'url' => ['/reward/achievement/index']],
            ['label' => Yii::t('app_menu', 'Аналитика'), 'url' => ['/analytics/chart/orders']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'Пользователи'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-account-group']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Пользователи'), 'url' => ['/user/user/index']],
            ['label' => Yii::t('app_menu', 'Подписчики'), 'url' => ['/user/user/subscribers']],
            ['label' => Yii::t('app_menu', 'Заказы'), 'url' => ['/order/order/index']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'Библиотека'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-image-multiple']),
        'items' => [
            ['template' => Html::tag(
                'div',
                Html::a(Yii::t('app_menu', 'Файловый менеджер'), 'javascript:void(0)', ['class' => 'yii2-elfinder-select-button']),
                ['data-toggle' => 'elfinder']
            )],
            ['label' => Yii::t('app_menu', 'Шаблоны'), 'url' => ['/library/template/index']],
            ['label' => Yii::t('app_menu', 'Категории шаблонов'), 'url' => ['/library/template-category/index']],
        ],
    ],
    
    [
        'label' => Yii::t('app_menu', 'Система'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-cog']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Настройки'),'url' => ['/system/settings/update']],
            ['label' => Yii::t('app_menu', 'Языки'), 'url' => ['/system/language/index']],
            ['label' => Yii::t('app_menu', 'Переводы'), 'url' => ['/translation/source/index']],
            ['label' => Yii::t('app_menu', 'Меню'), 'url' => ['/menu/menu/tree']],
            [
                'label' => Yii::t('app_menu', $staticpages['footer']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'footer'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'footer'),
            ],
            ['label' => Yii::t('app_menu', 'Типы юнитов'), 'url' => ['/course/unit-type/index']],
        ],
    ],
    [
        'label' => Yii::t('app_menu', 'SEO'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-search-web']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Мета'), 'url' => ['/seo/meta/update']],
            ['label' => Yii::t('app_menu', 'Файлы'), 'url' => ['/seo/file/update']],
            [
                'label' => Yii::t('app_menu', $staticpages['auth']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'auth'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'auth'),
            ],
            [
                'label' => Yii::t('app_menu', $staticpages['courses']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'courses'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'courses'),
            ],
            [
                'label' => Yii::t('app_menu', $staticpages['course_packages']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'course_packages'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'course_packages'),
            ],
            [
                'label' => Yii::t('app_menu', $staticpages['blog']['label']),
                'url' => ['/staticpage/staticpage/update', 'name' => 'blog'],
                'active' => (Yii::$app->controller->uniqueId == 'staticpage/staticpage') && (Yii::$app->request->get('name') == 'blog'),
            ],
        ],
    ],
    
    [
        'label' => Yii::t('app_menu', 'Кэш'),
        'icon' => Html::tag('i', null, ['class' => 'mdi mdi-delete']),
        'items' => [
            ['label' => Yii::t('app_menu', 'Очистить'), 'url' => ['/cache/clear']],
        ],
    ],
];
