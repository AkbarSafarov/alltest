<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\Trash\widgets\TrashStateTabsWidget;

$this->title = Yii::t('app', 'Структура курса "{course}"', ['course' => $root_unit->course->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Курсы'), 'url' => ['course/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

$is_active = $root_unit->state == 'active';

?>

<div class="card">
    <div class="card-body">
        <?= TrashStateTabsWidget::widget(['model' => $root_unit, 'states' => ['active', 'archive']]) ?>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row course-unit-filter">
                    <div class="col">
                        <label class="form-label">
                            <?= Yii::t('app', 'Название') ?>
                        </label>
                        <?= Html::textInput('name', null, [
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                    
                    <div class="col">
                        <label class="form-label">
                            <?= Yii::t('app', 'Статус') ?>
                        </label>
                        <?= Html::dropDownList('status', null, ArrayHelper::getColumn($statuses, 'label'), [
                            'class' => 'form-control',
                            'prompt' => '',
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 text-sm-end">
                <div><label class="form-label">&nbsp;</label><br></div>
                
                <?= Html::button(
                    Html::tag('i', null, ['class' => 'fas fa-eye-slash me-2']) . Yii::t('app', 'Скрыть всю структуру'),
                    [
                        'class' => 'btn btn-danger m-0',
                        'onclick' => "$('.course-unit-wrapper .collapse').removeClass('show');",
                    ]
                ) ?>
                
                <?= Html::button(
                    Html::tag('i', null, ['class' => 'fas fa-eye me-2']) . Yii::t('app', 'Развернуть всю структуру'),
                    [
                        'class' => 'btn btn-success m-0',
                        'onclick' => "$('.course-unit-wrapper .collapse').addClass('show');",
                    ]
                ) ?>
            </div>
        </div>
        
        <div class="accordion custom-accordion course-unit-wrapper">
            <div class="course-section-wrapper course-section-1-sortable" data-id="<?= $root_unit->id ?>">
                <?php foreach ($sections as $section_1) { ?>
                    <div class="card mb-2" data-id="<?= $section_1->id ?>">
                        <div class="course-section-1-counter-reset"></div>
                        
                        <div class="course-unit">
                            <?= $this->render('index/unit', [
                                'model' => $section_1,
                                'is_active' => $is_active,
                                'types' => $types,
                                'statuses' => $statuses,
                                'collapse_target' => "course-section-$section_1->id",
                                'has_parent' => false,
                            ]); ?>
                        </div>
                        
                        <div class="card-body ps-4 pe-0 py-2 collapse <?= in_array($section_1->id, $course_structure) ? 'show' : null ?>"
                             id="course-section-<?= $section_1->id ?>"
                        >
                            <div class="course-section-2-sortable" data-id="<?= $section_1->id ?>">
                                <?php foreach ($section_1->children as $key_2 => $section_2) { ?>
                                    <div class="card mb-2" data-id="<?= $section_2->id ?>">
                                        <div class="course-section-2-counter-reset"></div>
                                        
                                        <div class="course-unit">
                                            <?= $this->render('index/unit', [
                                                'model' => $section_2,
                                                'is_active' => $is_active,
                                                'types' => $types,
                                                'statuses' => $statuses,
                                                'collapse_target' => "course-section-$section_2->id",
                                                'has_parent' => true,
                                            ]); ?>
                                        </div>
                                        
                                        <div class="card-body ps-4 pe-0 py-2 collapse <?= in_array($section_2->id, $course_structure) ? 'show' : null ?>"
                                             id="course-section-<?= $section_2->id ?>"
                                        >
                                            <div class="course-unit-sortable" data-id="<?= $section_2->id ?>">
                                                <?php foreach ($section_2->children as $key_3 => $unit) { ?>
                                                    <div class="card mb-2" data-id="<?= $unit->id ?>">
                                                        <div class="course-unit">
                                                            <?= $this->render('index/unit', [
                                                                'model' => $unit,
                                                                'is_active' => $is_active,
                                                                'types' => $types,
                                                                'statuses' => $statuses,
                                                                'has_parent' => true,
                                                            ]); ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            
                                            <?= $is_active ? Html::a(
                                                Yii::t('app', 'Добавить юнит'),
                                                ['create', 'parent_id' => $section_2->id],
                                                ['class' => 'btn btn-success w-100']
                                            ) : null ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <?= $is_active ? Html::a(
                                Yii::t('app', 'Добавить подкатегорию'),
                                ['create', 'parent_id' => $section_1->id],
                                ['class' => 'btn btn-success w-100']
                            ) : null ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            <?= $is_active ? Html::a(
                Yii::t('app', 'Добавить категорию'),
                ['create', 'parent_id' => $root_unit->id],
                ['class' => 'btn btn-success w-100']
            ) : null ?>
        </div>
    </div>
</div>

<div class="common-buttons d-none">
    <?php
        echo Html::beginForm(null, 'post', [
            'id' => 'course-structure-form',
            'data-sr-trigger' => 'ajax-form',
            'data-sr-callback' => Yii::$app->services->trash->ajaxReload(),
        ]);
        
        echo Html::tag('h6', Yii::t('app', 'Действия'), ['class' => 'bg-light p-2 m-0']);
        
        if ($is_active) {
            echo Html::submitButton(Html::tag('i', null, ['class' => 'fas fa-copy me-2']) . Yii::t('app', 'Клонировать все'), [
                'onclick' => "$(this).closest('form').attr('action', '" . Yii::$app->urlManager->createUrl(['course/unit/clone']) . "');",
                'class' => 'btn btn-info',
                'data-confirm' => Yii::t('app', 'Вы уверены?'),
            ]);
            
            echo Html::submitButton(Html::tag('i', null, ['class' => 'fas fa-trash me-2']) . Yii::t('app', 'Удалить все'), [
                'formaction' => Yii::$app->urlManager->createUrl(['course/unit/delete']),
                'class' => 'btn btn-danger',
                'data-trigger' => 'delete-confirmation-button',
            ]);
        } else {
            echo Html::submitButton(Html::tag('i', null, ['class' => 'fas fa-trash-restore me-2']) . Yii::t('app', 'Восстановить все'), [
                'formaction' => Yii::$app->urlManager->createUrl(['course/unit/delete']),
                'class' => 'btn btn-info',
                'data-trigger' => 'delete-confirmation-button',
            ]);
        }
    ?>
    
    <?= Html::endForm() ?>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(e) {
        
        //      Removing empty spaces
        
        <?php if (!$is_active) { ?>
            $('.card-body > div').each(function() {
                if ($(this).html().trim() === '') {
                    $(this).parent().remove();
                }
            });
        <?php } ?>
        
        //      Structure toggle
        
        $(document).on('click', '.course-unit-collapse-toggle', function() {
            $.get('<?= Yii::$app->urlManager->createUrl(['course/unit/structure-toggle']) ?>', {
                course_id: '<?= $root_unit->tree ?>',
                id: $(this).data('id')
            });
        });
        
        //      Filter
        
        let filterName, filterStatus,
            selector;
        
        $(document).on('change', '.course-unit-filter [name="name"], .course-unit-filter [name="status"]', function() {
            filterName = $('.course-unit-filter [name="name"]').val();
            filterStatus = $('.course-unit-filter [name="status"]').val();
            selector = '.course-unit-title:contains(' + filterName + ')';
            
            if (filterStatus.length > 0) {
                selector += '[data-status*="' + filterStatus + '"]';
            }
            
            $('.course-unit-wrapper .card').addClass('d-none');
            $(selector).parents('.card').removeClass('d-none');
        });
        
        //      Sortable
        
        function sortableFunction() {
            let id, relId, insertType,
                action, sendData;
            
            function stopFunction(event, ui) {
                id = ui.item.data('id');
                
                if (ui.item.prev('*').data('id') !== undefined) {
                    relId = ui.item.prev('*').data('id');
                    insertType = 'after';
                } else if (ui.item.next('*').data('id') !== undefined) {
                    relId = ui.item.next('*').data('id');
                    insertType = 'before';
                } else {
                    relId = ui.item.parent().data('id');
                    insertType = 'over';
                }
                
                action = '<?= Yii::$app->urlManager->createUrl(['course/unit/move']) ?>';
                sendData = {
                    id: id,
                    rel_id: relId,
                    insert_type: insertType,
                };
                
                $.get(action, sendData, () => {
                    $.get('<?= Yii::$app->urlManager->createUrl(['/course/unit/index-unit']) ?>', {id: id}, (unit) => ui.item.find('.course-unit').eq(0).html(unit));
                });
            }
            
            let sortableParams = {
                handle: '.table-sorter',
                placeholder: 'sortable-placeholder',
                start: function(event, ui) {
                    ui.placeholder.height(ui.helper.outerHeight());
                    id = ui.item.data('id');
                },
                stop: function(event, ui) {
                    stopFunction(event, ui);
                }
            };
            
            $('.course-section-1-sortable').sortable({...sortableParams, ...{
                connectWith: '.course-section-1-sortable',
            }});
            
            $('.course-section-2-sortable').sortable({...sortableParams, ...{
                connectWith: '.course-section-2-sortable',
            }});
            
            $('.course-unit-sortable').sortable({...sortableParams, ...{
                connectWith: '.course-unit-sortable',
            }});
        }
        
        $(document).on('mouseenter', '.course-unit-wrapper .table-sorter', function() {
            if ($('.course-unit-wrapper .ui-sortable').length === 0) {
                sortableFunction();
            }
        });
        
        //      Inline update
        
        let el, url, value;
        
        $(document).on('click', '[data-trigger="toggle_visibility"]', function() {
            el = $($(this).data('el'));
            el.toggle();
            
            value = el.find('input').val();
            el.find('input').focus().val('').val(value);
        });
        
        $(document).on('keydown', '[class*="course-inline-update"] input', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $(this).parent().find('button').trigger('click');
            }
        });
        
        $(document).on('click', '[class*="course-inline-update"] button', function(event) {
            el = $(this).parent().find('input');
            url = el.data('url');
            
            sendData = {'_csrf-backend': $('meta[name="csrf-token"]').attr('content')};
            sendData[el.attr('name')] = el.val();
            
            $.ajax({
                url: url,
                type: 'post',
                data: sendData,
                success: (data) => {
                    $($(this).data('el')).toggle();
                    $(this).closest('.course-unit').html(data);
                },
            });
        });
    });
</script>


<style>
    .course-section-wrapper {
        counter-reset: course-section-1-counter;
    }
    
    .course-section-1-counter-reset {
        counter-reset: course-section-2-counter;
    }
    
    .course-section-2-counter-reset {
        counter-reset: course-unit-counter;
    }
    
    .course-section-1-sortable h5::before {
        counter-increment: course-section-1-counter;
        content: counter(course-section-1-counter) '. ';
    }
    
    .course-section-2-sortable h5::before {
        counter-increment: course-section-2-counter;
        content: counter(course-section-1-counter) '.' counter(course-section-2-counter) '. ';
    }
    
    .course-unit-sortable h5::before {
        counter-increment: course-unit-counter;
        content: counter(course-section-1-counter) '.' counter(course-section-2-counter) '.' counter(course-unit-counter) '. ';
    }
</style>
