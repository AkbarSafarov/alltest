<?php

namespace backend\modules\Moderation\behaviors;

use Yii;
use yii\base\Behavior;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\Moderation\models\ModerationStatus;


class ModerationStatusBehavior extends Behavior
{
    public string $value = 'development';
    public array $exclude_values = ['development'];
    public array $scenarios = [];
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }
    
    public function afterSave($event)
    {
        if (in_array($this->owner->scenario, $this->scenarios)) {
            if (!in_array($this->owner->status, $this->exclude_values)) {
                $model = new ModerationStatus();
                $model->model_class = StringHelper::basename($this->owner->className());
                $model->model_id = $this->owner->id;
                $model->new_value = $this->value;
                $model->save(false);
            }
        }
    }
}
