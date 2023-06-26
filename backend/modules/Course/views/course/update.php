<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\widgets\crud\UpdateWidget;
use backend\modules\Library\widgets\LibraryAttachmentTemplateWidget;

$this->title = $model->isNewRecord ? Yii::t('app', 'Создание') : Yii::t('app', 'Редактирование: {value}', ['value' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Курсы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'has_seo_meta' => true,
    'tabs' => [
        'information' => [
            'label' => Yii::t('app', 'Информация'),
            'attributes' => [
                'name' => 'text_input',
                'slug' => 'text_input',
                [
                    'name' => 'type',
                    'type' => 'select',
                    'data' => ArrayHelper::getColumn($model->enums->types(), 'label'),
                ],
                [
                    'name' => 'teachers_tmp',
                    'type' => 'select2_ajax',
                    'data' => ArrayHelper::map($model->teachers, 'id', 'username'),
                    'widget_options' => [
                        'ajax_url' => Yii::$app->urlManager->createUrl(['items-list/users', 'role' => 'teacher']),
                    ],
                    'options' => [
                        'options' => [
                            'multiple' => true,
                            'value' => ArrayHelper::getColumn($model->teachers, 'id'),
                        ],
                    ],
                ],
                [
                    'name' => 'language_id',
                    'type' => 'select',
                    'data' => ArrayHelper::map($languages, 'id', 'name'),
                ],
                [
                    'name' => 'date_from',
                    'type' => 'text_input',
                    'options' => [
                        'data-toggle' => 'datepicker',
                    ],
                ],
                [
                    'name' => 'date_to',
                    'type' => 'text_input',
                    'options' => [
                        'data-toggle' => 'datepicker',
                    ],
                ],
                'author' => 'text_input',
                'price' => 'text_input',
                'optimal_time' => 'text_input',
                'demo_time' => 'text_input',
                'students_start_quantity' => 'text_input',
                
                [
                    'name' => 'passing_percent',
                    'type' => 'text_input',
                    'container_options' => [
                        'options' => [
                            'class' => 'mb-3',
                            'data' => [
                                'visible_group' => 'course-type',
                                'visible_value' => 'linear',
                            ],
                        ],
                        'hintOptions' => [
                            'hint' => Yii::t('app', 'Рекомендуемое значение - {value}', ['value' => '70-90%']),
                        ],
                    ],
                ],
                [
                    'name' => 'certificate_file',
                    'type' => 'files',
                    'multiple' => false,
                    'container_options' => [
                        'options' => [
                            'class' => 'mb-3',
                            'data' => [
                                'visible_group' => 'course-type',
                                'visible_value' => 'linear',
                            ],
                        ],
                    ],
                    'options' => [
                        'options' => [
                            'accept' => '.docx',
                        ],
                        'pluginOptions' => [
                            'maxFileSize' => 1024 * 1024 * 10,
                            'initialPreviewConfig' => [
                                [
                                    'type' => 'office',
                                    'key' => $model->certificate_file,
                                    'downloadUrl' => $model->certificate_file,
                                ]
                            ],
                        ],
                    ],
                    'widget_options' => [
                        'delete_url' => Yii::$app->urlManager->createUrl([
                            'course/course/file-delete', 'id' => $model->id, 'attr' => 'certificate_file'
                        ]),
                    ],
                ],
            ],
        ],
        
        'description' => [
            'label' => Yii::t('app', 'Описание'),
            'attributes' => [
                [
                    'name' => 'preview_image',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'image',
                    ],
                ],
                [
                    'name' => 'image',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'image',
                    ],
                ],
                [
                    'name' => 'video',
                    'type' => 'elfinder',
                    'options' => [
                        'filter' => 'video',
                    ],
                ],
                'short_description' => 'text_area',
                'full_description' => 'tinymce',
            ],
        ],
        
        'content' => [
            'label' => Yii::t('app', 'Контент'),
            'attributes' => [
                LibraryAttachmentTemplateWidget::widget(['model' => $model]),
            ],
        ],
        
        'advantages' => [
            'label' => Yii::t('app', 'Преимущества'),
            'attributes' => [
                [
                    'name' => false,
                    'type' => 'render',
                    'view' => '_advantages',
                ],
            ],
        ],
        
        'authors' => [
            'label' => Yii::t('app', 'Авторы'),
            'attributes' => [
                [
                    'name' => false,
                    'type' => 'render',
                    'view' => '_authors',
                ],
            ],
        ],
    ],
]);

?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('change', '#course-type', function() {
            $('[data-visible_group="course-type"]').addClass('d-none');
            $('[data-visible_group="course-type"][data-visible_value="' + $(this).val() + '"]').removeClass('d-none');
        });
        
        $('#course-type').trigger('change');
    });
</script>
