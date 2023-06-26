<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$page->registerSeoMeta();

$this->title = Yii::t('app', 'Поиск');

$course_models = $courses->getModels();
$pagination = $courses->pagination;

?>

<main class="main has-bg">
    <div class="catalogue">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['course/courses']]) ?>
            
            <div class="container">
                <div class="search__title">
                    <?= $course_models ? Yii::t('app', 'Найдено {count} вариантов', ['count' => $courses->count]) : null ?>
                </div>
                
                <?php if ($course_models) { ?>
                    <div class="catalogue__content">
                        <div class="catalogue__section submenu">
                            <ul class="catalogue__items" id="courses-lazy-load-result">
                                <?= $this->render('@frontend/views/particles/courses', ['models' => $course_models]); ?>
                            </ul>
                            
                            <?php if ($pagination->pageSize * ($pagination->page + 1) < $pagination->totalCount) { ?>
                                <button type="button"
                                   class="news__btn primary-btn"
                                   data-toggle="lazy-load-button"
                                   data-action="<?= Yii::$app->urlManager->createUrl([
                                        'course/courses-search',
                                        'search' => Yii::$app->request->get('search'),
                                    ]) ?>"
                                   data-result="#courses-lazy-load-result"
                                   data-offset="1"
                                   data-offset_max="<?= ceil($pagination->totalCount / $pagination->pageSize) - 1 ?>"
                                >
                                    <span class="_icon-arrow">
                                        <?= Yii::t('app', 'Больше курсов') ?>
                                    </span>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="search-error">
                        <h3 class="search-error__title">
                            <?= Yii::t('app', 'Извините, но по вашему запросу ничего не найдено') ?>
                        </h3>
                        
                        <div class="search-error__img">
                            <img src="/img/content/not-found/courses.png" alt="">
                        </div>
                        <div class="search-error__text">
                            <?= Yii::t('app', 'Может быть вам поискать что-то ещё?') ?>
                        </div>
                        <a class="search-error__btn primary-btn" href="<?= Yii::$app->urlManager->createUrl(['course/courses']) ?>">
                            <?= Yii::t('app', 'Посмотреть каталог') ?>
                        </a>
                    </div> 
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['course/courses']]) ?>
</main>
