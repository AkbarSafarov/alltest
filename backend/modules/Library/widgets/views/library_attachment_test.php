<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<table class="table table-bordered table-striped table-relations">
    <tbody class="template-tests-wrapper" data-toggle="sortable">
        <?php
            foreach ($model->attachmentTests as $attachment_test) {
                echo Html::tag('tr', $this->render('library_attachment_test/test', [
                    'model' => $attachment_test,
                    'form' => $form,
                    'input_types' => $input_types,
                ]));
            }
        ?>
    </tbody>
    
    <tfoot>
        <tr>
            <td colspan="3">
                <?php
                    foreach ($input_types as $key => $input_type) {
                        $buttons[] = Html::button(
                            Html::tag('i', null, ['class' => 'fas fa-plus me-2']) . $input_type['label'],
                            [
                                'class' => 'btn btn-success mx-1',
                                'data-sr-trigger' => 'ajax-button',
                                'data-sr-url' => Yii::$app->urlManager->createUrl(['library/attachment-test/add', 'input_type' => $key]),
                                'data-sr-wrapper' => '.template-tests-wrapper',
                                'data-sr-insert-type' => 'append',
                            ]
                        );
                    }
                    
                    echo Html::tag('div', implode(null, $buttons));
                ?>
            </td>
        </tr>
    </tfoot>
</table>
