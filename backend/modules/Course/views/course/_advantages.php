<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use alexantr\elfinder\InputFile;

use backend\modules\Course\models\CourseAdvantage;

$relations = ArrayHelper::merge($model->advantages, [new CourseAdvantage]);

?>

<table class="table table-bordered table-striped table-relations">
    <thead>
        <tr>
            <th style="width: 50px;"></th>
            <th><?= $relations[0]->getAttributeLabel('name') ?></th>
            <th><?= $relations[0]->getAttributeLabel('icon') ?></th>
            <th style="width: 50px;"></th>
        </tr>
    </thead>
    
    <tbody data-toggle="sortable">
        <?php foreach ($relations as $value) { ?>
            <?php $value_id = $value->isNewRecord ? '__key__' : $value->id ?>
            
            <tr class="<?= $value->isNewRecord ? 'table-new-relation' : null ?>" data-table="advantages">
                <td>
                    <div class="btn btn-primary table-sorter">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </td>
                
                <td>
                    <?= $form->field($value, 'name', [
                        'template' => '{input}',
                        'options' => ['class' => null],
                    ])->textInput([
                        'name' => "Course[advantages_tmp][$value_id][name]",
                    ]) ?>
                </td>
                
                <td>
                    <?= $form->field($value, 'icon', [
                        'enableClientValidation' => false,
                        'template' => '{input}',
                        'options' => [
                            'class' => null,
                            'data-toggle' => 'elfinder',
                            'data-filter' => 'image',
                        ],
                    ])->widget(InputFile::className(), [
                        'options' => [
                            'id' => "course-advantages-icon-$value_id",
                            'name' => "Course[advantages_tmp][$value_id][icon]",
                        ]
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
                <button type="button" class="btn btn-success w-100 btn-add" data-table="advantages">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    </tfoot>
</table>
