<?php

use yii\helpers\Html;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$condition_1 = $controller == 'site' && $action == 'index';
$condition_2 = $controller == 'auth';

?>

<?php if (!$condition_1 && !$condition_2) { ?>
    <div class="header-login__input">
        <div class="input">
            <?= Html::beginForm(['course/courses-search'], 'get') ?>
            <?= Html::textInput('search', Yii::$app->request->get('search'), ['placeholder' => Yii::t('app', 'Введите запрос...')]) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
<?php } ?>
