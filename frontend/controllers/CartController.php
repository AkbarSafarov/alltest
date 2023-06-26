<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;
use backend\modules\Order\models\OrderPromocode;


class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function($rule, $action) {
                    Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Вы должны авторизоваться'));
                    return $this->redirect(['auth/signup']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $render_type = Yii::$app->request->isAjax ? 'renderPartial' : 'render';
        
        return $this->{$render_type}('index', [
            'cart' => Yii::$app->services->cart,
        ]);
    }
    
    public function actionChange()
    {
        $id = Yii::$app->request->post('id');
        $type = Yii::$app->request->post('type');
        
        switch ($type) {
            case 'Course':
                $product = Course::find()
                    ->active()
                    ->andWhere([
                        'and',
                        ['id' => $id],
                        ['not in', 'id', Yii::$app->user->identity->owned_courses],
                    ])
                    ->one();
                break;
            case 'CoursePackage':
                $product = CoursePackage::find()->active()->andWhere(['id' => $id])->one();
                break;
            default:
                $product = null;
        }
        
        if (!$product) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Продукт не найден'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $cart = Yii::$app->services->cart;
        $cart->change($product, $type);
        $is_in_cart = isset($cart->products["$type-$product->id"]);
        
        return $this->asJson([
            'change_type' => $is_in_cart ? Yii::t('app', 'В корзине') : Yii::t('app', 'Купить'),
            'change_alert' => $is_in_cart ? Yii::t('app_notification', 'Продукт был добавлен в корзину') : Yii::t('app_notification', 'Продукт был удалён из корзины'),
            'quantity' => $cart->total_quantity,
            'page' => $this->runAction('index'),
        ]);
    }
    
    public function actionChangePromocode()
    {
        if ($promocode_key = Yii::$app->request->post('promocode')) {
            $model = OrderPromocode::find()->andWhere(['key' => $promocode_key])->one();
            
            if (!$model) {
                Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Промокод не найден'));
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            if (!$model->service->checkAvailability()) {
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            Yii::$app->services->cart->changePromocode($model->id);
        } else {
            Yii::$app->services->cart->changePromocode();
        }
        
        return $this->runAction('index');
    }
    
    public function actionClear()
    {
        Yii::$app->services->cart->changePromocode();
        foreach (Yii::$app->services->cart->products as $product) $product->delete();
        Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Корзина была очищена'));
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}
