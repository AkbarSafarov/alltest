<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\assets\AppAsset;

use backend\modules\Seo\models\SeoMeta;
use backend\modules\Menu\models\Menu;

AppAsset::register($this);

$seo_meta = SeoMeta::find()->andWhere(['model_class' => 'SeoMeta', 'lang' => Yii::$app->language])->one() ?: new SeoMeta();
$seo_meta->registerSeoMeta('global');

$is_home = Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index';
$curr_url = Yii::$app->request->hostInfo . Yii::$app->request->url;

$user = Yii::$app->user->identity;
$menu = Menu::findOne(27)->setJsonAttributes(['name', 'url'])->tree();

$footer_blocks = Yii::$app->services->staticpage->footer['blocks'];
$contact_blocks = Yii::$app->services->staticpage->contact['blocks'];

$header_class = $this->params['header_class'] ?? 'header-login';
$footer_class = $this->params['footer_class'] ?? 'footer footer--reg';

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Yii::$app->services->settings->site_favicon ?>">
    <meta property="og:site_name" content="<?= Yii::$app->services->settings->site_name ?>">
    <meta property="og:url" content="<?= $curr_url ?>">
    <meta property="og:locale" content="<?= Yii::$app->language ?>">
    <meta property="og:type" content="website">
    
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ?></title>
    <?php $this->head() ?>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(85224709, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
    });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/85224709" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <header class="<?= $header_class ?>">
        <?php
            if ($user) {
                echo $this->render('header/student', [
                    'user' => $user,
                    'menu' => $menu,
                ]);
            } else {
                echo $this->render('header/guest', [
                    'menu' => $menu,
                ]);
            }
        ?>
    </header>
    
    <?= $content ?>
    
    <footer class="<?= $footer_class ?>">
        <div class="container">
            <div class="footer__wrapper">
                <div class="logo footer__logo">
                    <img src="<?= Yii::$app->services->settings->site_logo_2 ?>" alt="">
                </div>
                
                <div class="footer__content">
                    <div class="footer__content-inner">
                        <ul class="footer__info">
                            <li>
                                <address class="footer__address">
                                    <?= $contact_blocks->address ?>
                                </address>
                            </li>
                            <li>
                                <a class="footer__mail link-hover" href="mailto:<?= $contact_blocks->email ?>">
                                    <?= $contact_blocks->email ?>
                                </a>
                            </li>
                            <li>
                                <a class="link-hover" href="tel:<?= $contact_blocks->phone ?>">
                                    <?= $contact_blocks->phone ?>
                                </a>
                            </li>
                            <li>
                                <?= $contact_blocks->dev_by ?>
                            </li>
                        </ul>
                        
                        <ul class="footer__menu">
                            <?php foreach ($menu as $m) { ?>
                                <li>
                                    <a class="link-hover" href="<?= $m['url'] ?>">
                                        <?= $m['name'] ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    
                    <div class="footer__socials">
                        <div class="footer__socials-icons">
                            <ul>
                                <?php
                                    foreach (Yii::$app->params['socials'] as $social => $icon) {
                                        if ($link = $contact_blocks->{"social_$social"}) {
                                            echo Html::tag('li', Html::a(null, $link, [
                                                'class' => "link-hover _icon-$icon",
                                                'target' => '_blank',
                                            ]));
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        
                        <div class="footer__socials-partners">
                            <ul>
                                <?php foreach ($footer_blocks->partners as $partner) { ?>
                                    <li>
                                        <a href="<?= Html::encode($partner->link) ?>" target="_blank">
                                            <img src="<?= Html::encode($partner->image) ?>" alt="">
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="footer__bottom">
                    <div class="footer__link">
                        <?= Yii::t('app', 'footer_link_left') ?>
                    </div>
                    <div class="footer__link-policy">
                        <?= Yii::t('app', 'footer_link_right') ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <div class="overlay"></div>
    
    <div class="modal" id="main-modal">
        <div class="modal__wrapper package-course-wrapper">
            <div class="modal__content package-course">
                <div class="modal__body package-course__body">
                    <div class="package-course__top">
                        <button class="package-course__btn modal__btn-close _icon-arrow">
                            <?= Yii::t('app', 'Назад') ?>
                        </button>
                        <button class="package-course__btn-cross modal__btn-close _icon-cross"></button>
                    </div>
                    
                    <div id="main-modal-wrapper"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="main-alert" style="display: none;">
        <?= json_encode(Yii::$app->session->getAllFlashes(), JSON_UNESCAPED_UNICODE) ?>
    </div>
</div>


<script src="https://www.gstatic.com/firebasejs/8.6.8//firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8//firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/ui/4.8.0/firebase-ui-auth__<?= Yii::$app->language != 'uz' ? Yii::$app->language : 'en' ?>.js"></script>
<link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/4.8.0/firebase-ui-auth.css" />

<script type="text/javascript">
    window.onload = function() {
        if ($('#firebaseui-auth-container').length > 0) {
            // Your web app's Firebase configuration
            let firebaseConfig = {
                apiKey: "AIzaSyCFkjM4QgYaEnSGzA-H7OwsYnbOLrZDnFM",
                authDomain: "alltest-social-auth.firebaseapp.com",
                projectId: "alltest-social-auth",
                storageBucket: "alltest-social-auth.appspot.com",
                messagingSenderId: "718756128623",
                appId: "1:718756128623:web:f50394c7a57486c4c0ea9a"
            };
            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);
            
            // FirebaseUI config.
            let uiConfig = {
                signInFlow: 'popup',
                signInOptions: [
                    {
                        provider: firebase.auth.GoogleAuthProvider.PROVIDER_ID,
                        scopes: [
                            'https://www.googleapis.com/auth/userinfo.profile',
                        ]
                    },
                    firebase.auth.FacebookAuthProvider.PROVIDER_ID
                ],
                callbacks: {
                    signInSuccessWithAuthResult: function(authResult, redirectUrl) {
                        let url = '<?= Yii::$app->urlManager->createUrl(['auth/social-login']) ?>';
                            sendData = {
                                provider: authResult.credential.providerId,
                                token: authResult.credential.accessToken,
                            };
                        
                        $.post(url, sendData);
                        
                        return false;
                    },
                    uiShown: function() {
                        
                    }
                }
            };
            // Initialize the FirebaseUI Widget using Firebase.
            let ui = new firebaseui.auth.AuthUI(firebase.auth());
            // The start method will wait until the DOM is loaded.
            ui.start('#firebaseui-auth-container', uiConfig);
        }
    }
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
