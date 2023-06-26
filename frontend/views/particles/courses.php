<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use speedrunner\services\DateService;

use backend\modules\Course\enums\CourseEnums;

$types = CourseEnums::types();

?>

<?php foreach ($models as $model) { ?>
    <?php $link = Yii::$app->urlManager->createUrl(['course/course-view', 'slug' => $model->slug]) ?>
    
    <li class="catalogue__item">
        <article class="course-card">
            <div class="course-card__inner">
                <div class="course-card__top">
                    <a class="course-card__top-img" href="<?= $link ?>">
                        <img src="<?= Yii::$app->services->image->thumb($model->preview_image, [370, 180]) ?>" alt="">
                    </a>
                    
                    <?php if ($percent = ArrayHelper::getValue($model, 'activeDiscount.percent')) { ?>
                        <div class="course-card__sale _icon-fire">
                            <?= "-$percent%" ?>
                        </div>
                    <?php } ?>
                    
                    <div class="course-card__top-hover">
                        <a class="course-card__link primary-btn"
                           href="<?= $link ?>"
                        >
                            <span class="_icon-arrow">
                                <?= Yii::t('app', 'Подробнее') ?>
                            </span>
                        </a>
                        
                        <?php if (!$model->service->isOwned()) { ?>
                            <?= Html::beginForm(['cart/change'], 'post', [
                                'data-toggle' => 'cart-change-form',
                            ]); ?>
                            
                            <?= Html::hiddenInput('id', $model->id) ?>
                            <?= Html::hiddenInput('type', 'Course') ?>
                            
                            <button type="submit" class="course-card__link-cart primary-btn _icon-cart"></button>
                            
                            <?= Html::endForm(); ?>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="course-card__body">
                    <a class="course-card__category" href="<?= $link ?>">
                        <?= ArrayHelper::getValue($types, "$model->type.label") ?>
                    </a>
                    
                    <a class="course-card__title" href="<?= $link ?>">
                        <h5><?= $model->name ?></h5>
                    </a>
                    
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
        </article>
    </li>
<?php } ?>