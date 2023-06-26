<?php

namespace backend\modules\Course\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;
use backend\modules\System\models\SystemLanguage;


class CourseController extends CrudController
{
    public function init()
    {
        $this->model = new Course();
        return parent::init();
    }
    
    public function actions()
    {
        $actions = ArrayHelper::filter(parent::actions(), ['view', 'delete']);
        
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => Actions\crud\DataProviderAction::className(),
                'trash_state_tabs' => ['development', 'active', 'outdated', 'archive'],
                'render_params' => fn() => [
                    'languages' => SystemLanguage::find()->asArray()->all(),
                ],
            ],
            'create' => [
                'class' => Actions\crud\CreateAction::className(),
                'render_params' => fn() => [
                    'languages' => SystemLanguage::find()->asArray()->all(),
                ],
            ],
            'update' => [
                'class' => Actions\crud\UpdateAction::className(),
                'render_params' => fn() => [
                    'languages' => SystemLanguage::find()->asArray()->all(),
                ],
            ],
            'file-delete' => [
                'class' => Actions\crud\FileDeleteAction::className(),
                'allowed_attributes' => ['certificate_file'],
            ],
        ]);
    }
    
    public function findModel($id)
    {
        $query = $this->model->find()->with(['attachmentTemplates'])->where(['course.id' => $id]);
        
        if (Yii::$app->user->identity->role == 'teacher') {
            $query->byTeacher();
        }
        
        return $query->one();
    }
    
    public function actionPublish($id)
    {
        if (!($model = $this->findModel($id))) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        if (!($root_unit = CourseUnit::find()->andWhere(['tree' => $model->id, 'depth' => 0])->one())) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $active_structure = $root_unit->tree(['status' => 'active'], ['type', 'attachmentTemplates', 'attachmentTests', 'attachmentTestPacks']);
        $current_structure = $root_unit->tree([], ['type', 'attachmentTemplates', 'attachmentTests', 'attachmentTestPacks']);
        
        if ($active_structure != $current_structure) {
            Yii::$app->session->addFlash('warning', Yii::t('app_notification', 'Вся структура должна быть активирована'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $model->service->updateUserCourseUnits($active_structure);
        Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Курс опубликован'));
        
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionClone($id)
    {
        if (!($model = $this->findModel($id))) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        if ($model->service->clone()) {
            Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Запись была клонирована'));
        } else {
            Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Произошла ошибка'));
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}
