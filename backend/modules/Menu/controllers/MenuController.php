<?php

namespace backend\modules\Menu\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use backend\modules\Menu\models\Menu;


class MenuController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-with-children' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionTree()
    {
        $root_unit = Menu::find()->andWhere(['depth' => 0])->one();
        $root_unit->beforeSearch();
        
        return $this->render('tree', [
            'root_unit' => $root_unit,
            'data' => $root_unit->tree(),
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Menu();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($parent = Menu::findOne($model->parent_id)) {
                $model->appendTo($parent);
            }
            
            return $this->redirect(['tree']);
        }
        
        return $this->renderAjax('update', [
            'model' => $model,
            'menu_list' => Menu::find()->itemsTree('name', 'translation')->asArray()->all(),
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = Menu::findOne($id);
        
        if (!$model || $model->id <= 27) {
            return $this->redirect(['tree']);
        }
        
        $model->beforeSearch();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tree']);
        }
        
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = Menu::find()->where(['id' => $id])->one();
        
        if (!$model || $model->id <= 27) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $model->state = Yii::$app->services->trash->isDeleted($model) ? 'archive' : 'active';
        
        if ($model->state == 'archive' && Menu::find()->checkParentIsDeleted($model)->exists()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        if ($model->deleteWithChildren()) {
            Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Состояние записи было изменено'));
        } else {
            Yii::$app->session->addFlash('danger', Yii::t('app_notification', 'Произошла ошибка'));
        }
        
        return $this->redirect(['tree']);
    }
    
    public function actionMove($item, $action, $second)
    {
        $item_model = Menu::findOne($item);
        $second_model = Menu::findOne($second);
        
        if ($item_model && $item_model->id > 27 && $second_model && $second_model->depth > 0) {
            switch ($action) {
                case 'after':
                    $item_model->insertAfter($second_model);
                    break;
                case 'before':
                    $item_model->insertBefore($second_model);
                    break;
                case 'over':
                    $item_model->appendTo($second_model);
                    break;
            }
            
            return true;
        }
        
        return false;
    }
    
    public function actionExpand($id)
    {
        $model = Menu::findOne($id);
        return $model ? $model->updateAttributes(['expanded' => intval(!$model->expanded)]) : false;
    }
}
