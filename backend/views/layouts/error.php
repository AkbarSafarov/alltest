<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\assets\AppAsset;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

//-----------------------------------------------------------------------------------

$breadcrumbs = ArrayHelper::getValue($this->params, 'breadcrumbs', []);

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

<body>
<?php $this->beginBody() ?>

 <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
