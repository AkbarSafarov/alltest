<?php

namespace backend\modules\User\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\web\Cookie;

use speedrunner\validators\UnchangeableValidator;


class User extends ActiveRecord implements IdentityInterface
{
    const PASSWORD_RESET_TOKEN_EXPIRE = 3600;
    
    public $new_password;
    public $owned_courses;
    
    public $full_name;
    public $gender;
    public $birth_date;
    public $phone;
    public $parent_phone;
    public $address;
    
    public $profile_attributes = [
        'full_name',
        'gender',
        'birth_date',
        'phone',
        'parent_phone',
        'address',
    ];
    
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'update_profile' => [
                'nickname', 'full_name', 'gender', 'birth_date', 'phone', 'address', 'new_password',
            ],
        ]);
    }
    
    public function behaviors()
    {
        return [
            'file' => [
                'class' => \speedrunner\behaviors\FileBehavior::className(),
                'attributes' => ['image'],
                'multiple' => false,
                'save_dir' => 'uploaded/profile',
            ],
            'relations_one_one' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'oneOne',
                'attributes' => [
                    'profile' => [
                        'model' => new UserProfile(),
                        'relation' => 'profile',
                        'attributes' => [
                            'main' => 'user_id',
                            'relational' => $this->profile_attributes,
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['username', 'nickname', 'role', 'full_name', 'gender', 'birth_date', 'phone'], 'required'],
            [['new_password'], 'required', 'enableClientValidation' => false, 'when' => fn($model) => $model->isNewRecord],
            
            [['username', 'nickname'], 'unique'],
            [['username'], 'email'],
            [['username', 'nickname', 'full_name', 'address'], 'string', 'max' => 100],
            [['nickname'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => Yii::t('app', 'Поле должно содержать только латинские буквы и цифры')],
            [['role'], 'in', 'range' => array_keys($this->enums->roles())],
            [['gender'], 'in', 'range' => array_keys($this->enums->genders())],
            [['birth_date'], 'date', 'format' => 'php: d.m.Y'],
            [['image'], 'file', 'extensions' => Yii::$app->params['extensions']['image'], 'maxSize' => 1024 * 1024],
            [['new_password'], 'string', 'min' => 8, 'max' => 50],
            [['phone', 'parent_phone'], 'match', 'pattern' => '/998[0-9]{9}/', 'enableClientValidation' => false],
            [['parent_phone'], 'compare', 'compareAttribute' => 'phone', 'operator' => '!=', 'message' => Yii::t('app', 'Телефон родителя должен отличаться от Вашего телефона')],
            
            [['role'], UnchangeableValidator::className(), 'when' => fn ($model) => $model->id == 1],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'username' => Yii::t('app', 'Адрес электронной почты'),
            'nickname' => Yii::t('app', 'Публичное имя'),
            'role' => Yii::t('app', 'Роль'),
            'image' => Yii::t('app', 'Изображение'),
            'last_activity' => Yii::t('app', 'Последняя активность'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'new_password' => Yii::t('app', 'Новый пароль'),
            
            'full_name' => Yii::t('app', 'ФИО'),
            'gender' => Yii::t('app', 'Пол'),
            'birth_date' => Yii::t('app', 'Дата рождения'),
            'phone' => Yii::t('app', 'Телефон'),
            'parent_phone' => Yii::t('app', 'Телефон родителя'),
            'address' => Yii::t('app', 'Адрес'),
        ];
    }
    
    public function updateProfile()
    {
        $this->scenario = 'update_profile';
        return $this->save();
    }
    
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }
    
    public function getCart()
    {
        return $this->hasOne(UserCart::className(), ['user_id' => 'id']);
    }
    
    public function getCourses()
    {
        return $this->hasMany(UserCourse::className(), ['user_id' => 'id']);
    }
    
    public static function afterLogin($event)
    {
        //        Checking user limit
        
        $identity = $event->identity;
        $session_ids = $identity->session_ids;
        $session_ids[] = Yii::$app->session->id;
        
        if (count($session_ids) > 3) {
            unset($session_ids[0]);
        }
        
        $identity->updateAttributes(['session_ids' => array_values($session_ids)]);
        $identity->refresh();
        
        //        Setting cookie for multiple auth
        
        $user_login_profiles = Yii::$app->request->cookies->getValue('user_login_profiles', []);
        $user_login_profiles[$identity->id] = ArrayHelper::merge($identity->attributes, $identity->profile->attributes);
        
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'user_login_profiles',
            'value' => $user_login_profiles,
            'expire' => time() + 86400 * 365,
        ]));
    }
    
    public static function beforeLogout($event)
    {
        //        Removing user session from session_ids
        
        $identity = $event->identity;
        $session_ids = $identity->session_ids;
        $session_index = array_search(Yii::$app->session->id, $session_ids);
        
        if ($session_index !== null) {
            unset($session_ids[$session_index]);
            
            $identity->session_ids = array_values($session_ids);
            $identity->save();
        }
        
        //        Removing cookie from multiple auth
        
        $user_login_profiles = Yii::$app->request->cookies->getValue('user_login_profiles', []);
        ArrayHelper::remove($user_login_profiles, $identity->id);
        
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'user_login_profiles',
            'value' => $user_login_profiles,
            'expire' => time() + 86400 * 365,
        ]));
    }
    
    public static function find()
    {
        return parent::find()->with(['profile']);
    }
    
    public function afterFind()
    {
        foreach ($this->profile_attributes as $p_a) {
            $this->{$p_a} = $this->profile->{$p_a};
        }
        
        return parent::afterFind();
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->session_ids = [];
            $this->last_activity = date('Y-m-d H:i:s');
        }
        
        //        Setting new password
        
        if ($this->new_password) {
            $this->auth_key = Yii::$app->services->string->randomize();
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->new_password);
        }
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        //        Assigning role
        
        if (array_key_exists('role', $changedAttributes)) {
            $roles = Yii::$app->authManager->getRoles();
            
            if (!$insert) {
                Yii::$app->authManager->revoke($roles[$changedAttributes['role']], $this->id);
            }
            
            Yii::$app->authManager->assign($roles[$this->role], $this->id);
            Yii::$app->authManager->invalidateCache();
        }
        
        //        Creating cart
        
        if (!$this->cart) {
            (new UserCart())->save();
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete()
    {
        if ($this->id == 1) {
            Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Вы не можете удалить эту запись'));
            return false;
        }
        
        return parent::beforeDelete();
    }
    
    public function afterDelete()
    {
        //        Detaching role
        
        $roles = Yii::$app->authManager->getRoles();
        Yii::$app->authManager->revoke($roles[$this->role], $this->id);
        
        return parent::afterDelete();
    }
    
    //        YII2 default methods
    
    public static function findIdentity($id)
    {
        $query = self::find()->andWhere(['id' => $id]);
        
        if (Yii::$app->id == 'app-frontend') {
            $session_id = json_encode(Yii::$app->session->id, JSON_UNESCAPED_UNICODE);
            $query->andWhere("JSON_CONTAINS(session_ids, '[$session_id]')");
        }
        
        if (!($user = $query->one())) {
            return null;
        }
        
        if (Yii::$app->id == 'app-frontend') {
            $user->owned_courses = UserCourse::find()
                ->andWhere([
                    'and',
                    ['user_id' => $user->id],
                    ['IS', 'demo_datetime_to', null],
                ])
                ->select('course_id')
                ->column();
        }
        
        $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
        
        return $user;
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->andWhere(['auth_key' => $token])->one();
    }
    
    public static function findByUsername($username)
    {
        return self::find()->andWhere(['username' => $username])->one();
    }
    
    public static function findByPasswordResetToken($token)
    {
        return self::isPasswordResetTokenValid($token) ? self::find()->where(['password_reset_token' => $token])->one() : false;
    }
    
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = self::PASSWORD_RESET_TOKEN_EXPIRE;
        return $timestamp + $expire >= time();
    }
    
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
