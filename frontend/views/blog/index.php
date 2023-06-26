<?php

$page->registerSeoMeta();

$this->title = Yii::t('app', 'Новости');
$this->params['header_class'] = 'header';

$pagination = $blogs->pagination;

?>

<main class="main">
    <div class="news">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop') ?>
            
            <div class="container">
                <div class="news__top">
                    <h2 class="news__title">
                        <?= $this->title ?>
                        <span class="title-decor"></span>
                    </h2>
                </div>
                
                <div class="news__items" id="blog-lazy-load-result">
                    <?= $this->render('@frontend/views/particles/blogs', ['models' => $blogs->getModels()]); ?>
                </div>
                
                <?php if ($pagination->pageSize * ($pagination->page + 1) < $pagination->totalCount) { ?>
                    <button type="button"
                       class="news__btn primary-btn"
                       data-toggle="lazy-load-button"
                       data-action="<?= Yii::$app->urlManager->createUrl(['blog/index']) ?>"
                       data-result="#blog-lazy-load-result"
                       data-offset="1"
                       data-offset_max="<?= ceil($pagination->totalCount / $pagination->pageSize) - 1 ?>"
                    >
                        <span class="_icon-arrow">
                            <?= Yii::t('app', 'Больше новостей') ?>
                        </span>
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile') ?>
</main>
