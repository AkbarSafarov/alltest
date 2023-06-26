<div class="account__item">
    <h5 class="account__subtitle">
        <?= Yii::t('app', 'Сертификаты') ?>
    </h5>
    
    <div class="account__certificates-container swiper-container" data-mobile="false">
        <ul class="account__certificates swiper-wrapper">
            <?php foreach ($certificates as $certificate) { ?>
                <li class="swiper-slide">
                    <a class="account__certificates-item" href="<?= $certificate ?>" data-fancybox="certificates">
                        <div class="account__certificates-img">
                            <iframe src="<?= $certificate ?>" frameborder="0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    
    <div class="account__buttons">
        <button class="tertinary-btn account__buttons-prev _icon-arrow swiper-button-prev"></button>
        <button class="tertinary-btn account__buttons-next _icon-arrow swiper-button-next"></button>
    </div>
</div>
