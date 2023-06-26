<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use backend\modules\User\models\User;


class LoginForm extends Model
{
    private $user;
    
    public $username;
    public $password;
    
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'email'],
            [['password'], 'passwordValidation'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Адрес электронной почты'),
            'password' => Yii::t('app', 'Пароль'),
        ];
    }
    
    public function passwordValidation($attribute, $params)
    {
        $this->user = User::find()
            ->andWhere([
                'username' => $this->username,
            ])
            ->one();
        
        if (!$this->user || !$this->user->validatePassword($this->{$attribute})) {
            $this->addError($attribute, Yii::t('app', 'Неверный {attribute}', ['attribute' => $this->getAttributeLabel('password')]));
        }
    }
    
    public function login()
    {
        Yii::$app->user->login($this->user, 600 * 24 * 30);
        return $this->user;
    }
}
