<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;

use backend\modules\Library\models\LibraryAttachmentTestPack;
use backend\modules\Library\models\LibraryTestCategory;

$relations = ArrayHelper::merge($model->attachmentTestPacks, [new LibraryAttachmentTestPack]);

?>

<table class="table table-striped table-bordered table-relations">
    <tbody data-toggle="sortable">
        <?php foreach ($relations as $value) { ?>
            <?php $value_id = $value->isNewRecord ? '__key__' : $value->id ?>
            
            <tr class="<?= $value->isNewRecord ? 'table-new-relation' : null ?>" data-table="attachment-test-packs">
                <td style="width: 50px;">
                    <div class="btn btn-primary table-sorter">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </td>
                
                <td>
                    <?php
                        if ($value->isNewRecord) {
                            echo $form->field($value, 'subject_id', [
                                'template' => '{label}{input}',
                                'options' => ['class' => 'mb-3'],
                            ])->dropDownList(
                                [],
                                [
                                    'name' => "LibraryAttachmentTestPack[$value_id][subject_id]",
                                    'data-toggle' => 'select2-ajax',
                                    'data-action' => Yii::$app->urlManager->createUrl(['items-list/library-test-subjects']),
                                    'data-sr-trigger' => 'ajax-change',
                                    'data-sr-url' => Yii::$app->urlManager->createUrl(['library/test-category/filtered-by-subject']),
                                    'data-sr-wrapper' => "#libraryattachmenttestpack-category_ids-$value_id",
                                ]
                            );
                            
                            echo $form->field($value, 'category_ids', [
                                'template' => '{label}{input}',
                                'options' => ['class' => 'mb-3'],
                            ])->dropDownList(
                                [],
                                [
                                    'name' => "LibraryAttachmentTestPack[$value_id][category_ids]",
                                    'id' => "libraryattachmenttestpack-category_ids-$value_id",
                                    'data-toggle' => 'select2',
                                    'multiple' => true,
                                ]
                            );
                            
                            echo $form->field($value, 'input_types', [
                                'template' => '{label}{input}',
                                'options' => ['class' => 'mb-3'],
                            ])->dropDownList(
                                ArrayHelper::getColumn($input_types, 'label'),
                                [
                                    'name' => "LibraryAttachmentTestPack[$value_id][input_types]",
                                    'data-toggle' => 'select2',
                                    'placeholder' => '',
                                    'multiple' => true,
                                ]
                            );
                        } else {
                            echo $form->field($value, 'subject_id', [
                                'template' => '{label}{input}',
                                'options' => ['class' => 'mb-3'],
                            ])->widget(Select2::className(), [
                                'data' => [$value->subject_id => ArrayHelper::getValue($value, 'subject.name')],
                                'options' => [
                                    'name' => "LibraryAttachmentTestPack[$value_id][subject_id]",
                                    'id' => "libraryattachmenttestpack-subject_id-$value_id",
                                    'class' => 'test-pack-subject',
                                    'placeholder' => '',
                                    'data-sr-trigger' => 'ajax-change',
                                    'data-sr-url' => Yii::$app->urlManager->createUrl([
                                        'library/test-category/filtered-by-subject',
                                        'category_ids' => $value->category_ids,
                                    ]),
                                    'data-sr-wrapper' => "#libraryattachmenttestpack-category_ids-$value_id",
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false,
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl(['items-list/library-test-subjects']),
                                        'dataType' => 'json',
                                        'delay' => 300,
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ],
                            ]);
                            
                            echo $form->field($value, 'category_ids', [
                                'template' => '{label}{input}',
                                'options' => ['class' => 'mb-3'],
                            ])->widget(Select2::className(), [
                                'options' => [
                                    'name' => "LibraryAttachmentTestPack[$value_id][category_ids]",
                                    'id' => "libraryattachmenttestpack-category_ids-$value_id",
                                    'placeholder' => '',
                                    'multiple' => true,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]);
                            
                            echo $form->field($value, 'input_types', [
                                'template' => '{label}{input}',
                                'options' => ['class' => 'mb-3'],
                            ])->widget(Select2::className(), [
                                'data' => ArrayHelper::getColumn($input_types, 'label'),
                                'options' => [
                                    'name' => "LibraryAttachmentTestPack[$value_id][input_types]",
                                    'id' => "libraryattachmenttestpack-input_types-$value_id",
                                    'placeholder' => '',
                                    'multiple' => true,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]);
                        }
                        
                        echo $form->field($value, 'quantity', [
                            'template' => '{label}{input}',
                            'options' => ['class' => 'mb-3'],
                        ])->textInput([
                            'type' => 'number',
                            'name' => "LibraryAttachmentTestPack[$value_id][quantity]",
                            'min' => 1,
                            'max' => 100,
                            'required' => true,
                        ]);
                    ?>
                </td>
                
                <td style="width: 50px;">
                    <button type="button" class="btn btn-danger btn-remove">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    
    <tfoot>
        <tr>
            <td colspan="3">
                <button type="button" class="btn btn-success w-100 btn-add" data-table="attachment-test-packs">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    </tfoot>
</table>


<?= $this->registerJs("
    $('.test-pack-subject').change();
") ?>
