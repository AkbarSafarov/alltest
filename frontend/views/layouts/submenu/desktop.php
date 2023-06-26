<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$items = require '_items.php';
$current_route = $current_route ?? [Yii::$app->requestedRoute];

?>

<div class="vertical-menu">
    <div class="vertical-menu__wrapper">
        <nav class="vertical-menu__nav">
            <ul class="vertical-menu__list">
                <?php foreach ($items as $key => $item) { ?>
                    <li>
                        <?= Html::a(null, $item['route'], [
                            'class' => '_icon-' . $item['icon'] . ($item['route'] == $current_route ? ' _active' : null),
                        ]) ?>
                        
                        <div class="vertical-menu__tooltip">
                            <h6 class="vertical-menu__tooltip-title">
                                <?= $item['label'] ?>
                            </h6>
                            <p class="vertical-menu__tooltip-text">
                                <?= Yii::t('app', "submenu_{$key}_description") ?>
                            </p>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            
            <button class="vertical-menu__more-btn _icon-more btn-modal"></button>
        </nav>
        
        <?php if ($course_structure_collapse_button ?? false) { ?>
            <div class="vertical-menu__bottom">
                <button class="vertical-menu__btn _icon-arrow-fill"></button>
                <div class="vertical-menu__tooltip">
                    <p class="vertical-menu__tooltip-text">
                        <?= Yii::t('app', 'Показать/скрыть структуру курса') ?>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>