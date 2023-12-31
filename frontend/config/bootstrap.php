<?php

Yii::$container->set('yii\bootstrap\ActiveField', [
    'options' => ['class' => 'input-item'],
    'inputOptions' => ['class' => false],
    'labelOptions' => ['class' => 'input-item__label'],
    'hintOptions' => ['class' => 'input-item__subtext'],
    'template' => '<div class="input-item__inner">{label}<div class="input">{input}</div></div>{hint}{error}',
    'checkboxTemplate' => '<div class="custom-control custom-switch">{input}{beginLabel}{labelTitle}{endLabel}{hint}{error}</div>',
]);

Yii::$container->set('yii\widgets\LinkPager', [
    'options' => ['class' => 'pagination'],
    'linkContainerOptions' => ['class' => 'page-item'],
    'linkOptions' => ['class' => 'page-link'],
    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
    'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
    'prevPageLabel' => '<i class="fas fa-angle-left"></i>',
    'nextPageLabel' => '<i class="fas fa-angle-right"></i>',
    'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
]);
