<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'История платежей');

$pagination = $orders->pagination;

?>

<main class="main has-bg">
    <div class="payment">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', ['current_route' => ['user/profile/view']]) ?>
            
            <div class="container">
                <h3 class="payment__title">
                    <?= $this->title ?>
                </h3>
                <a class="payment__btn primary-btn" href="<?= Yii::$app->urlManager->createUrl(['user/profile/view']) ?>">
                    <?= Yii::t('app', 'Назад в профиль') ?>
                </a>

                <div class="payment__table">
                    <div class="payment__selects">
                        <div class="payment__select">
                            <?= Html::beginForm(false, 'get') ?>
                            
                            <?= Html::dropDownList('sort', Yii::$app->request->get('sort'), [
                                'date' => Yii::t('app', 'By date (asc)'),
                                '-price' => Yii::t('app', 'By price (desc)'),
                                'price' => Yii::t('app', 'By price (asc)'),
                            ], [
                                'class' => 'select-dropdown',
                                'prompt' => Yii::t('app', 'By date (desc)'),
                                'onchange' => "$(this).closest('form').trigger('submit')",
                            ]) ?>
                            
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                    
                    <ul class="account__table account__table--payment" id="orders-lazy-load-result">
                        <?= $this->render('@frontend/views/particles/orders', ['models' => $orders->getModels()]); ?>
                    </ul>
                    
                    <?php if ($pagination->pageSize * ($pagination->page + 1) < $pagination->totalCount) { ?>
                        <button type="button"
                           class="news__btn primary-btn"
                           data-toggle="lazy-load-button"
                           data-action="<?= Yii::$app->urlManager->createUrl(['user/profile/orders', 'sort' => Yii::$app->request->get('sort')]) ?>"
                           data-result="#orders-lazy-load-result"
                           data-offset="1"
                           data-offset_max="<?= ceil($pagination->totalCount / $pagination->pageSize) - 1 ?>"
                        >
                            <span class="_icon-arrow">
                                <?= Yii::t('app', 'Больше заказов') ?>
                            </span>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['user/profile/view']]) ?>
</main>
