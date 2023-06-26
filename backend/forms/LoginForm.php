<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\modules\User\models\User;


class LoginForm extends Model
{
    private $user;
    
    public $username;
    public $password;
    public $remember_me = true;
    
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'email'],
            [['remember_me'], 'boolean'],
            [['password'], 'passwordValidation'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Логин'),
            'password' => Yii::t('app', 'Пароль'),
            'remember_me' => Yii::t('app', 'Запомнить меня'),
        ];
    }
    
    public function passwordValidation($attribute, $params)
    {
        $this->user = User::find()
            ->andWhere([
                'and',
                ['username' => $this->username],
                ['!=', 'role', 'student'],
            ])
            ->one();
        
        if (!$this->user || !$this->user->validatePassword($this->{$attribute})) {
            $this->addError($attribute, Yii::t('app', 'Неверный {attribute}', ['attribute' => $this->getAttributeLabel('password')]));
        }
    }
    
    public function login()
    {
        Yii::$app->user->login($this->user, $this->remember_me ? 3600 * 24 * 30 : 0);
        return $this->user;
    }
}
