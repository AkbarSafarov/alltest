<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$model->registerSeoMeta();

$this->title = $model->name;
$this->params['header_class'] = 'header';

?>

<main class="main">
    <div class="public-offer">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['site/page', 'slug' => $model->slug]]) ?>
            
            <div class="container">
                <h2 class="public-offer__title">
                    <?= $this->title ?>
                    <span class="title-decor"></span>
                </h2>
                
                <article class="public-offer__item">
                    <?= $model->description ?>
                </article>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['site/page', 'slug' => $model->slug]]) ?>
</main>
