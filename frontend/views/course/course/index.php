<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$page->registerSeoMeta();

$this->title = Yii::t('app', 'Каталог курсов');

?>

<main class="main has-bg">
    <div class="catalogue">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop') ?>
            
            <div class="container">
                <?= Html::beginForm(false, 'get') ?>
                
                <div class="catalogue__top">
                    <h3 class="catalogue__title">
                        <?= $this->title ?>
                    </h3>
                    
                    <div class="catalogue__dropdown">
                        <?= Html::dropDownList('type', Yii::$app->request->get('type'), ArrayHelper::getColumn($types, 'group_name'), [
                            'prompt' => Yii::t('app', 'Все типы курсов'),
                            'class' => 'select-dropdown',
                            'onchange' => "$(this).closest('form').trigger('submit');",
                        ]) ?>
                    </div>
                    
                    <div class="catalogue__dropdown">
                        <?= Html::dropDownList('language', Yii::$app->request->get('language'), $languages, [
                            'prompt' => Yii::t('app', 'На всех языках'),
                            'class' => 'select-dropdown',
                            'onchange' => "$(this).closest('form').trigger('submit');",
                        ]) ?>
                    </div>
                    
                    <div class="catalogue__dropdown">
                        <?= Html::dropDownList('author', Yii::$app->request->get('author'), $authors, [
                            'prompt' => Yii::t('app', 'Все авторы'),
                            'class' => 'select-dropdown',
                            'onchange' => "$(this).closest('form').trigger('submit');",
                        ]) ?>
                    </div>
                </div>
                
                <?= Html::endForm() ?>
                
                <?php if ($course_groups['linear']->totalCount || $course_groups['exam']->totalCount) { ?>
                    <div class="catalogue__content">
                        <?php foreach ($course_groups as $key => $courses) { ?>
                            <?php if ($course_models = $courses->getModels()) { ?>
                                <?php $pagination = $courses->pagination ?>
                                
                                <div class="catalogue__section submenu">
                                    <button class="catalogue__item-btn _icon-arrow">
                                        <?= ArrayHelper::getValue($types, "$key.group_name") ?>
                                    </button>
                                    
                                    <div class="catalogue__section-block">
                                        <ul class="catalogue__items" id="courses-<?= $key ?>-lazy-load-result">
                                            <?= $this->render('@frontend/views/particles/courses', ['models' => $course_models]); ?>
                                        </ul>
                                        
                                        <?php if ($pagination->pageSize * ($pagination->page + 1) < $pagination->totalCount) { ?>
                                            <button type="button"
                                                    class="catalogue__section-btn primary-btn"
                                                    data-toggle="lazy-load-button"
                                                    data-action="<?= Yii::$app->urlManager->createUrl([
                                                        'course/courses',
                                                        'type' => $key,
                                                        'language' => Yii::$app->request->get('language'),
                                                        'author' => Yii::$app->request->get('author'),
                                                    ]) ?>"
                                                    data-result="#courses-<?= $key ?>-lazy-load-result"
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
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="search-error">
                        <h3 class="search-error__title">
                            <?= Yii::t('app', 'Ничего не найдено') ?>
                        </h3>
                        
                        <div class="search-error__img">
                            <img src="/img/content/not-found/courses.png" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile') ?>
</main>
