<?php

namespace frontend\controllers\user;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use backend\modules\User\models\UserCourse;
use backend\modules\User\models\UserCourseUnit;
use backend\modules\Reward\services\RewardCheckService;


class UnitController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionView($course_id, $id = null)
    {
        //        Searching unit
        
        $model = UserCourseUnit::find()
            ->andFilterWhere([
                'tree' => $course_id,
                'id' => $id,
                'depth' => 3,
                'is_unlocked' => 1,
            ])
            ->orderBy([
                'is_current' => SORT_DESC,
                'lft' => SORT_ASC,
            ])
            ->one();
        
        if (!$model) {
            return $this->redirect(['user/course/index']);
        }
        
        $model->service->setCurrent();
        
        //        Searching course
        
        $course = UserCourse::find()
            ->active()
            ->with([
                'units.type',
                'achievements.achievement',
                'leagues.league',
            ])
            ->andWhere([
                'id' => $course_id,
                'user_id' => Yii::$app->user->id,
            ])
            ->one();
        
        if (!$course) {
            return $this->redirect(['user/course/index']);
        }
        
        $course->updateAttributes(['last_visit' => date('Y-m-d H:i:s')]);
        
        //        Rendering
        
        $render_type = Yii::$app->request->isAjax ? 'renderPartial' : 'render';
        
        return $this->{$render_type}('view', [
            'course' => $course,
            'model' => $model,
        ]);
    }
    
    public function actionSubmit($id)
    {
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['user/course/index']);
        }
        
        $model = UserCourseUnit::find()
            ->joinWith(['course' => fn($query) => $query->active()])
            ->with(['course.units.type'])
            ->andWhere([
                'and',
                [
                    'user_course.user_id' => Yii::$app->user->id,
                    'user_course_unit.id' => $id,
                    'user_course_unit.depth' => 3,
                    'user_course_unit.is_unlocked' => 1,
                ],
                [
                    'or',
                    ['is', 'user_course_unit.available_from', new Expression('null')],
                    ['<=', 'user_course_unit.available_from', date('Y-m-d H:i:s')],
                ],
            ])
            ->one();
        
        if (!$model) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $course = $model->course;
        
        //        Checking tests existance
        
        $test_pack = Yii::$app->session->get("unit_{$model->id}_test_pack", []);
        $test_quantity = count($model->library_attachment_json['tests']) + count($test_pack);
        
        if ($test_quantity && !Yii::$app->session->has("unit_{$model->id}_is_submitted")) {
            $answers = [
                'test' => (array)ArrayHelper::getValue($_POST, 'answer.test'),
                'test_pack' => (array)ArrayHelper::getValue($_POST, 'answer.test_pack'),
            ];
            
            Yii::$app->session->set("unit_{$model->id}_test_post_data", $answers);
            Yii::$app->session->set("unit_{$model->id}_is_submitted", true);
            
            //        Validating tests
            
            $score = 0;
            
            $test_types = [
                'test' => $model->library_attachment_json['tests'],
                'test_pack' => $test_pack,
            ];
            
            foreach ($test_types as $test_type => $tests) {
                foreach ($tests as $test) {
                    $test = (object)$test;
                    $correct_answer = Yii::$app->session->get("unit_{$model->id}_{$test_type}_{$test->id}_answer");
                    
                    if ($test->input_type == 'text_area') {
                        $answer = trim(mb_strtolower((string)ArrayHelper::getValue($answers, "$test_type.$test->id")));
                        $correct_answer = array_map(fn($value) => trim(mb_strtolower($value)), $correct_answer);
                        
                        if (in_array($answer, $correct_answer)) {
                            $post_data_correct[$test_type][] = $test->id;
                            $score++;
                        }
                    } else {
                        if (ArrayHelper::getValue($answers, "$test_type.$test->id") == $correct_answer) {
                            $post_data_correct[$test_type][] = $test->id;
                            $score++;
                        }
                    }
                }
            }
            
            Yii::$app->session->set("unit_{$model->id}_test_post_data_correct", $post_data_correct ?? []);
            
            //        Updating performance
            
            $performance = round($score / $test_quantity * 100);
            
            $model->updateAttributes(['performance' => array_merge($model->performance, [$performance])]);
            $model->performance = $performance;
            
            $course->service->updatePerformance();
            
            //        Conditionally unlocking next unit
            
            if ($model->performance >= $course->course->passing_percent) {
                $model->service->unlockNextUnit();
                $course->service->updateProgress();
                (new RewardCheckService($course))->all();
            } else {
                if ($model->available_from < date('Y-m-d H:i:s')) {
                    $available_from_interval = abs((int)Yii::$app->services->settings->user_course_unit_available_from_interval);
                    $model->updateAttributes(['available_from' => date('Y-m-d H:i:s', strtotime("+$available_from_interval hours", time()))]);
                }
            }
            
            return $this->render('result', [
                'model' => $model,
                'course' => $course,
                'test_quantity' => [
                    'total' => $test_quantity,
                    'score' => $score,
                ],
            ]);
        } else {
            $route = $model->service->unlockNextUnit();
            $course->service->updateProgress();
            (new RewardCheckService($course))->all();
            
            return $this->redirect($route);
        }
    }
    
    public function actionStructureToggle($course_id, $id)
    {
        $course = UserCourse::findOne($course_id);
        $model = UserCourseUnit::findOne($id);
        
        if (!$course || !$model) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $user_course_structure = Yii::$app->session->get("user_course_structure_$course_id", []);
        
        if (isset($user_course_structure[$id])) {
            unset($user_course_structure[$id]);
        } else {
            $user_course_structure[$id] = $id;
        }
        
        return Yii::$app->session->set("user_course_structure_$course_id", $user_course_structure);
    }
}
