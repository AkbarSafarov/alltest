<?php

return [
    'user_courses' => [
        'label' => Yii::t('app', 'Курсы пользователя'),
        'route' => ['user/course/index'],
        'icon' => 'user',
    ],
    'courses' => [
        'label' => Yii::t('app', 'Все курсы'),
        'route' => ['course/courses'],
        'icon' => 'computer',
    ],
    'course_packages' => [
        'label' => Yii::t('app', 'Пакеты курсов'),
        'route' => ['course/packages'],
        'icon' => 'box',
    ],
    'profile' => [
        'label' => Yii::t('app', 'Аккаунт'),
        'route' => ['user/profile/view'],
        'icon' => 'setting',
    ],
    'bookmarks' => [
        'label' => Yii::t('app', 'Закладки'),
        'route' => ['user/bookmark/view'],
        'icon' => 'bookmark',
    ],
    'about' => [
        'label' => Yii::t('app', 'О проекте'),
        'route' => Yii::$app->user->isGuest ? ['site/index'] : ['site/home'],
        'icon' => 'information',
    ],
    'news' => [
        'label' => Yii::t('app', 'Новости'),
        'route' => ['blog/index'],
        'icon' => 'menu-newspaper',
    ],
    'cart' => [
        'label' => Yii::t('app', 'Корзина'),
        'route' => ['cart/index'],
        'icon' => 'cart',
    ],
];
