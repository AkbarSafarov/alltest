<?php

namespace backend\modules\User\enums;

use Yii;
use yii\helpers\ArrayHelper;


class UserEnums
{
    private static $roles = null;
    
    public function __construct()
    {
        if (self::$roles === null) {
            $roles = ArrayHelper::toArray(Yii::$app->authManager->getRoles());
            
            self::$roles = ArrayHelper::getColumn($roles, function ($value) {
                return ['label' => ucfirst($value['name'])];
            });
        }
        
        return self::$roles;
    }
    
    public static function roles()
    {
        return array_map(fn($value) => ['label' => Yii::t('app_role', $value['label'])], self::$roles);
    }
    
    public static function genders()
    {
        return [
            'male' => [
                'label' => Yii::t('app', 'Male'),
            ],
            'female' => [
                'label' => Yii::t('app', 'Female'),
            ],
        ];
    }
}