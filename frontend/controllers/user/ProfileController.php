<?php

namespace frontend\controllers\user;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\Cookie;

use frontend\forms\ProfileForm;

use backend\modules\User\models\User;
use backend\modules\User\models\UserNotification;
use backend\modules\Order\models\Order;


class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['view'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'update' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => ProfileForm::className(),
                'render_view' => 'update',
                'run_method' => 'update',
                'success_message' => 'profile_update_success_alert',
                'redirect_route' => ['view', 'nickname' => ArrayHelper::getValue(Yii::$app->user, 'identity.nickname')],
            ],
        ];
    }
    
    public function actionView($nickname = null)
    {
        $nickname = $nickname ?? ArrayHelper::getValue(Yii::$app->user->identity, 'nickname');
        
        if (!($model = User::find()->andWhere(['nickname' => $nickname])->one())) {
            return $this->redirect(['auth/login']);
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    
    public function actionOrders($offset = 0, $sort = null)
    {
        $query = Order::find()
            ->with(['products'])
            ->andWhere(['student_id' => Yii::$app->user->id])
            ->select([
                '*',
                "(total_price) - (discount_price) - (promocode_price) as checkout_price",
            ])
            ->offset($offset);
        
        switch ($sort) {
            case 'date':
                $query->orderBy('created_at');
                break;
            case 'price':
                $query->orderBy('checkout_price');
                break;
            case '-price':
                $query->orderBy('checkout_price DESC');
                break;
            default:
                $query->orderBy('created_at DESC');
        }
        
        $orders = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $offset,
            ],
        ]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/particles/orders', [
                'models' => $orders->getModels(),
            ]);
        } else {
            return $this->render('orders', [
                'orders' => $orders,
            ]);
        }
    }
    
    public function actionDelete()
    {
        $user = Yii::$app->user->identity;
        $user_login_profiles = Yii::$app->request->cookies->getValue('user_login_profiles', []);
        
        if (!($password = Yii::$app->request->post('password'))) {
            return $this->renderAjax('delete');
        }
        
        if (!$user->validatePassword($password)) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Вы ввели неверный пароль'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        Yii::$app->user->logout(false);
        unset($user_login_profiles[$user->id]);
        
        $user->delete();
        Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Ваш аккаунт удалён'));
        
        if ($another_user = User::findOne(array_key_first($user_login_profiles))) {
            Yii::$app->user->login($another_user);
        }
        
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'user_login_profiles',
            'value' => $user_login_profiles,
            'expire' => time() + 86400 * 365,
        ]));
        
        return $this->redirect(['view']);
    }
    
    public function actionClearNotifications()
    {
        return UserNotification::updateAll(['is_not_seen' => 0], ['user_id' => Yii::$app->user->id]);
    }
}
