<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Аналитика: проданные продукты');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?= Html::beginForm(false, 'get', [
    'id' => 'analytics-filter-form',
]) ?>

<div id="analytics-filter-wrapper">
    <div class="card mb-0">
        <div class="card-body">
            <?php
                foreach ($filters as $key => $filter) {
                    echo $this->render('_filter', [
                        'enums' => $enums,
                        'key' => $key,
                        'filter' => $filter,
                    ]);
                }
            ?>
        </div>
    </div>
</div>

<?= Html::endForm() ?>

<div class="btn-group w-100">
    <?= Html::beginForm(['/analytics/chart/add-filter'], 'get', [
        'class' => 'w-100',
        'data-sr-trigger' => 'ajax-form',
        'data-sr-wrapper' => '#analytics-filter-wrapper > .card > .card-body',
        'data-sr-insert-type' => 'append',
        'data-sr-callback' => 'el.find("[name=key]").val(parseInt(el.find("[name=key]").val()) + 1)',
    ]) ?>
    
    <?= Html::submitButton(
        Html::tag('i', null, ['class' => 'fa fa-plus me-2']) . Yii::t('app', 'Добавить фильтр'),
        [
            'class' => 'btn btn-success btn-lg w-100',
        ]
    ) ?>
    
    <?= Html::hiddenInput('key', count($filters)) ?>
    <?= Html::endForm() ?>
    
    <?= Html::submitButton(
        Html::tag('i', null, ['class' => 'fa fa-filter me-2']) . Yii::t('app', 'Применить'),
        [
            'class' => 'btn btn-primary btn-lg w-100',
            'form' => 'analytics-filter-form',
        ]
    ) ?>
    
    <?= Html::resetButton(
        Html::tag('i', null, ['class' => 'fa fa-power-off me-2']) . Yii::t('app', 'Сбросить'),
        [
            'class' => 'btn btn-danger btn-lg w-100',
            'form' => 'analytics-filter-form',
        ]
    ) ?>
</div>

<hr class="my-3">

<div class="accordion custom-accordion">
    <div class="card mb-0">
        <div class="card-header p-0 bg-primary">
            <h5 class="m-0 position-relative">
                <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-analytics">
                    <?= Yii::t('app', 'Аналитика') ?>
                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                </a>
            </h5>
        </div>
        
        <div id="tab-analytics" class="collapse show">
            <div class="card-body">
                <?php foreach ($price_groups as $key => $prices) { ?>
                    <h5 class="fw-bold">
                        <?= Yii::t('app', 'Запрос №{number}', ['number' => $key + 1]) ?>
                    </h5>
                    
                    <div class="row mb-4">
                        <?php foreach ($enums->orderPrices() as $key => $order_price) { ?>
                            <div class="col-md">
                                <div class="alert alert-<?= $order_price['css_class'] ?> m-0">
                                    <strong><?= $order_price['label'] ?>:</strong>
                                    <?= Yii::$app->formatter->asDecimal($prices[$key]) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                
                <canvas id="chart"></canvas>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let totalPrice, totalQuantity;
                        
                        new Chart(document.getElementById('chart').getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: <?= json_encode($months, JSON_UNESCAPED_UNICODE) ?>,
                                datasets: <?= json_encode($datasets, JSON_UNESCAPED_UNICODE) ?>
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: '<?= Yii::t('app', 'Аналитика') ?>'
                                },
                                scales: {
                                    yAxes: [{
                                        display: true,
                                        ticks: {
                                            suggestedMin: 0
                                        }
                                    }]
                                },
                                tooltips: {
                                    titleFontSize: 15,
                                    bodyFontSize: 13,
                                    callbacks: {
                                        title: function(tooltipItem, data) {
                                            return [
                                                data.datasets[tooltipItem[0].datasetIndex].label,
                                                tooltipItem[0].label
                                            ];
                                        },
                                        label: function(tooltipItem, data) {
                                            totalPrice = parseInt(tooltipItem.value).toLocaleString('ru-RU');
                                            totalQuantity = data.datasets[tooltipItem.datasetIndex].totalQuantity[tooltipItem.index];
                                            
                                            return [
                                                ' ' + data.datasets[tooltipItem.datasetIndex].label + ': ' + totalPrice,
                                                ' <?= ArrayHelper::getValue($enums->orderPrices(), 'total_quantity.label') ?>: ' + totalQuantity
                                            ];
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
