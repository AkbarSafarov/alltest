<?php

$page->registerSeoMeta();

$this->title = Yii::t('app', 'Часто задаваемые вопросы');
$this->params['header_class'] = 'header';

?>

<main class="main">
    <section class="faq">
        <div class="container">
            <div class="faq__content">
                <h2 class="faq__title">
                    <?= $this->title ?>
                    <span class="title-decor"></span>
                </h2>
                
                <ul class="faq__list">
                    <?php foreach ($blocks->questions_and_answers as $q_a) { ?>
                        <li>
                            <div class="faq__item submenu">
                                <button class="faq__item-btn">
                                    <span class="faq__item-title">
                                        <?= $q_a->question ?>
                                    </span>
                                    <span class="faq__item-icon">
                                        <i class="faq__item-icon-plus _icon-plus"></i>
                                        <i class="faq__item-icon-minus _icon-minus"></i>
                                    </span>
                                </button>
                                
                                <div class="faq__item-text">
                                    <?= $q_a->answer ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </section>
    
    <section class="banner">
        <div class="container">
            <div class="banner__content">
                <h5 class="banner__title">
                    <?= Yii::t('app', 'Не смогли найти ответ на свой вопрос?') ?>
                </h5>
                <p class="banner__text">
                    <?= Yii::t('app', 'Напишите нам и мы ответим на все Ваши вопросы!') ?>
                </p>
                <a class="banner__link _icon-arrow" href="<?= Yii::$app->urlManager->createUrl(['site/contact']) ?>">
                    <?= Yii::t('app', 'Связаться с нами') ?>
                </a>
            </div>
        </div>
    </section>
</main>
