<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\widgets\grid\GridView;

$this->title = Yii::t('app', 'Переводы');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="card">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-sm-8 offset-sm-4">
                <div class="text-sm-end">
                    <?= Html::a(
                        Html::tag('i', null, ['class' => 'fas fa-file-alt me-2']) . Yii::t('app', 'Импортировать'),
                        '#',
                        [
                            'class' => 'btn btn-success mb-2 me-1',
                            'data-sr-trigger' => 'ajax-button',
                            'data-sr-url' => Yii::$app->urlManager->createUrl(['translation/source/import']),
                            'data-sr-wrapper' => '#main-modal',
                            'data-sr-callback' => "$('#main-modal').modal('show');",
                        ]
                    ); ?>
                    
                    <?= Html::a(
                        Html::tag('i', null, ['class' => 'fas fa-file-alt me-2']) . Yii::t('app', 'Экспортировать'),
                        '#',
                        [
                            'class' => 'btn btn-warning mb-2',
                            'data-sr-trigger' => 'ajax-button',
                            'data-sr-url' => Yii::$app->urlManager->createUrl(['translation/source/export']),
                            'data-sr-wrapper' => '#main-modal',
                            'data-sr-callback' => "$('#main-modal').modal('show');",
                        ]
                    ); ?>
                </div>
            </div>
        </div>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'headerOptions' => [
                        'style' => 'width: 100px;'
                    ]
                ],
                'category',
                [
                    'attribute' => 'message',
                    'format' => 'raw',
                    'value' => fn ($model) => Html::a($model->message, ['update', 'id' => $model->id]),
                ],
                [
                    'attribute' => 'translations_tmp',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $lang = Yii::$app->language;
                        
                        $result[] = Html::textInput(
                            "TranslationSource[translations_tmp][$lang]",
                            ArrayHelper::getValue($model, 'currentTranslation.translation'),
                            [
                                'class' => 'form-control',
                                'data-trigger' => 'translationsourse-message-input',
                                'data-url' => Yii::$app->urlManager->createUrl(['translation/source/update-ajax', 'id' => $model->id]),
                            ]
                        );
                        
                        $result[] = Html::button(
                            Html::tag('i', null, ['class' => 'fas fa-check']),
                            [
                                'class' => 'btn btn-success d-none',
                                'data-trigger' => 'translationsourse-message-button',
                            ]
                        );
                        
                        return Html::tag('div', implode(null, $result), ['class' => 'd-flex']);
                    },
                    'contentOptions' => [
                        'style' => 'max-width: 300px; white-space: normal;',
                    ],
                ],
                [
                    'attribute' => 'has_translation',
                    'format' => 'boolean',
                    'value' => fn ($model) => (bool)ArrayHelper::getValue($model, 'currentTranslation.translation'),
                ],
            ],
        ]); ?>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(e) {
        let el, url, sendData = {};
        
        $(document).on('keydown', '[data-trigger*="translationsourse-message-input"]', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $(this).parent().find('[data-trigger*="translationsourse-message-button"]').trigger('click');
            }
        });
        
        $(document).on('focusin', '[data-trigger*="translationsourse-message-input"]', function(event) {
            $(this).parent().find('[data-trigger*="translationsourse-message-button"]').removeClass('d-none');
        });
        
        $(document).on('focusout', '[data-trigger*="translationsourse-message-input"]', function(event) {
            if (event.relatedTarget !== $(this).parent().find('[data-trigger*="translationsourse-message-button"]')[0]) {
                $(this).parent().find('[data-trigger*="translationsourse-message-button"]').addClass('d-none');
            }
        });
        
        $(document).on('click', '[data-trigger*="translationsourse-message-button"]', function(event) {
            el = $(this).parent().find('[data-trigger*="translationsourse-message-input"]');
            url = el.data('url');
            
            sendData['_csrf-backend'] = $('meta[name="csrf-token"]').attr('content');
            sendData[el.attr('name')] = el.val();
            
            $.ajax({
                url: url,
                type: 'post',
                data: sendData,
                success: (data) => {
                    $(this).addClass('d-none');
                },
            });
        });
    });
</script>
