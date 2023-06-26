<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;
use yii\web\AssetManager;
use backend\assets\AppAsset;
use kartik\file\FileInputAsset;

use backend\widgets\layout\Menu;
use backend\modules\User\models\UserNotification;
use backend\modules\Staticpage\models\Staticpage;

AppAsset::register($this);
FileInputAsset::register($this);

$staticpages = Staticpage::find()->indexBy('name')->asArray()->all();
$user = Yii::$app->user->identity;
$langs = Yii::$app->services->i18n::$languages;
$notifications = UserNotification::find()->andWhere(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->limit(10)->all();

$tinymce = Yii::createObject(['class' => 'alexantr\tinymce\TinyMCE', 'name' => false]);
$tinymce->run();

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Yii::$app->services->settings->site_favicon ?>">
    
    <meta name="sidebar-size" content="<?= Yii::$app->session->get('nav') ? 'condensed' : 'default' ?>">
    <meta name="elfinder-connection-url" content="<?= Yii::$app->urlManager->createUrl(['connection/elfinder-input']) ?>">
    <meta name="tinymce-base-url" content="<?= (new AssetManager())->getBundle('\alexantr\tinymce\WidgetAsset')->baseUrl . '/' ?>">
    <meta name="tinymce-file-picker-connection-url" content="<?= Yii::$app->urlManager->createUrl(['connection/tinymce']) ?>">
    <meta name="tinymce-image-upload-connection-url" content="<?= Yii::$app->urlManager->createUrl(['connection/tinymce-image-upload']) ?>">
    <meta name="tinymce-params" content="<?= Html::encode(json_encode($tinymce->clientOptions, JSON_UNESCAPED_UNICODE)) ?>">
    
    <meta name="tests-validation-text" content="<?= Yii::t('app', 'Вы должны выбрать правильный ответ') ?>">
    
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="loading">
<?php $this->beginBody() ?>

<div id="wrapper">
    <!-- Topbar Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
            <ul class="list-unstyled topnav-menu float-end mb-0">
                <li class="dropdown d-none d-lg-inline-block topbar-dropdown">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?= Yii::$app->services->image->thumb($langs[Yii::$app->language]['image'], [24, 16]) ?>" alt="user-image" height="16">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <?php foreach ($langs as $l) { ?>
                            <a href="<?= $l['url'] ?>" class="dropdown-item">
                                <img src="<?= Yii::$app->services->image->thumb($l['image'], [18, 12]) ?>" alt="user-image" class="me-1" height="12">
                                <span class="align-middle">
                                    <?= $l['name'] ?>
                                </span>
                            </a>
                        <?php } ?>
                    </div>
                </li>
                
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-bell noti-icon"></i>
                        <?php if ($notifications) { ?>
                            <span class="badge bg-danger rounded-circle noti-icon-badge">
                                <?= count($notifications) ?>
                            </span>
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end">
                                    <a href="<?= Yii::$app->urlManager->createUrl(['/user/notification/clear']) ?>" class="text-dark">
                                        <small><?= Yii::t('app', 'Очистить') ?></small>
                                    </a>
                                </span>
                                <?= Yii::t('app', 'Уведомления') ?>
                            </h5>
                        </div>
                        <div class="noti-scroll" data-simplebar>
                            <?php
                                if ($notifications) {
                                    foreach ($notifications as $key => $n) {
                                        echo Html::a(
                                            Html::tag('i', null, ['class' => 'mdi mdi-alert-circle-outline me-2'])
                                            . ArrayHelper::getValue($n->service->actionData(), 'label'),
                                            ['/user/notification/view', 'id' => $n->id],
                                            [
                                                'class' => 'dropdown-item notify-item',
                                            ]
                                        );
                                    }
                                } else {
                                    echo Html::tag('div', Yii::t('app', 'Нет уведомлений'), ['class' => 'dropdown-item notify-item']);
                                }
                            ?>
                        </div>
                    </div>
                </li>
                
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?= Yii::$app->services->image->thumb($user->image, [32, 32]) ?>" alt="user-image" class="rounded-circle">
                        <span class="pro-user-name ms-1">
                            <?= $user->username ?>
                            <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">
                                <?= $user->full_name ?>
                            </h6>
                        </div>
                        
                        <?= Html::a(
                            Html::tag('i', '&nbsp;', ['class' => 'fe-user']) . Yii::t('app', 'Редактирование профиля'),
                            ['/user/user/profile-update'],
                            ['class' => 'dropdown-item notify-item']
                        ) ?>
                        
                        <?= Html::a(
                            Html::tag('i', '&nbsp;', ['class' => 'fe-log-out']) . Yii::t('app', 'Выход'),
                            ['/auth/logout'],
                            ['class' => 'dropdown-item notify-item', 'data-method' => 'POST']
                        ) ?>
                    </div>
                </li>
            </ul>
            
            <div class="logo-box">
                <a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>" class="logo logo-light text-center" style="filter: contrast(5);">
                    <span class="logo-sm">
                        <img src="<?= Yii::$app->services->settings->site_favicon ?>" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= Yii::$app->services->settings->site_logo_2 ?>" alt="" height="20">
                    </span>
                </a>
            </div>
            
            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <?= Html::button(Html::tag('i', null, ['class' => 'fe-menu']), [
                        'class' => 'button-menu-mobile waves-effect waves-light',
                        'data-sr-trigger' => 'toggle_session',
                        'data-sr-url' => Yii::$app->urlManager->createUrl(['session/set']),
                        'data-sr-name' => 'nav',
                        'data-sr-callback' => "$('.nav-wrapper-side').toggleClass('opened');",
                    ]) ?>
                </li>
                
                <li>
                    <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                </li>
            </ul>
            
            <div class="clearfix"></div>
        </div>
    </div>
    
    <div class="left-side-menu">
        <div class="h-100" data-simplebar>
            <div id="sidebar-menu">
                <?= Menu::widget([
                    'items' => require 'nav/' . Yii::$app->user->identity->role . '.php',
                    'options' => ['id' => 'side-menu'],
                    'labelTemplate' => '<a data-bs-toggle="collapse" href="#">
                        {label}
                        <span class="menu-arrow"></span>
                    </a>',
                    'submenuTemplate' => '<div class="collapse"><ul class="nav-second-level">{items}</ul></div>',
                    'encodeLabels' => false,
                    'activateParents' => true,
                ]); ?>
            </div>
        </div>
    </div>
    
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <?= Breadcrumbs::widget([
                                    'links' => $this->params['breadcrumbs'] ?? [],
                                    'homeLink' => ['label' => Yii::t('app', 'Главная'), 'url' => ['/']],
                                    'options' => ['tag' => 'ol', 'class' => 'breadcrumb m-0'],
                                    'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                                    'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>',
                                ]) ?>
                            </div>
                            <h4 class="page-title">
                                <?= $this->title ?>
                            </h4>
                        </div>
                    </div>
                </div>
                
                <div id="content-wrapper">
                    <?= $content ?>
                </div>
            </div>
        </div>
        
        <footer class="footer">
            <div class="container-fluid">
                <?= date('Y') . ' &copy; ' . Yii::$app->services->settings->site_name ?>
            </div>
        </footer>
    </div>
</div>

<div id="main-alert">
    <?= json_encode(Yii::$app->session->getAllFlashes(), JSON_UNESCAPED_UNICODE) ?>
</div>

<div class="modal fade" id="main-modal">
    <div class="modal-dialog"></div>
</div>

<div class="modal" id="password-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <?= Yii::t('app', 'Пожалуйста введите пароль') ?>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <?= Html::beginForm(['/user/user/check-password'], 'post', [
                    'id' => 'password-form',
                    'data-sr-trigger' => 'ajax-form',
                    'data-trigger' => 'delete-confirmation-form',
                    'data-sr-callback' => "el.data('password-hash', data); el.trigger('deleteRecord');",
                ]) ?>
                
                <?= Html::passwordInput('password', null, ['class' => 'form-control']) ?>
                <?= Html::endForm() ?>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="password-form" class="btn btn-primary float-right">
                    <?= Yii::t('app', 'OK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="redactor-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <?= Yii::t('app', 'Редактирование') ?>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <?= Html::textArea(null, null, [
                    'id' => 'redactor-modal-input',
                ]) ?>
            </div>
            
            <div class="modal-footer">
                <button type="button" id="redactor-modal-button" class="btn btn-primary btn-block">
                    <?= Yii::t('app', 'Применить') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="upload-progress-bar" class="d-none">
    <h5 class="bg-light p-2 m-0">
        <?= Yii::t('app', 'Загрузка файла') ?>
    </h5>
    <div class="progress">
        <div class="progress-bar"></div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>