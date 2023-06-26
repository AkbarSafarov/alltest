<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'SEO мета');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SEO'), 'url' => '#'];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?php $form = ActiveForm::begin([
    'options' => ['id' => 'update-form'],
]); ?>

<div class="card">
    <div class="card-body">
        <div class="text-sm-end mb-3">
            <?= Yii::$app->services->html->saveButtons(['save']) ?>
        </div>
        
        <div class="accordion custom-accordion">
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
                        <?= $this->render('meta', [
                            'model' => $model,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-sm-end mt-3">
            <?= Yii::$app->services->html->saveButtons(['save']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
