<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<ul class="nav nav-tabs nav-bordered mb-3">
    <?php foreach ($roles as $key => $value) { ?>
        <li class="nav-item">
            <?= Html::a(
                $value['label'],
                ArrayHelper::merge($_GET, ['/' . Yii::$app->requestedRoute, 'UserSearch[role]' => $key]),
                [
                    'class' => 'nav-link ' . ($key == $model->role ? 'active' : null),
                ]
            ) ?>
        </li>
    <?php } ?>
</ul>
