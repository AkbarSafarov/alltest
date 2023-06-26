<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'Просмотр: {value}', ['value' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <div class="accordion custom-accordion">
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-information">
                            <?= Yii::t('app', 'Информация') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-information" class="collapse show">
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table m-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'student_id',
                                    'format' => 'raw',
                                    'value' => fn ($model) => ArrayHelper::getValue($model->student, 'username'),
                                ],
                                [
                                    'attribute' => 'promocode_id',
                                    'format' => 'raw',
                                    'value' => fn ($model) => ArrayHelper::getValue($model->promocode, 'key'),
                                ],
                                'total_price:decimal',
                                'discount_price:decimal',
                                'promocode_price:decimal',
                                'checkout_price:decimal',
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => fn ($model) => Html::tag('b', ArrayHelper::getValue($model->enums->statuses(), "$model->status.label")),
                                ],
                                'created_at',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-0">
                <div class="card-header p-0 bg-primary">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title p-3 text-white d-block" data-bs-toggle="collapse" href="#tab-products">
                            <?= Yii::t('app', 'Продукты') ?>
                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                
                <div id="tab-products" class="collapse show">
                    <div class="card-body">
                        <?= $this->render('view/products', [
                            'model' => $model,
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
