<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<table class="table table-bordered table-striped table-relations">
    <thead>
        <tr>
            <th style="width: 50px;">#</th>
            <th><?= Yii::t('app', 'Курс') ?></th>
            <th><?= Yii::t('app', 'Дата приобретения') ?></th>
            <th><?= Yii::t('app', 'Последнее посещение') ?></th>
            <th><?= Yii::t('app', 'Текущий юнит') ?></th>
            <th><?= Yii::t('app', 'Награды') ?></th>
        </tr>
    </thead>
    
    <tbody>
        <?php foreach ($courses as $key => $course) { ?>
            <tr>
                <td>
                    <?= $key + 1 ?>
                </td>
                <td>
                    <?= Html::a($course->name, ['/course/course/view', 'id' => $course->id], ['target' => '_blank']) ?>
                </td>
                <td>
                    <?= date('d.m.Y H:i', strtotime($course->created_at)) ?>
                </td>
                <td>
                    <?= date('d.m.Y H:i', strtotime($course->last_visit)) ?>
                </td>
                <td>
                    <b><?= "$course->progress%" ?></b>
                    <?= $course->current_unit ? "<hr>$course->current_unit" : null ?>
                </td>
                <td>
                    <?php
                        if ($course->leagues) {
                            $html[0] = Html::tag('h4', Yii::t('app', 'Лиги'), ['class' => 'mt-0']);
                            $html[0] .= implode('<br>', ArrayHelper::getColumn($course->leagues, 'league.name'));
                        }
                        
                        if ($course->achievements) {
                            $html[1] = Html::tag('h4', Yii::t('app', 'Достижения'), ['class' => 'mt-0']);
                            $html[1] .= implode('<br>', ArrayHelper::getColumn($course->achievements, 'achievement.name'));
                        }
                        
                        echo implode('<hr>', $html ?? []);
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
