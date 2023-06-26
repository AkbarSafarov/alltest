<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use wbraganca\fancytree\FancytreeWidget;

use backend\modules\Trash\widgets\TrashStateTabsWidget;

$this->title = Yii::t('app', 'Меню');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= TrashStateTabsWidget::widget(['model' => $root_unit, 'states' => ['active', 'archive']]) ?>
        
        <div class="text-sm-end mb-2">
            <?= Html::a(
                Html::tag('i', null, ['class' => 'fas fa-plus-square me-2']) . Yii::t('app', 'Создание'),
                ['tree'],
                ['class' => 'btn btn-info']
            ) ?>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div id="nav-item-content"></div>
            </div>
        
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary p-3">
                        <h5 class="m-0 text-white">
                            <?= Yii::t('app', 'Древо') ?>
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <?= Html::textInput('fancytree_search', null, [
                                'class' => 'form-control',
                                'placeholder' => Yii::t('app', 'Поиск'),
                            ]) ?>
                        </div>
                        
                        <?= FancytreeWidget::widget([
                            'pluginOptions' => [
                                'data-action_create' => Yii::$app->urlManager->createUrl(['menu/menu/create']),
                                'data-action_update' => Yii::$app->urlManager->createUrl(['menu/menu/update', 'state' => $root_unit->state]),
                                'data-action_delete' => Yii::$app->urlManager->createUrl(['menu/menu/delete']),
                                'data-action_expand' => Yii::$app->urlManager->createUrl(['menu/menu/expand']),
                                'data-action_move' => Yii::$app->urlManager->createUrl(['menu/menu/move']),
                            ],
                            'options' => [
                                'source' => $data,
                                'extensions' => ['dnd', 'filter'],
                                'init' => new JsExpression('function(event, data) {
                                    $("#nav-item-content").load(data.tree.$div.data("action_create"));
                                }'),
                                'activate' => $root_unit->state == 'active' ? new JsExpression('function(event, data) {
                                    $("#nav-item-content").load(data.tree.$div.data("action_update") + "&id=" + data.node.data.id);
                                }') : new JsExpression('function(event, data) {
                                    $.post(data.tree.$div.data("action_delete") + "?id=" + data.node.data.id, {
                                        "_csrf-backend": $("mta[name=csrf-token]").attr("content"),
                                    });
                                }'),
                                'collapse' => new JsExpression('function(event, data) {
                                    $.get(data.tree.$div.data("action_expand"), {
                                        id: data.node.data.id
                                    });
                                }'),
                                'expand' => new JsExpression('function(event, data) {
                                    $.get(data.tree.$div.data("action_expand"), {
                                        id: data.node.data.id
                                    });
                                }'),
                                'dnd' => $root_unit->state == 'active' ? [
                                    'preventVoidMoves' => true,
                                    'preventRecursiveMoves' => true,
                                    'autoExpandMS' => 400,
                                    'dragStart' => new JsExpression('function(node, data) {
                                        return true;
                                    }'),
                                    'dragEnter' => new JsExpression('function(node, data) {
                                        return true;
                                    }'),
                                    'dragDrop' => new JsExpression('function(node, data) {
                                        $.get(data.tree.$div.data("action_move"), {
                                            item: data.otherNode.data.id,
                                            action: data.hitMode,
                                            second: node.data.id
                                        }, function() {
                                            data.otherNode.moveTo(node, data.hitMode);
                                        });
                                    }'),
                                ] : null,
                                'filter' => [
                                    'autoExpand' => true,
                                    'highlight' => false,
                                    'mode' => 'hide',
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let tree;
        
        $('input[name="fancytree_search"]').on('keyup', function(e) {
            tree = $.ui.fancytree.getTree();
            tree.filterBranches.call(tree, $(this).val(), {});
        });
    });
</script>
