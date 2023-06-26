<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

use backend\modules\Course\models\Course;
use backend\modules\User\models\UserCourse;


class OrderController extends Controller
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
    
    public function actionPay($type)
    {
        if (!in_array($type, ['paycom', 'click'])) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        if ($order = Yii::$app->services->cart->createOrder()) {
            if ($order->checkout_price > 0) {
                return $this->renderPartial("pay/$type", [
                    'order' => $order,
                ]);
            } else {
                $order->updateAttributes(['status' => 'paid']);
                $order->service->createUserCourses();
                return $this->redirect(['user/course/index']);
            }
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
    public function actionGetDemo($id)
    {
        $course = Course::find()
            ->active()
            ->andWhere([
                'and',
                ['id' => $id],
                ['>', 'demo_time', 0],
            ])
            ->one();
        
        if (!$course) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $user_course = UserCourse::find()->andWhere(['user_id' => Yii::$app->user->id, 'course_id' => $course->id])->one();
        
        if (!$user_course) {
            $user_course = new UserCourse();
            $user_course->user_id = Yii::$app->user->id;
            $user_course->course_id = $course->id;
            $user_course->demo_datetime_to = date('Y-m-d H:i:s', strtotime("+$course->demo_time hours"));
            $user_course->save();
            
            Yii::$app->services->notification->create(
                [Yii::$app->user->id],
                'course_demo', $this->id,
                [
                    'course' => $course->name,
                    'datetime' => $user_course->demo_datetime_to,
                ]
            );
        }
        
        return $this->redirect(['user/unit/view', 'course_id' => $user_course->id]);
    }
}
