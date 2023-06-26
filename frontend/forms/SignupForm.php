<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;

use backend\modules\User\models\User;
use backend\modules\User\enums\UserEnums;


class SignupForm extends Model
{
    public $username;
    public $nickname;
    public $password;
    
    public $full_name;
    public $gender;
    public $birth_date;
    public $phone;
    public $parent_phone;
    public $address;
    
    public function init()
    {
        $this->username = Yii::$app->request->get('username');
        $this->password = Yii::$app->request->get('password');
        $this->nickname = Yii::$app->request->get('nickname');
        $this->full_name = Yii::$app->request->get('full_name');
        
        return parent::init();
    }
    
    public function rules()
    {
        return [
            [['username', 'password', 'nickname', 'full_name', 'gender', 'birth_date', 'phone', 'parent_phone'], 'required'],
            [['username'], 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'Данный email уже занят')],
            [['username'], 'email'],
            [['username', 'nickname', 'full_name', 'address'], 'string', 'max' => 100],
            [['nickname'], 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'Данное имя уже занято')],
            [['nickname'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => Yii::t('app', 'Поле должно содержать только латинские буквы и цифры')],
            [['gender'], 'in', 'range' => array_keys($this->genders())],
            [['birth_date'], 'date', 'format' => 'php: d.m.Y'],
            
            [['phone', 'parent_phone'], 'match', 'pattern' => '/998[0-9]{9}/', 'enableClientValidation' => false],
            [['parent_phone'], 'compare', 'compareAttribute' => 'phone', 'operator' => '!=', 'message' => Yii::t('app', 'Телефон родителя должен отличаться от Вашего телефона')],
            
            [['password'], 'string', 'min' => 8, 'max' => 50],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Логин (адрес электронной почты)'),
            'nickname' => Yii::t('app', 'Имя пользователя (публичное)'),
            'password' => Yii::t('app', 'Пароль'),
            
            'full_name' => Yii::t('app', 'ФИО'),
            'gender' => Yii::t('app', 'Пол'),
            'birth_date' => Yii::t('app', 'Дата рождения'),
            'phone' => Yii::t('app', 'Телефон'),
            'parent_phone' => Yii::t('app', 'Телефон родителя'),
            'address' => Yii::t('app', 'Место проживания'),
        ];
    }
    
    public static function genders()
    {
        return UserEnums::genders();
    }
    
    public function beforeValidate()
    {
        $this->phone = str_replace(['+', '-', '(', ')', ' '], null, $this->phone);
        $this->parent_phone = str_replace(['+', '-', '(', ')', ' '], null, $this->parent_phone);
        
        return parent::beforeValidate();
    }
    
    public function signup()
    {
        $user = new User();
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->role = 'student';
        $user->new_password = $this->password;
        
        $user->full_name = $this->full_name;
        $user->gender = $this->gender;
        $user->birth_date = $this->birth_date;
        $user->phone = $this->phone;
        $user->parent_phone = $this->parent_phone;
        $user->address = $this->address;
        
        if ($user->save()) {
            Yii::$app->services->notification->create(
                [$user->id],
                'auth_signup', $user->id,
                ['full_name' => $user->full_name]
            );
            
            Yii::$app->services->mail->send($user->username, Yii::t('app_mail', 'Добро пожаловать'), 'signup');
            
            Yii::$app->user->login($user);
            return $user;
        } else {
            return false;
        }
    }
}
