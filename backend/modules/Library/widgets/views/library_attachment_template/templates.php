<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<div class="row m-0">
    <?php foreach ($templates as $template) { ?>
        <div class="col-md-4 p-2">
            <?= Html::beginTag('a', [
                'href' => '#',
                'class' => 'card text-decoration-none',
                'data-dismiss' => 'modal',
                'data-sr-trigger' => 'ajax-button',
                'data-sr-url' => Yii::$app->urlManager->createUrl([
                    'library/attachment-template/add', 'id' => $template->id,
                ]),
                'data-sr-wrapper' => '.template-templates-wrapper',
                'data-sr-insert-type' => 'append',
            ]) ?>
            
            <div class="card-header bg-primary text-white text-center">
                <?= $template->name ?>
            </div>
            
            <div class="card-body text-center p-5">
                <?= Html::img(Yii::$app->services->image->thumb($template->image, [200, 200], 'resize'), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
            
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
</div>
