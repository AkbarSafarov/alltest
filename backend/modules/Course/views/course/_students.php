<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<div class="text-sm-end mb-3">
    <?= Html::button(
        Html::tag('i', null, ['class' => 'fas fa-envelope me-2']) . Yii::t('app', 'Отправить сообщение'),
        [
            'class' => 'btn btn-info',
            'data-sr-trigger' => 'ajax-button',
            'data-sr-url' => Yii::$app->urlManager->createUrl([
                'user/message/create',
                'user_search_params' => ArrayHelper::merge($user_search_params, ['model' => 'user']),
            ]),
            'data-sr-wrapper' => '#main-modal',
            'data-sr-callback' => "$('#main-modal').modal('show');",
        ]
    ) ?>
</div>

<table class="table table-bordered table-striped table-relations">
    <thead>
        <tr>
            <th style="width: 50px;">#</th>
            <th><?= Yii::t('app', 'Email') ?></th>
        </tr>
    </thead>
    
    <tbody>
        <?php foreach ($students as $key => $student) { ?>
            <tr>
                <td>
                    <?= $key + 1 ?>
                </td>
                <td>
                    <?= $student->username ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
