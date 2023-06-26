<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$page->registerSeoMeta();

$this->title = Yii::t('app', 'Каталог пакетов курсов');

$pagination = $packages->pagination;

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
                        <?= Html::dropDownList('language', Yii::$app->request->get('language'), $languages, [
                            'prompt' => Yii::t('app', 'Язык'),
                            'class' => 'select-dropdown',
                            'onchange' => "$(this).closest('form').trigger('submit');",
                        ]) ?>
                    </div>
                </div>
                
                <?= Html::endForm() ?>
                
                <?php if ($packages->totalCount) { ?>
                    <div class="catalogue__content">
                        <div class="catalogue__section submenu">
                            <ul class="catalogue__items catalogue__package-course" id="packages-lazy-load-result">
                                <?= $this->render('@frontend/views/particles/packages', ['models' => $packages->getModels()]); ?>
                            </ul>
                            
                            <?php if ($pagination->pageSize * ($pagination->page + 1) < $pagination->totalCount) { ?>
                                <button type="button"
                                   class="news__btn primary-btn"
                                   data-toggle="lazy-load-button"
                                   data-action="<?= Yii::$app->urlManager->createUrl([
                                        'course/packages',
                                        'type' => $key,
                                        'language' => Yii::$app->request->get('language'),
                                        'author' => Yii::$app->request->get('author'),
                                    ]) ?>"
                                   data-result="#packages-lazy-load-result"
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
                            <?= Yii::t('app', 'Ничего не найдено') ?>
                        </h3>
                        <div class="search-error__img">
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="406" height="306" class="illustration styles_illustrationTablet__1DWOa"><title>#18 search engine</title><path d="M176.44,89.86S145.54,67,114.65,70.11s-62.1,42.94-62.1,42.94l10,7.58s51.83-43,79.9-11.21S176.44,89.86,176.44,89.86Z" fill="#572fff"></path><path d="M176.44,89.86S145.54,67,114.65,70.11s-62.1,42.94-62.1,42.94l10,7.58s51.83-43,79.9-11.21S176.44,89.86,176.44,89.86Z" fill="#fff" opacity="0.2"></path><rect x="47" y="86.67" width="106.1" height="68.55" fill="#ffd200"></rect><path d="M324.82,233.05a13.44,13.44,0,0,1-9.23-6.91l0-.1-47.35-90.17-26.06,18.45,18.43,57.94a9.91,9.91,0,0,1-9.91,12.91c-14-.64-28.89-1-44.36-1-79.92,0-144.71,9.31-144.71,20.8s64.79,20.81,144.71,20.81S351,256.47,351,245C351,240.54,341.31,236.43,324.82,233.05Z" fill="#e6e6e6" opacity="0.45"></path><path d="M174.24,238.18s1.48,5,5.87,5.59,5.31,5,1.28,5.9-14-4.75-14-4.75l.63-6.35Z" fill="#572fff"></path><path d="M88.77,237.33s-5.08,1.1-6,5.43-5.38,4.91-6,.82S82.58,230,82.58,230l6.28,1.13Z" fill="#572fff"></path><path d="M89.45,162.67s-4.68,21.84,12.17,31.2,70-7.49,61.79,45.56h11.86s15.33-44-24.44-59.64L138.76,166.1Z" fill="#24285b"></path><rect x="235.67" y="139.91" width="5.58" height="36.52" transform="translate(175.32 -120.92) rotate(43.7)" fill="#ffd200"></rect><rect x="235.67" y="139.91" width="5.58" height="36.52" transform="translate(175.32 -120.92) rotate(43.7)" opacity="0.08"></rect><rect x="233.7" y="138.03" width="5.58" height="36.52" transform="translate(173.48 -120.09) rotate(43.7)" fill="#ffd200"></rect><ellipse cx="253.88" cy="142.03" rx="17" ry="11.34" transform="translate(-24.2 227.46) rotate(-46.3)" fill="#ffd200"></ellipse><ellipse cx="253.88" cy="142.03" rx="17" ry="11.34" transform="translate(-24.2 227.46) rotate(-46.3)" opacity="0.08"></ellipse><ellipse cx="251.91" cy="140.15" rx="17" ry="11.34" transform="translate(-23.45 225.46) rotate(-46.3)" fill="#ffd200"></ellipse><ellipse cx="251.91" cy="140.15" rx="11.23" ry="7.49" transform="translate(-23.45 225.46) rotate(-46.3)" fill="#fff" opacity="0.57"></ellipse><path d="M218.25,92.33s-4.51,6.55-8.89,9.77a4,4,0,0,1-5.59-.88c-1.59-2.23-3.09-5.71-1.1-9.51l2.58-6.87A7.14,7.14,0,0,1,212.53,81C218,81.32,221.19,88.48,218.25,92.33Z" fill="#f4a28c"></path><polygon points="206.83 86.25 186.48 94.45 188.98 108.93 203.65 96.96 206.83 86.25" fill="#f4a28c"></polygon><path d="M216.52,91.33a32,32,0,0,1-5.3-5.1,6.65,6.65,0,0,1-4.63,5.62,5.4,5.4,0,0,1-6.4-2.49l6.6-7.8a8.11,8.11,0,0,1,7.76-3,29.87,29.87,0,0,1,3.69.88c3,1,5.09,5.22,8.39,5.36a1.92,1.92,0,0,1,1.55,2.91c-1.62,2.69-5.16,6.11-9.49,4.74A7.45,7.45,0,0,1,216.52,91.33Z" fill="#24285b"></path><path d="M207.4,92.21s1.9-2.4,0-3.69-4.69,2.17-2.54,4.48Z" fill="#f4a28c"></path><path d="M215.2,96.31l-.24,3.77a1.28,1.28,0,0,1-1.89,1l-2.71-1.5Z" fill="#f4a28c"></path><path d="M202,98.29s.61-4.55,2.25-6.28c0,0-7.41,2.5-7.58,10.65Z" fill="#ce8172" opacity="0.31"></path><path d="M67.66,153.45S67.34,106.28,124,96.31L90.71,155.22h-23Z" opacity="0.08"></path><path d="M191.68,113.94,190,104.17a23.86,23.86,0,0,0-14.31-18.12,42.35,42.35,0,0,0-10.09-2.65c-4.44-.62-10.66,0-17.62,1.83C118,93.1,96,118.5,91.45,149.12l-2,13.55,49.31,3.43s-2.5-14,15-14S193.42,152.28,191.68,113.94Z" fill="#572fff"></path><path d="M143.36,98.79S119.43,103,93.13,140.9l-3.68,21.77,37.42,2.78Z" opacity="0.08"></path><path d="M170.8,116.22c4.67-15.58-13.44-28.18-26.35-18.28-10.8,8.27-23,23.16-23.48,48.29-.83,45.35,42.61,37.24,101.4,19.87l-1.87-13.21s-70.4,13.31-60.84-9.57C163.66,133.75,168.32,124.51,170.8,116.22Z" fill="#572fff"></path><path d="M170.8,116.22c4.67-15.58-13.44-28.18-26.35-18.28-10.8,8.27-23,23.16-23.48,48.29-.83,45.35,42.61,37.24,101.4,19.87l-1.87-13.21s-70.4,13.31-60.84-9.57C163.66,133.75,168.32,124.51,170.8,116.22Z" fill="#fff" opacity="0.2"></path><path d="M146.09,179.9s1.45,50.15-57.23,59.53L86.59,228.8s47-11.65,16.64-42.86Z" fill="#24285b"></path><ellipse cx="238.52" cy="244.98" rx="6.38" ry="2.36" opacity="0.08"></ellipse><ellipse cx="262.92" cy="238.87" rx="6.38" ry="2.36" opacity="0.08"></ellipse><ellipse cx="287.49" cy="243.59" rx="6.38" ry="2.36" opacity="0.08"></ellipse><ellipse cx="325.11" cy="245.96" rx="6.38" ry="2.36" opacity="0.08"></ellipse><ellipse cx="214.33" cy="238.28" rx="6.52" ry="2.42" opacity="0.08"></ellipse><path d="M220.86,155.43s16.18-1.71,17.6,4.61-1.68,9.67-16.24,5Z" fill="#f4a28c"></path><path d="M59.67,155.22s2.33,9.44,11.66,7.45S74,149.1,59.67,155.22Z" fill="#f4a28c"></path></svg>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile') ?>
</main>
