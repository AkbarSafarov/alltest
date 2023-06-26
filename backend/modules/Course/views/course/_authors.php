<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use alexantr\elfinder\InputFile;

use backend\modules\Course\models\CourseAuthor;

$relations = ArrayHelper::merge($model->authors, [new CourseAuthor]);

?>

<table class="table table-bordered table-striped table-relations">
    <thead>
        <tr>
            <th style="width: 50px;"></th>
            <th style="width: 200px;"><?= $relations[0]->getAttributeLabel('image') ?></th>
            <th><?= $relations[0]->getAttributeLabel('full_name') ?></th>
            <th><?= $relations[0]->getAttributeLabel('experience') ?></th>
            <th style="width: 50px;"></th>
        </tr>
    </thead>
    
    <tbody data-toggle="sortable">
        <?php foreach ($relations as $value) { ?>
            <?php $value_id = $value->isNewRecord ? '__key__' : $value->id ?>
            
            <tr class="<?= $value->isNewRecord ? 'table-new-relation' : null ?>" data-table="authors">
                <td>
                    <div class="btn btn-primary table-sorter">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </td>
                
                <td>
                    <?= $form->field($value, 'image', [
                        'enableClientValidation' => false,
                        'template' => '{input}',
                        'options' => [
                            'class' => null,
                            'data-toggle' => 'elfinder',
                            'data-filter' => 'image',
                        ],
                    ])->widget(InputFile::className(), [
                        'options' => [
                            'id' => "course-author-image-$value_id",
                            'name' => "Course[authors_tmp][$value_id][image]",
                        ]
                    ]) ?>
                </td>
                
                <td>
                    <?= $form->field($value, 'full_name', [
                        'template' => '{input}',
                        'options' => ['class' => null],
                    ])->textInput([
                        'name' => "Course[authors_tmp][$value_id][full_name]",
                    ]) ?>
                </td>
                
                <td>
                    <?= $form->field($value, 'experience', [
                        'template' => '{input}',
                        'options' => ['class' => null],
                    ])->textArea([
                        'name' => "Course[authors_tmp][$value_id][experience]",
                        'rows' => 5,
                    ]) ?>
                </td>
                
                <td>
                    <button type="button" class="btn btn-danger btn-remove">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    
    <tfoot>
        <tr>
            <td colspan="5">
                <button type="button" class="btn btn-success w-100 btn-add" data-table="authors">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    </tfoot>
</table>
