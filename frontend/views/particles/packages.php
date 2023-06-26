<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use speedrunner\services\DateService;

?>

<?php foreach ($models as $model) { ?>
    <li class="catalogue__item">
        <div class="course-card package-card"
             data-sr-trigger="ajax-button"
             data-sr-url="<?= Yii::$app->urlManager->createUrl(['course/package-view', 'id' => $model->id]) ?>"
             data-sr-wrapper="#main-modal-wrapper"
             data-sr-callback="$('#main-modal').addClass('_active');"
        >
            <div class="course-card__inner">
                <div class="course-card__top">
                    <img src="<?= Yii::$app->services->image->thumb($model->image, [570, 203]) ?>" alt="">
                    
                    <?php if ($percent = ArrayHelper::getValue($model, 'activeDiscount.percent')) { ?>
                        <div class="course-card__sale _icon-fire">
                            <?= "-$percent%" ?>
                        </div>
                    <?php } ?>
                    
                    <div class="course-card__top-hover">
                        <div class="course-card__link primary-btn">
                            <span class="_icon-arrow">
                                <?= Yii::t('app', 'Подробнее') ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="course-card__body">
                    <div class="course-card__title">
                        <h5><?= $model->name ?></h5>
                    </div>
                    
                    <div class="course-card__access">
                        <span class="course-card__access-item">
                            <?= $model->optimal_time ?>
                        </span>
                        <span class="course-card__access-item">
                            <?= Yii::t('app', 'Доступ до {date}', [
                                'date' => $model->date_to,
                            ]) ?>
                        </span>
                    </div>
                    
                    <div class="course-card__users">
                        <span class="_icon-users">
                            <?= Yii::t('app', 'Учеников:') ?>
                            <?= $model->students_total_quantity ?>
                        </span>
                    </div>
                    
                    <div class="course-card__price">
                        <h5 class="course-card__price-current">
                            <?= Yii::t('app', '{price} UZS', [
                                'price' => Yii::$app->formatter->asDecimal($model->service->realPrice()),
                            ]) ?>
                        </h5>
                        
                        <?php if ($model->activeDiscount) { ?>
                            <div class="course-card__price-old">
                                <?= Yii::t('app', '{price} UZS', [
                                    'price' => Yii::$app->formatter->asDecimal($model->price),
                                ]) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </li>
<?php } ?>