<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = $model->unit_json['name'];

Yii::$app->session->set("unit_{$model->id}_test_pack", []);

?>

<main class="main has-bg">
    <div class="unit">
        <div class="container__wrapper">
            <?= $this->render('@frontend/views/layouts/submenu/desktop', [
                'current_route' => ['user/course/index'],
                'course_structure_collapse_button' => true,
            ]) ?>
            
            <div class="container">
                <div class="unit__inner" oncopy="return false">
                    <?= $this->render('_sidebar', [
                        'course' => $course,
                    ]); ?>
                    
                    <div class="nav-overlay"></div>
                    
                    <?= $this->render("view/{$course->course_json['type']}", [
                        'model' => $model,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('@frontend/views/layouts/submenu/mobile', ['current_route' => ['user/course/index']]) ?>
</main>
