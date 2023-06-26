<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use alexantr\tinymce\TinyMCE;
use alexantr\elfinder\InputFile;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin(
    ArrayHelper::merge(
        [
            'options' => [
                'id' => 'update-form',
                'enctype' => 'multipart/form-data',
            ],
        ],
        $form_options
    )
); ?>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-8 offset-sm-4">
                <div class="text-sm-end">
                    <?= Yii::$app->services->html->saveButtons($save_buttons, Url::to($form->action)) ?>
                </div>
            </div>
        </div>
        
        <div class="accordion custom-accordion">
            <?php foreach ($tabs as $tab_key => $tab) { ?>
                <div class="card mb-0">
                    <div class="card-header p-0 bg-primary">
                        <h5 class="m-0 position-relative">
                            <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-<?= $tab_key ?>">
                                <?= $tab['label'] ?>
                                <i class="mdi mdi-chevron-down accordion-arrow"></i>
                            </a>
                        </h5>
                    </div>
                    
                    <div id="tab-<?= $tab_key ?>" class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                <?php
                                    foreach ($tab['attributes'] as $key => $attribute) {
                                        $name = is_array($attribute) ? $attribute['name'] : $key;
                                        $type = is_array($attribute) ? $attribute['type'] : $attribute;
                                        
                                        switch ($type) {
                                            case 'text_input':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::getValue($attribute, 'container_options', [])
                                                )->textInput(
                                                    ArrayHelper::getValue($attribute, 'options', [])
                                                );
                                                break;
                                                
                                            case 'text_area':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::getValue($attribute, 'container_options', [])
                                                )->textArea(
                                                    ArrayHelper::merge(
                                                        ['rows' => 5],
                                                        ArrayHelper::getValue($attribute, 'options', [])
                                                    )
                                                );
                                                break;
                                                
                                            case 'checkbox':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::getValue($attribute, 'container_options', [])
                                                )->checkbox(
                                                    ArrayHelper::merge(
                                                        ['class' => 'form-check-input'],
                                                        ArrayHelper::getValue($attribute, 'options', [])
                                                    )
                                                )->label(null, ['class' => 'form-check-label']);
                                                break;
                                                
                                            case 'select':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::getValue($attribute, 'container_options', [])
                                                )->dropDownList(
                                                    ArrayHelper::getValue($attribute, 'data', []),
                                                    ArrayHelper::getValue($attribute, 'options', [])
                                                );
                                                break;
                                                
                                            case 'select2_ajax':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::getValue($attribute, 'container_options', [])
                                                )->widget(
                                                    Select2::className(),
                                                    ArrayHelper::merge(
                                                        [
                                                            'data' => ArrayHelper::getValue($attribute, 'data', []),
                                                            'options' => [
                                                                'placeholder' => '',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                                'tags' => ArrayHelper::getValue($attribute, 'widget_options.tags', false),
                                                                'ajax' => [
                                                                    'url' => ArrayHelper::getValue($attribute, 'widget_options.ajax_url'),
                                                                    'dataType' => 'json',
                                                                    'delay' => 300,
                                                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                                                ],
                                                            ],
                                                        ],
                                                        ArrayHelper::getValue($attribute, 'options', [])
                                                    )
                                                );
                                                break;
                                                
                                            case 'elfinder':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::getValue($attribute, 'container_options', [])
                                                )->widget(
                                                    InputFile::className(),
                                                    ArrayHelper::getValue($attribute, 'options', [])
                                                );
                                                break;
                                                
                                            case 'tinymce':
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::merge(
                                                        ['options' => ['class' => 'mb-3']],
                                                        ArrayHelper::getValue($attribute, 'container_options', [])
                                                    )
                                                )->widget(
                                                    TinyMCE::className(),
                                                    ArrayHelper::getValue($attribute, 'options', [])
                                                );
                                                break;
                                                
                                            case 'files':
                                                $multiple = ArrayHelper::getValue($attribute, 'multiple', false);
                                                $files = ArrayHelper::getValue($attribute, 'value', $model->{$name});
                                                
                                                $initial_preview = $multiple ? ($files ?? []) : ($files ?? '');
                                                $initial_preview_config = $multiple ? (
                                                    ArrayHelper::getColumn($files ?? [], fn ($value) => ['key' => $value, 'downloadUrl' => $value])
                                                ) : [];
                                                
                                                echo $form->field(
                                                    $model, $name,
                                                    ArrayHelper::merge(
                                                        [
                                                            'template' => '{label}{hint}{error}{input}',
                                                            'options' => ['class' => 'mb-3'],
                                                        ],
                                                        ArrayHelper::getValue($attribute, 'container_options', [])
                                                    )
                                                )->widget(
                                                    FileInput::className(),
                                                    ArrayHelper::merge(
                                                        [
                                                            'options' => [
                                                                'accept' => 'image/*',
                                                                'multiple' => $multiple,
                                                            ],
                                                            'pluginOptions' => array_merge(Yii::$app->params['fileInput_plugin_options'], [
                                                                'deleteUrl' => ArrayHelper::getValue($attribute, 'widget_options.delete_url'),
                                                                'initialPreview' => $initial_preview,
                                                                'initialPreviewConfig' => $initial_preview_config,
                                                            ]),
                                                            'pluginEvents' => [
                                                                'filesorted' => $multiple ? new JsExpression("function(event, params) {
                                                                    $.post('" . ArrayHelper::getValue($attribute, 'widget_options.sort_url', []) . "', {sort: params});
                                                                }") : 'false',
                                                            ],
                                                        ],
                                                        ArrayHelper::getValue($attribute, 'options', [])
                                                    )
                                                );
                                                break;
                                                
                                            case 'render':
                                                echo $this->render(
                                                    ArrayHelper::getValue($attribute, 'view'),
                                                    ArrayHelper::merge(
                                                        [
                                                            'model' => ArrayHelper::getValue($attribute, 'model', $model),
                                                            'form' => $form,
                                                        ],
                                                        ArrayHelper::getValue($attribute, 'params', []),
                                                    ),
                                                    Yii::$app->controller
                                                );
                                                break;
                                                
                                            default:
                                                echo $attribute;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            
            <?php if ($has_seo_meta) { ?>
                <div class="card mb-0">
                    <div class="card-header p-0 bg-primary">
                        <h5 class="m-0 position-relative">
                            <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-seo-meta">
                                <?= Yii::t('app', 'SEO мета') ?>
                                <i class="mdi mdi-chevron-down accordion-arrow"></i>
                            </a>
                        </h5>
                    </div>
                    
                    <div id="tab-seo-meta" class="collapse show">
                        <div class="card-body">
                            <?= $this->render('@backend/modules/Seo/views/meta/meta', [
                                'model' => $seo_meta_model ?? $model,
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-8 offset-sm-4">
                <div class="text-sm-end">
                    <?= Yii::$app->services->html->saveButtons($save_buttons, Url::to($form->action)) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
