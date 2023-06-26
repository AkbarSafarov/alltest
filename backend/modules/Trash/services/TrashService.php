<?php

namespace backend\modules\Trash\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\Trash\models\Trash;


class TrashService
{
    public static function isDeleted($model)
    {
        return Trash::find()->andWhere(['trash_model_class' => StringHelper::basename($model->className()), 'trash_model_id' => $model->id])->exists();
    }
    
    public static function ajaxReload($action = null, $hide_password_modal = true)
    {
        $action = $action ?? Yii::$app->request->url;
        $password_modal_script = $hide_password_modal ? "$('#password-modal').modal('hide');" : null;
        
        return "$.get('$action', function(data) {
            document.getElementById('content-wrapper').innerHTML = data;
            $password_modal_script
        });";
    }
}