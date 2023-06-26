<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\CourseUnit;


$model = new CourseUnit([
    'id' => $model->id,
    'tree' => $model->tree,
    'name' => $model->name,
    'depth' => $model->depth,
    'type_id' => $model->type_id,
    'status' => $model->status,
]);

//        Moderation

$moderation_buttons = implode(null, Yii::$app->services->moderationStatus->tableButtons($model));
$moderation_buttons = $moderation_buttons ? Html::tag(
    'div',
    $moderation_buttons,
    ['class' => 'd-flex float-start mx-3']
) : null;

//        Actions

$available_buttons = [
    'moderation' => $moderation_buttons,
    'update' => Html::button(
        Html::tag('i', null, ['class' => 'fas fa-edit']),
        [
            'title' => Yii::t('app', 'Редактировать название'),
            'class' => 'btn btn-link',
            'data' => [
                'trigger' => 'toggle_visibility',
                'el' => ".course-inline-update-$model->id",
            ],
        ]
    ),
//    'clone' => Html::a(
//        Html::tag('i', null, ['class' => 'fas fa-copy']),
//        ['clone', 'id' => $model->id],
//        [
//            'title' => Yii::t('app', 'Клонировать'),
//            'class' => 'btn btn-link',
//            'data-method' => 'post',
//            'data-confirm' => Yii::t('app', 'Вы уверены?'),
//            'data-sr-callback' => Yii::$app->services->trash->ajaxReload(),
//        ]
//    ),
    'preview' => $model->depth == 3 ? Html::a(
        Html::tag('i', null, ['class' => 'fas fa-external-link-alt']),
        ['preview/unit', 'id' => $model->id],
        [
            'title' => Yii::t('app', 'Предпросмотр'),
            'class' => 'btn btn-link',
            'target' => '_blank',
        ]
    ) : null,
//    'delete' => Html::a(
//        Html::tag('i', null, ['class' => 'fas fa-trash']),
//        ['delete', 'id' => $model->id],
//        [
//            'title' => Yii::t('app', 'Удалить'),
//            'class' => 'btn btn-link',
//            'data-trigger' => 'delete-confirmation-button',
//            'data-sr-callback' => Yii::$app->services->trash->ajaxReload(),
//        ]
//    ),
//    'restore' => !$has_parent ? Html::a(
//        Html::tag('i', null, ['class' => 'fas fa-trash-restore']),
//        ['delete', 'id' => $model->id],
//        [
//            'title' => Yii::t('app', 'Восстановить'),
//            'class' => 'btn btn-link',
//            'data-trigger' => 'delete-confirmation-button',
//            'data-sr-callback' => Yii::$app->services->trash->ajaxReload(),
//        ]
//    ) : null,
    'move' => Html::a(
        Html::tag('i', null, ['class' => 'fas fa-arrows-alt']),
        '#',
        [
            'title' => Yii::t('app', 'Переместить'),
            'class' => 'btn btn-link table-sorter',
            'data-url' => Yii::$app->urlManager->createUrl(['/course/unit/index-unit', 'id' => $model->id]),
        ]
    ),
];

//$buttons = $is_active ? ['moderation', 'update', 'preview', 'clone', 'delete', 'move'] : ['restore'];
$buttons = $is_active ? ['moderation', 'update', 'preview', 'move'] : [];

//        Label

$type = Html::tag('h6', ArrayHelper::getValue($types, "$model->type_id.name"), ['class' => 'my-2']);
$type .= Html::tag('span', $statuses[$model->status]['single_label'], [
    'class' => 'badge bg-' . $statuses[$model->status]['css_class'],
    'style' => 'font-size: 12px;',
]);

$title = Html::tag('h5', $model->name, [
    'class' => 'course-unit-title m-0',
    'style' => 'text-overflow: ellipsis; overflow: hidden; max-width: 500px; white-space: nowrap;',
    'data-status' => $model->status,
]);

$label = Html::tag('div', $title . $type);

?>

<div class="card-header d-flex align-items-center p-2 badge-soft-<?= $model->depth == 1 ? 'primary' : ($model->depth == 2 ? 'success' : 'danger') ?>">
    <?php
        if ($collapse_target ?? false) {
            echo Html::a(null, '#', [
                'class' => 'course-unit-collapse-toggle',
                'data-bs-toggle' => 'collapse',
                'data-bs-target' => "#$collapse_target",
                'data-id' => $model->id,
            ]);
        }
    ?>
    
    <?php if ($is_active) { ?>
        <div class="form-check form-check-primary ms-1 me-2">
            <?= Html::checkbox('selection[]', false, [
                'value' => $model->id,
                'form' => 'course-structure-form',
                'class' => 'form-check-input',
                'id' => "course-unit-selection-$model->id",
            ]) ?>
            
            <?= Html::label(null, "course-unit-selection-$model->id", [
                'class' => 'form-check-label',
            ]) ?>
        </div>
        
        <?= Html::a($label, ['update', 'id' => $model->id], [
            'class' => "course-inline-update-$model->id",
            'style' => 'display: flex;',
        ]) ?>
        
        <div class="w-100 course-inline-update-<?= $model->id ?>" style="display: none; max-width: 600px;">
            <div class="d-flex">
                <?= Html::textInput('CourseUnit[name]', $model->name, [
                    'class' => 'form-control',
                    'data-url' => Yii::$app->urlManager->createUrl(['course/unit/update', 'id' => $model->id, 'is_ajax' => true]),
                ]) ?>
                
                <?= Html::button(
                    Html::tag('i', null, ['class' => 'fas fa-check']),
                    [
                        'class' => 'btn btn-success',
                        'data-el' => ".course-inline-update-$model->id",
                    ]
                ) ?>
            </div>
        </div>
    <?php } else { ?>
        <?php if (!$has_parent) { ?>
            <div class="form-check form-check-primary ms-1 me-2">
                <?= Html::checkbox('selection[]', false, [
                    'value' => $model->id,
                    'form' => 'course-structure-form',
                    'class' => 'form-check-input',
                    'id' => "course-unit-selection-$model->id",
                ]) ?>
                
                <?= Html::label(null, "course-unit-selection-$model->id", [
                    'class' => 'form-check-label',
                ]) ?>
            </div>
        <?php } ?>
        
        <?= Html::tag('div', $label, ['style' => 'display: flex;']) ?>
    <?php } ?>
    
    <div class="ms-auto text-end">
        <?= implode(null, ArrayHelper::filter($available_buttons, $buttons)) ?>
    </div>
</div>
