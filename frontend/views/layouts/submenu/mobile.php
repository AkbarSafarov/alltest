<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$items = require '_items.php';

$this->params['footer_class'] = 'footer';

?>

<div class="modal">
    <div class="modal__wrapper modal__wrapper--bottom">
        <div class="modal__content vertical-menu-mobile">
            <div class="modal__body vertical-menu-mobile__body">
                <div class="vertical-menu-mobile__top">
                    <h6 class="vertical-menu-mobile__title">
                        <?= Yii::t('app', 'Меню') ?>
                    </h6>
                    <button class="vertical-menu-mobile__btn-cross modal__btn-close _icon-cross"></button>
                </div>
                
                <ul class="vertical-menu-mobile__list">
                    <?php foreach ($items as $item) { ?>
                        <li>
                            <?= Html::a($item['label'], $item['route'], [
                                'class' => '_icon-' . $item['icon'] . (' _active'),
                            ]) ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>