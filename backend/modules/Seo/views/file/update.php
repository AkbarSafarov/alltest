<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\crud\UpdateWidget;

$this->title = Yii::t('app', 'Редактирование файлов');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SEO'), 'url' => '#'];
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo UpdateWidget::widget([
    'model' => $model,
    'form_options' => [
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ],
    'save_buttons' => ['save'],
    'tabs' => [
        'robots' => [
            'label' => 'Robots.txt',
            'attributes' => [
                [
                    'name' => 'robots',
                    'type' => 'text_area',
                    'container_options' => [
                        'options' => ['class' => 'mb-3'],
                    ],
                    'options' => [
                        'rows' => 30,
                    ],
                ],
            ],
        ],
        
        'sitemap' => [
            'label' => 'Sitemap.xml',
            'attributes' => [
                [
                    'name' => 'sitemap',
                    'type' => 'text_input',
                    'container_options' => [
                        'options' => ['class' => 'mb-3'],
                    ],
                    'options' => [
                        'type' => 'file',
                        'style' => 'height: auto;',
                    ],
                ],
            ],
        ],
    ],
]);
