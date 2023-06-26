<?php

namespace backend\modules\Order\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;

use backend\modules\User\models\UserCourse;


class OrderService extends ActiveService
{
    public function createUserCourses()
    {
        $user_id = $this->model->student_id;
        $courses = [];
        
        foreach ($this->model->products as $product) {
            $product->product->updateCounters(['students_now_quantity' => 1]);
            
            switch ($product->model_class) {
                case 'Course':
                    $courses[] = $product->product;
                    break;
                case 'CoursePackage':
                    $courses = ArrayHelper::merge($courses, $product->product->activeCourses);
                    break;
            }
        }
        
        foreach ($courses as $course) {
            if (!$course->service->isOwned()) {
                $user_course = UserCourse::find()->andWhere(['user_id' => $user_id, 'course_id' => $course->id])->one();
                
                if (!$user_course) {
                    (new UserCourse(['course_id' => $course->id, 'user_id' => $user_id]))->save();
                } else {
                    $user_course->updateAttributes(['demo_datetime_to' => null]);
                }
            }
        }
    }
}
