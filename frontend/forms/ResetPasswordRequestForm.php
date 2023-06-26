<?php

namespace frontend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

use backend\modules\User\models\User;


class ResetPasswordRequestForm extends Model
{
    public $username;
    
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'email'],
            [
                ['username'],
                'exist',
                'targetClass' => '\backend\modules\User\models\User',
                'message' => Yii::t('app', 'Email не найден'),
            ],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Адрес электронной почты'),
        ];
    }
    
    public function sendEmail()
    {
        $user = User::find()
            ->where([
                'username' => $this->username,
            ])
            ->one();
        
        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            
            if (!$user->save()) {
                return false;
            }
        }
        
        return Yii::$app->services->mail->send($user->username, Yii::t('app_mail', 'Инструкция для восстановление пароля'), 'password_reset', [
            'token' => $user->password_reset_token,
        ]);
    }
}
