<?php

namespace backend\modules\Moderation\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

use backend\modules\Moderation\enums\ModerationEnums;


class ModerationStatusTabsWidget extends Widget
{
    public Model $model;
    public array $available_statuses = [];
    
    public function run()
    {
        $statuses = ArrayHelper::merge(
            [
                null => ['label' => Yii::t('app', 'Все')],
            ],
            ArrayHelper::filter(ModerationEnums::statuses(), $this->available_statuses)
        );
        
        return $this->render('moderation_status_tabs', [
            'model' => $this->model,
            'statuses' => $statuses,
        ]);
    }
}
