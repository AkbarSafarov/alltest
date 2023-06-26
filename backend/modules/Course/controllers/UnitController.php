<?php

namespace backend\modules\Course\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;
use backend\modules\Course\models\CourseUnitType;
use backend\modules\Moderation\enums\ModerationEnums;


class UnitController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex($course_id)
    {
        $root_unit = CourseUnit::find()->andWhere(['tree' => $course_id, 'depth' => 0])->one();
        
        if (!$root_unit || !$root_unit->service->isAllowed()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $root_unit->beforeSearch();
        
        $render_type = Yii::$app->request->isAjax ? 'renderPartial' : 'render';
        
        return $this->{$render_type}('index', [
            'root_unit' => $root_unit,
            'sections' => Yii::$app->services->array->toObjects($root_unit->tree()),
            'types' => CourseUnitType::find()->indexBy('id')->all(),
            'statuses' => ModerationEnums::statuses(),
            'course_structure' => Yii::$app->session->get("course_structure_$course_id", []),
        ]);
    }
    
    public function actionIndexUnit($id)
    {
        if (!($model = CourseUnit::findOne($id))) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->renderPartial('index/unit', [
            'model' => $model,
            'is_active' => true,
            'types' => CourseUnitType::find()->indexBy('id')->all(),
            'statuses' => ModerationEnums::statuses(),
            'collapse_target' =>  $model->depth < 3 ? "course-section-$model->id" : null,
            'has_parent' => false,
        ]);
    }
    
    public function actionCreate($parent_id)
    {
        $parent = CourseUnit::find()
            ->andWhere([
                'and',
                ['id' => $parent_id],
                ['<=', 'depth', 2],
            ])
            ->one();
        
        if (!$parent || !$parent->service->isAllowed()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $update_type = $parent->depth < 2 ? 'section' : 'unit';
        
        $model = new CourseUnit(['tree' => $parent->tree]);
        $model->scenario = "update_$update_type";
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->appendTo($parent)) {
                Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Запись была сохранена'));
            } else {
                Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Произошла ошибка'));
            }
            
            if (Yii::$app->request->get('save_and_update')) {
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                return $this->redirect(['index', 'course_id' => $model->tree]);
            }
        }
        
        return $this->render("update/$update_type", [
            'model' => $model,
            'types' => CourseUnitType::find()->all(),
        ]);
    }
    
    public function actionUpdate($id, $is_ajax = false)
    {
        $model = CourseUnit::find()->with(['attachmentTemplates', 'attachmentTests', 'attachmentTestPacks'])->andWhere(['id' => $id])->one();
        
        if (!$model || $model->depth == 0 || !$model->service->isAllowed()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $update_type = $model->type_id ? 'unit' : 'section';
        $model->scenario = "update_$update_type";
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($is_ajax) {
                return $this->runAction('index-unit', ['id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Запись была сохранена'));
                
                if (Yii::$app->request->get('save_and_update')) {
                    return $this->redirect(['update', 'id' => $model->id]);
                } else {
                    return $this->redirect(['index', 'course_id' => $model->tree]);
                }
            }
        }
        
        if ($is_ajax) {
            return $this->runAction('index-unit', ['id' => $model->id]);
        } else {
            return $this->render("update/$update_type", [
                'model' => $model,
                'types' => CourseUnitType::find()->all(),
            ]);
        }
    }
    
    public function actionDelete()
    {   
        if (Yii::$app->request->get('password') != Yii::$app->user->identity->password_hash) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $id = Yii::$app->request->post('selection');
        $models = CourseUnit::find()->where(['id' => $id])->all();
        
        if (!$models || count(ArrayHelper::map($models, 'depth', 'depth')) != 1 || $models[0]->depth == 0) {
            Yii::$app->session->addFlash('warning', Yii::t('app', 'Все элементы структуры должны быть на едином уровне'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        foreach ($models as $model) {
            if (!Yii::$app->services->permissions->isDeletable($model)) {
                continue;
            }
            
            $model->state = Yii::$app->services->trash->isDeleted($model) ? 'archive' : 'active';
            
            $condition_1 = !$model->service->isAllowed();
            $condition_2 = $model->state == 'archive' && CourseUnit::find()->checkParentIsDeleted($model)->exists();
            $condition_3 = !$model->deleteWithChildren();
            
            if ($condition_1 || $condition_2 || $condition_3) {
                Yii::$app->session->setFlash('danger', [Yii::t('app_notification', 'Произошла ошибка')]);
                $transaction->rollBack();
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $transaction->commit();
        
        return true;
    }
    
    public function actionClone()
    {
        $id = Yii::$app->request->post('selection');
        $models = CourseUnit::find()->andWhere(['id' => $id])->all();
        
        if (!$models || count(ArrayHelper::map($models, 'depth', 'depth')) != 1 || $models[0]->depth == 0) {
            Yii::$app->session->addFlash('warning', Yii::t('app', 'Все элементы структуры должны быть на едином уровне'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        foreach ($models as $model) {
            if (!$model->service->isAllowed() || !$model->service->clone()) {
                Yii::$app->session->setFlash('danger', [Yii::t('app_notification', 'Произошла ошибка')]);
                $transaction->rollBack();
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $transaction->commit();
        
        return true;
    }
    
    public function actionMove($id, $rel_id, $insert_type)
    {
        $model = CourseUnit::findOne($id);
        $rel_model = CourseUnit::findOne($rel_id);
        
        if (!$model || $model->depth == 0 || !$model->service->isAllowed() || !$rel_model || $rel_model->depth == 0) {
            return false;
        }
        
        $model->scenario = 'move';
        
        switch ($insert_type) {
            case 'after':
                return $model->depth == $rel_model->depth ? $model->insertAfter($rel_model) : false;
            case 'before':
                return $model->depth == $rel_model->depth ? $model->insertBefore($rel_model) : false;
            case 'over':
                return $model->depth - 1 == $rel_model->depth ? $model->appendTo($rel_model) : false;
        }
    }
    
    public function actionStructureToggle($course_id, $id)
    {
        $course = Course::findOne($course_id);
        $model = CourseUnit::find()->where(['id' => $id])->one();
        
        if (!$course || !$model) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $course_structure = Yii::$app->session->get("course_structure_$course_id", []);
        
        if (isset($course_structure[$id])) {
            unset($course_structure[$id]);
        } else {
            $course_structure[$id] = $id;
        }
        
        return Yii::$app->session->set("course_structure_$course_id", $course_structure);
    }
}
