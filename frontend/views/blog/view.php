<?php

$model->registerSeoMeta();

$this->title = $model->name;
$this->params['header_class'] = 'header';

?>

<main class="main">
    <div class="publication">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['blog/index']]) ?>
            
            <div class="container">
                <h2 class="publication__title">
                    <?= $model->name ?>
                </h2>
                
                <div class="publication__date">
                    <?= date('d.m.Y', strtotime($model->published_at_from)) ?>
                </div>
                
                <div class="publication__content">
                    <?= $model->full_description ?>
                </div>
                
                <div class="read-more">
                    <div class="container">
                        <div class="read-more__wrapper">
                            <h2 class="read-more__title-main">
                                <?= Yii::t('app', 'Читайте также') ?>
                                <span class="title-decor"></span>
                            </h2>
                            
                            <div class="read-more__container swiper-container" data-mobile="false">
                                <div class="read-more__articles swiper-wrapper">
                                    <?= $this->render('@frontend/views/particles/blogs', ['models' => $related]); ?>
                                </div>
                            </div>
                            
                            <div class="read-more__link">
                                <a href="<?= Yii::$app->urlManager->createUrl(['blog/index']) ?>" class="primary-btn read-more__link-btn">
                                    <span class="_icon-arrow">
                                        <?= Yii::t('app', 'Все новости') ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['blog/index']]) ?>
</main>
