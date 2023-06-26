<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<?php foreach ($template_sections as $language_id => $template_section) { ?>
    <?php
        $buttons[] = Html::button(
            Html::tag('i', null, ['class' => 'fas fa-plus me-2']) . $template_section['language'],
            [
                'class' => 'btn btn-success mx-1',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => "#available-templates-modal-$language_id",
            ]
        );
    ?>
    
    <div class="modal fade" id="available-templates-modal-<?= $language_id ?>">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <?= $template_section['language'] ?>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="card nav flex-column nav-pills nav-pills-tab" role="tablist">
                                <li class="nav-item">
                                    <?= Html::a(
                                        Yii::t('app', 'Поиск'),
                                        "#tab-available-templates-$language_id-search",
                                        [
                                            'class' => 'nav-link active',
                                            'data-bs-toggle' => 'pill',
                                        ]
                                    ) ?>
                                </li>
                                
                                <?php foreach ($template_section['template_groups'] as $template_category_id => $template_group) { ?>
                                    <li class="nav-item">
                                        <?= Html::a(
                                            $template_group['category'],
                                            "#tab-available-templates-$language_id-$template_category_id",
                                            [
                                                'class' => 'nav-link',
                                                'data-bs-toggle' => 'pill',
                                            ]
                                        ) ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        
                        <div class="col-md-9">
                            <div class="tab-content card p-0">
                                <div id="<?= "tab-available-templates-$language_id-search" ?>" class="tab-pane active">
                                    <div class="p-2">
                                        <?= Html::textInput(null, null, [
                                            'class' => 'form-control',
                                            'placeholder' => Yii::t('app', 'Поиск...'),
                                            'data-sr-trigger' => 'ajax-keypress',
                                            'data-sr-url' => Yii::$app->urlManager->createUrl([
                                                'library/attachment-template/search', 'language_id' => $language_id,
                                            ]),
                                            'data-sr-wrapper' => "#available-templates-search-wrapper-$language_id",
                                            'data-sr-min' => 0,
                                        ]) ?>
                                    </div>
                                    
                                    <div id="available-templates-search-wrapper-<?= $language_id ?>"></div>
                                </div>
                                
                                <?php foreach ($template_section['template_groups'] as $template_category_id => $template_group) { ?>
                                    <div id="<?= "tab-available-templates-$language_id-$template_category_id" ?>" class="tab-pane fade">
                                        <?= $this->render('library_attachment_template/templates', [
                                            'templates' => $template_group['templates'],
                                        ]); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<table class="table table-bordered table-striped table-relations">
    <tbody class="template-templates-wrapper" data-toggle="sortable">
        <?php foreach ($template_groups as $templates) { ?>
            <tr>
                <td style="width: 50px;">
                    <div class="btn btn-primary table-sorter">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </td>
                
                <td>
                    <div class="card-header bg-primary text-white text-center">
                        <?= ArrayHelper::getValue($templates, '0.template_information.name') ?>
                    </div>
                    
                    <div class="card-body p-3 border">
                        <?php foreach ($templates as $template) { ?>
                            <div class="mb-3 mb-3">
                                <?= $this->render('library_attachment_template/input', [
                                    'key' => $template->id,
                                    'label' => $template->label,
                                    'input_type' => $template->input_type,
                                    'template_id' => ArrayHelper::getValue($template, 'template_information.id'),
                                    'group' => $template->group,
                                    'value' => $template->value,
                                ]) ?>
                            </div>
                        <?php } ?>
                    </div>
                </td>
                
                <td style="width: 50px;">
                    <button type="button" class="btn btn-danger btn-remove">
                        <span class="fas fa-times"></span>
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    
    <tfoot>
        <tr>
            <td colspan="3">
                <?= Html::tag('div', implode(null, $buttons ?? [])); ?>
            </td>
        </tr>
    </tfoot>
</table>
