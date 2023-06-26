<?php foreach ($models as $model) { ?>
    <a href="<?= Yii::$app->urlManager->createUrl(['blog/view', 'slug' => $model->slug]) ?>" class="read-more__article swiper-slide">
        <div class="read-more__date">
            <?= date('d.m.Y', strtotime($model->published_at_from)) ?>
        </div>
        
        <h5 class="read-more__title title-item">
            <?= $model->name ?>
        </h5>
        
        <div class="read-more__img">
            <img src="<?= Yii::$app->services->image->thumb($model->image, [370, 180]) ?>" alt="">
        </div>
        
        <p class="read-more__info">
            <?= nl2br($model->short_description) ?>
        </p>
    </a>
<?php } ?>