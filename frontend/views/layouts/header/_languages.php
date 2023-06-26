<?php

use yii\helpers\Html;

$langs = Yii::$app->services->i18n::$languages;

?>

<?php if (count($langs) > 1) { ?>
    <div class="header-login__dropdown">
        <div class="dropdown-language dropdown--wrapper">
            <button class="dropdown-language__btn _icon-arrow dropdown--btn dropdown--btn-arrow">
                <?= strtoupper(Yii::$app->language) ?>
            </button>
            
            <ul class="dropdown-language__list dropdown--content">
                <?php foreach ($langs as $key => $l) { ?>
                    <li>
                        <a class="dropdown-language__item <?= $key == Yii::$app->language ? '_active' : null ?>" href="<?= $l['url'] ?>">
                            <?= strtoupper($key) ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>
