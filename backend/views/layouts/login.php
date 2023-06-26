<?php

use yii\helpers\Html;
use backend\assets\AppAsset;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;

AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Yii::$app->services->settings->site_favicon ?>">
    
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="loading authentication-bg authentication-bg-pattern">
<?php $this->beginBody() ?>

<?= $content ?>

<div id="main-alert">
    <?= json_encode(Yii::$app->session->getAllFlashes(), JSON_UNESCAPED_UNICODE) ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
