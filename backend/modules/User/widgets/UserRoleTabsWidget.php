<?php

namespace backend\modules\User\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

use backend\modules\User\enums\UserEnums;


class UserRoleTabsWidget extends Widget
{
    public Model $model;
    public array $roles;
    
    public function run()
    {
        $roles = ArrayHelper::filter(UserEnums::roles(), $this->roles);
        $roles = ArrayHelper::merge([
            null => ['label' => Yii::t('app', 'Все')],
        ], $roles);
        
        return $this->render('user_role_tabs', [
            'model' => $this->model,
            'roles' => $roles,
        ]);
    }
}
