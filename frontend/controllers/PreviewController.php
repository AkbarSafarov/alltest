<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\User;
use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;


class PreviewController extends Controller
{
    private $key;
    
    public function beforeAction($action)
    {
        $this->key = explode('___', base64_decode(Yii::$app->request->post('key')));
        
        if (count($this->key) != 2) {
            return false;
        }
        
        $user = User::find()
            ->andWhere([
                'and',
                ['auth_key' => $this->key[0]],
                ['!=', 'role', 'student'],
            ])
            ->one();
        
        if (!$user) {
            return false;
        }
        
        return true;
    }
    
    public function actionCourse()
    {
        if (!($model = Course::findOne($this->key[1]))) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        if (!($root_unit = CourseUnit::find()->andWhere(['tree' => $model->id, 'depth' => 0])->one())) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('course', [
            'model' => $model,
            'sections' => Yii::$app->services->array->toObjects($root_unit->tree([], ['type'])),
        ]);
    }
    
    public function actionUnit()
    {
        if (!($model = CourseUnit::find()->andWhere(['id' => $this->key[1], 'depth' => 3])->one())) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('unit', [
            'model' => $model,
            'templates' => $model->attachmentTemplates,
            'tests' => $model->attachmentTests,
            'test_packs' => $model->attachmentTestPacks,
        ]);
    }
}
