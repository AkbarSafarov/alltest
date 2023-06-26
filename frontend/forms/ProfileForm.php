<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use backend\modules\User\models\User;


class ProfileForm extends Model
{
    const PROFILE_ATTRIBUTES = ['nickname', 'full_name', 'gender', 'birth_date', 'phone', 'parent_phone', 'address', 'image'];
    
    public $user;
    
    public $nickname;
    public $full_name;
    public $gender;
    public $birth_date;
    public $phone;
    public $parent_phone;
    public $address;
    public $image;
    
    public $old_password;
    public $new_password;
    public $confirm_password;
    
    public function init()
    {
        if (!($this->user = Yii::$app->user->identity)) {
            throw new InvalidParamException(Yii::t('app', 'Not authorized'));
        }
        
        foreach (self::PROFILE_ATTRIBUTES as $a) {
            $this->{$a} = $this->user->{$a};
        }
        
        return parent::init();
    }
    
    public function formName()
    {
        return 'User';
    }
    
    public function rules()
    {
        return [
            [['nickname', 'full_name', 'gender', 'birth_date', 'phone', 'parent_phone'], 'required'],
            [['old_password'], 'required', 'enableClientValidation' => false, 'when' => fn ($model) => $model->new_password],
            [['confirm_password'], 'required', 'enableClientValidation' => false, 'when' => fn ($model) => $model->new_password],
            
            [['nickname', 'full_name', 'address'], 'string', 'max' => 100],
            [['nickname'], 'unique', 'targetClass' => User::className(), 'filter' => function($query) {
                $query->andWhere(['!=', 'id', $this->user->id]);
            }, 'message' => Yii::t('app', 'Данное имя уже занято')],
            [['nickname'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => Yii::t('app', 'Поле должно содержать только латинские буквы и цифры')],
            [['gender'], 'in', 'range' => array_keys($this->user->enums->genders())],
            [['birth_date'], 'date', 'format' => 'php: d.m.Y'],
            [['image'], 'file', 'extensions' => Yii::$app->params['extensions']['image'], 'maxSize' => 1024 * 1024],
            
            [['phone', 'parent_phone'], 'match', 'pattern' => '/998[0-9]{9}/', 'enableClientValidation' => false],
            [['parent_phone'], 'compare', 'compareAttribute' => 'phone', 'operator' => '!=', 'message' => Yii::t('app', 'Телефон родителя должен отличаться от Вашего телефона')],
            
            [['old_password'], 'oldPasswordValidation'],
            [['new_password'], 'string', 'min' => 8, 'max' => 50],
            [['confirm_password'], 'compare', 'compareAttribute' => 'new_password'],
        ];
    }
    
    public function oldPasswordValidation($attribute, $params)
    {
        if (!$this->user->validatePassword($this->{$attribute})) {
            $this->addError($attribute, Yii::t('app', 'Неверный {attribute}', ['attribute' => $this->getAttributeLabel('old_password')]));
        }
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge($this->user->attributeLabels(), [
            'old_password' => Yii::t('app', 'Старый пароль'),
            'new_password' => Yii::t('app', 'Новый пароль'),
            'confirm_password' => Yii::t('app', 'Подтверждение пароля'),
        ]);
    }
    
    public function beforeValidate()
    {
        $this->phone = str_replace(['+', '-', '(', ')', ' '], null, $this->phone);
        $this->parent_phone = str_replace(['+', '-', '(', ')', ' '], null, $this->parent_phone);
        
        if ($image = UploadedFile::getInstance($this, 'image')) {
            $this->image = $image;
        }
        
        return parent::beforeValidate();
    }
    
    public function update()
    {
        $user = $this->user;
        
        foreach (self::PROFILE_ATTRIBUTES as $a) {
            $user->{$a} = $this->{$a};
        }
        
        $user->new_password = $this->new_password;
        return $user->save() ? $user : false;
    }
}
