<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;

use frontend\forms\LoginForm;
use frontend\forms\ResetPasswordRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;

use backend\modules\User\models\User;


class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout-as'],
                'rules' => [
                    [
                        'actions' => ['logout-as'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout-as' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        $page = Yii::$app->services->staticpage->auth;
        $blocks = ['blocks' => $page['blocks']];
        
        return [
            'signup' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => SignupForm::className(),
                'render_view' => 'signup',
                'render_params' => fn() => $blocks,
                'run_method' => 'signup',
                'redirect_route' => ['user/profile/view'],
            ],
            'reset-password-request' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => ResetPasswordRequestForm::className(),
                'render_view' => 'reset_password_request',
                'render_params' => fn() => $blocks,
                'run_method' => 'sendEmail',
                'success_message' => 'reset_password_request_success_alert',
                'redirect_route' => ['login'],
            ],
            'reset-password' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => ResetPasswordForm::className(),
                'model_params' => ['token' => Yii::$app->request->get('token')],
                'render_view' => 'reset_password',
                'render_params' => fn() => $blocks,
                'run_method' => 'resetPassword',
                'redirect_route' => ['user/course/index'],
            ],
        ];
    }
    
    public function actionLogin()
    {
        $page = Yii::$app->services->staticpage->auth;
        
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->login();
            return $this->redirect(['user/course/index']);
        } else {
            $model->password = null;
        }
        
        return $this->render('login', [
            'page' => $page['page'],
            'blocks' => $page['blocks'],
            'model' => $model,
        ]);
    }
    
    public function actionSocialLogin()
    {
        $provider = Yii::$app->request->post('provider');
        $token = Yii::$app->request->post('token');
        
        $url = 'https://identitytoolkit.googleapis.com/v1/accounts:signInWithIdp?key=AIzaSyCFkjM4QgYaEnSGzA-H7OwsYnbOLrZDnFM';
        $ch = curl_init($url);
        $data = json_encode([
            'postBody' => "access_token=$token&providerId=$provider",
            'requestUri' => Yii::$app->request->hostInfo,
            'returnSecureToken' => true,
            'returnIdpCredential' => true,
        ]);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);
        
        if (isset($result->error)) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        if ($user = User::find()->andWhere(['username' => $result->email])->one()) {
            Yii::$app->user->login($user);
            return $this->redirect(['user/course/index']);
        }
        
        Yii::$app->session->addFlash('success', Yii::t('app', 'Необходимо заполнить дополнительные данные для продолжения'));
        
        return $this->redirect([
            'signup',
            'username' => $result->email,
            'password' => md5(uniqid()),
            'nickname' => preg_replace('/[^A-Za-z0-9]/', null, $result->email),
            'full_name' => $result->displayName,
        ]);
    }
    
    public function actionLoginAs($id)
    {
        $user = User::findOne($id);
        $user_login_profiles = Yii::$app->request->cookies->getValue('user_login_profiles', []);
        
        if ($user && array_key_exists($id, $user_login_profiles)) {
            if ($id != Yii::$app->user->id) {
                Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Вы сменили профиль'));
                Yii::$app->user->login($user);
            }
        } else {
            if (array_key_exists($id, $user_login_profiles)) {
                unset($user_login_profiles[$id]);
                
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'user_login_profiles',
                    'value' => $user_login_profiles,
                    'expire' => time() + 86400 * 365,
                ]));
            }
            
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Пользователь не найден или заблокирован'));
        }
        
        return $this->redirect(['user/profile/view']);
    }
    
    public function actionLogoutAs($id)
    {
        $user_login_profiles = Yii::$app->request->cookies->getValue('user_login_profiles', []);
        
        if (array_key_exists($id, $user_login_profiles)) {
            unset($user_login_profiles[$id]);
            
            if ($id == Yii::$app->user->id) {
                Yii::$app->user->logout(false);
                
                if ($another_user = User::findOne(array_key_first($user_login_profiles))) {
                    Yii::$app->user->login($another_user);
                }
            }
            
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'user_login_profiles',
                'value' => $user_login_profiles,
                'expire' => time() + 86400 * 365,
            ]));
        }
        
        return $this->redirect(['user/profile/view']);
    }
}
