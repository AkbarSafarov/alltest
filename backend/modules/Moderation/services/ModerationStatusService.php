<?php

namespace backend\modules\Moderation\services;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use speedrunner\services\ActiveService;

use backend\modules\Moderation\models\ModerationStatus;
use backend\modules\Moderation\enums\ModerationEnums;


class ModerationStatusService extends ActiveService
{
    public function __construct(?ModerationStatus $model = null)
    {
        $this->model = $model;
    }
    
    public static function tableButtons($model)
    {
        $statuses = ModerationEnums::statuses();
        
        $buttons = array_map(function ($value, $key) use ($model, $statuses) {
            if (in_array($key, ArrayHelper::getValue($statuses, "$model->status.can_change", []))) {
                switch (StringHelper::basename($model->className())) {
                    case 'CourseUnit':
                        $page_url = Yii::$app->urlManager->createUrl(['/course/unit/index', 'course_id' => $model->tree]);
                        
                        $html = Html::beginForm([
                            '/moderation/status/create',
                            'model_class' => StringHelper::basename($model::className()),
                            'new_value' => $key,
                            'redirect' => false,
                        ], 'post', [
                            'class' => 'mx-1',
                            'data-sr-trigger' => 'ajax-form',
                            'data-sr-callback' => Yii::$app->services->trash->ajaxReload($page_url),
                        ]);
                        
                        $html .= Html::hiddenInput('selection[]', $model->id);
                        
                        $html .= Html::submitButton(
                            Html::tag('i', null, ['class' => $value['icon']]),
                            [
                                'onclick' => 'return confirm("' . Yii::t('app', 'Вы уверены?') . '");',
                                'class' => 'btn btn-' . $value['css_class'],
                                'title' => $value['action_label'],
                            ]
                        );
                        
                        $html .= Html::endForm();
                        
                        return $html;
                        
                    case 'LibraryTest':
                        return Html::submitButton(
                            Html::tag('i', null, ['class' => 'me-2 ' . $value['icon']]) . $value['action_label'],
                            [
                                'formaction' => Yii::$app->urlManager->createUrl([
                                    '/moderation/status/create',
                                    'model_class' => StringHelper::basename($model::className()),
                                    'new_value' => $key,
                                ]),
                                'onclick' => 'return confirm("' . Yii::t('app', 'Вы уверены?') . '")',
                                'class' => "btn btn-{$value['css_class']}",
                            ]
                        );
                }
            }
        }, $statuses, array_keys($statuses));
        
        return array_filter($buttons);
    }
}