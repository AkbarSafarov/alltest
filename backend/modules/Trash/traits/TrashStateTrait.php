<?php

namespace backend\modules\Trash\traits;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

use backend\modules\Trash\enums\TrashEnums;


trait TrashStateTrait
{
    public $state;
    public $default_state = 'active';
    public $redirect_route = ['index'];
    
    public function beforeSearch()
    {
        $state = Yii::$app->request->get('state', $this->default_state);
        
        if (!in_array($state, array_keys(TrashEnums::states()))) {
            return Yii::$app->controller->redirect($this->redirect_route);
        }
        
        $this->state = $state;
        
        return parent::beforeSearch();
    }
}