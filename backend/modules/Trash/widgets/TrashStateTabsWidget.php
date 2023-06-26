<?php

namespace backend\modules\Trash\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

use backend\modules\Trash\enums\TrashEnums;


class TrashStateTabsWidget extends Widget
{
    public Model $model;
    public array $states;
    
    public function run()
    {
        return $this->render('trash_state_tabs', [
            'model' => $this->model,
            'states' => ArrayHelper::filter(TrashEnums::states(), $this->states),
        ]);
    }
}
