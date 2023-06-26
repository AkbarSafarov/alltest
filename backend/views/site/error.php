<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Произошла ошибка');

?>

<body class="loading authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <div class="auth-logo">
                                <a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="<?= Yii::$app->services->settings->site_logo_1 ?>" alt="" height="22">
                                    </span>
                                </a>
                            </div>
                            
                            <div class="text-center mt-4">
                                <h1 class="text-error">
                                    <?= $exception->statusCode ?: 500 ?>
                                </h1>
                                <h3 class="mt-3 mb-2">
                                    <?= $this->title ?>
                                </h3>
                                <p class="text-muted mb-3">
                                    <?= nl2br(Html::encode($message)) ?>
                                </p>
                                
                                <?= Html::a(Yii::t('app', 'На главную'), ['site/index'], [
                                    'class' => 'btn btn-primary waves-effect waves-light',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="footer footer-alt">
        <?= date('Y') . ' &copy; ' . Yii::$app->services->settings->site_name ?>
    </footer>
</body>
