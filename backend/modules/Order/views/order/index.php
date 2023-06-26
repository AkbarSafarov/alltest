<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Заказы');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <?= $trash_state_tabs_widget ?>
        
        <?= $this->render('index/search', ['model' => $searchModel]) ?>
        <?= $this->render('index/prices', ['query' => $dataProvider->query]) ?>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'backend\widgets\grid\ActionColumn',
                    'template' => '{delete}',
                ],
                [
                    'attribute' => 'id',
                    'format' => 'raw',
                    'value' => function ($model) use ($searchModel) {
                        return $searchModel->state != 'archive' ? Html::a($model->id, ['view', 'id' => (int)$model->id]) : $model->id;
                    },
                    'headerOptions' => [
                        'style' => 'width: 100px;'
                    ]
                ],
                [
                    'attribute' => 'student_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->student->username . '<hr>' . $model->student->phone;
                    },
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'promocode_id',
                    'format' => 'raw',
                    'value' => fn ($model) => ArrayHelper::getValue($model->promocode, 'key'),
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'checkout_price',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $prices = ['total_price', 'discount_price', 'promocode_price'];
                        
                        foreach ($prices as $p) {
                            $result[] = $model->getAttributeLabel($p) . ': ' . Yii::$app->formatter->asDecimal(ArrayHelper::getValue($model, $p, 0));
                        }
                        
                        $result[] = '<hr>' . Html::tag(
                            'b',
                            $model->getAttributeLabel('checkout_price') . ': ' . Yii::$app->formatter->asDecimal($model->checkout_price)
                        );
                        
                        return implode('<br>', $result);
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => fn ($model) => ArrayHelper::getValue($model->enums->statuses(), "$model->status.label"),
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'products_tmp',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return implode(',<br>', ArrayHelper::getColumn($model->products, 'model_json.name'));
                    },
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'created_at',
                    'enableSorting' => false,
                ],
            ],
        ]); ?>
    </div>
</div>
