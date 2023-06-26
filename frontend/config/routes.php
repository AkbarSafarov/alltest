<?php

return [
    'login' => 'auth/login',
    'login-as/<id>' => 'auth/login-as',
    'logout-as/<id>' => 'auth/logout-as',
    'signup' => 'auth/signup',
    'reset-password-request' => 'auth/reset-password-request',
    'reset-password/<token>' => 'auth/reset-password',
    
    '/' => 'site/index',
    'home' => 'site/home',
    'contact' => 'site/contact',
    'faq' => 'site/faq',
    'page/<slug>' => 'site/page',
    
    'news' => 'blog/index',
    'news/<slug>' => 'blog/view',
    
    'courses' => 'course/courses',
    'course/view/<slug>' => 'course/course-view',
    'course-packages' => 'course/packages',
    
    'cart' => 'cart/index',
    'order/view/<key>' => 'order/view',
    
    'profile/<nickname>' => 'user/profile/view',
    'profile' => 'user/profile/view',
    'user/courses' => 'user/course/index',
];