<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<?php if ($model->available_from < date('Y-m-d H:i:s')) { ?>
    <div class="test__successful test-info--result">
        <div class="test-result-successful">
            <div class="test-result-successful__total">
                <h6 class="test-result-successful__percent">
                    <?= Yii::t('app', 'Вы набрали {percent}%', [
                        'percent' => $model->performance,
                    ]) ?>
                </h6>
                <div class="test-result-successful__num">
                    <?= Yii::t('app', '{score} из {total} верных', [
                        'score' => $test_quantity['score'],
                        'total' => $test_quantity['total'],
                    ]) ?>
                </div>
            </div>
            
            <?php if ($type == 'linear') { ?>
                <div class="test-result-successful__nav">
                    <div class="test-result-successful__link">
                        <a class="tertinary-btn"
                           href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $course->id, 'id' => $model->id]) ?>"
                        >
                            <?= Yii::t('app', 'Пройти ещё раз') ?>
                        </a>
                    </div>
                    
                    <?php if ($next_unit) { ?>
                        <div class="test-result-successful__link">
                            <a class="primary-btn"
                               href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $next_unit->tree, 'id' => $next_unit->id]) ?>"
                            >
                                <span class="_icon-arrow">
                                    <?= Yii::t('app', 'Далее') ?>
                                </span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <a class="test-result-successful__btn primary-btn"
                   href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $course->id, 'id' => $model->id]) ?>"
                >
                    <span class="_icon-arrow">
                        <?= Yii::t('app', 'Пройти ещё раз') ?>
                    </span>
                </a>
            <?php } ?>
        </div>
    </div>
<?php } else { ?>
    <div class="test__unsuccessful test-info--result">
        <div class="test-result-unsuccessful">
            <div class="test-result-unsuccessful__total">
                <h6 class="test-result-unsuccessful__percent">
                    <?= Yii::t('app', 'Вы набрали {percent}%', [
                        'percent' => $model->performance,
                    ]) ?>
                </h6>
                <div class="test-result-unsuccessful__num">
                    <?= Yii::t('app', '{score} из {total} верных', [
                        'score' => $test_quantity['score'],
                        'total' => $test_quantity['total'],
                    ]) ?>
                </div>
            </div>
            
            <div class="test-result-unsuccessful__status">
                <div class="test-result-unsuccessful__label">
                    <?= Yii::t('app', 'Тест не пройден!') ?>
                </div>
                <div class="test-result-unsuccessful__time _icon-clock">
                    <div class="test-result-unsuccessful__time-label">
                        <?= Yii::t('app', 'Следующая попытка через:') ?>
                    </div>
                    <h6 class="test-result-unsuccessful__time-num">
                        <?= Yii::t('app', '{hours} часов', [
                            'hours' => Html::tag('span', round((strtotime($model->available_from) - time()) / 3600)),
                        ]) ?>
                    </h6>
                </div>
            </div>
        </div>
        
        <div class="unit__links">
            <?php if ($prev_unit) { ?>
                <a class="unit__link-prev tertinary-btn"
                   href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $prev_unit->tree, 'id' => $prev_unit->id]) ?>"
                >
                    <span class="_icon-arrow">
                        <?= Yii::t('app', 'Назад') ?>
                    </span>
                </a>
            <?php } ?>
            
            <?php if ($next_unit && $next_unit->is_unlocked) { ?>
                <a class="unit__link-next primary-btn"
                   href="<?= Yii::$app->urlManager->createUrl(['user/unit/view', 'course_id' => $next_unit->tree, 'id' => $next_unit->id]) ?>"
                >
                    <span class="_icon-arrow">
                        <?= Yii::t('app', 'Вперёд') ?>
                    </span>
                </a>
            <?php } ?>
        </div>
    </div>
<?php } ?>
