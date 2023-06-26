<?php

namespace speedrunner\actions\crud;

use Yii;
use yii\base\Action;
use yii\base\Model;
use yii\helpers\ArrayHelper;

use backend\modules\Trash\widgets\TrashStateTabsWidget;


class DataProviderAction extends Action
{
    public $trash_state_tabs = ['active', 'archive'];
    
    public ?Model $model;
    public $render_view = 'index';
    public ?\Closure $render_params;
    
    public function run()
    {
        $params = Yii::$app->request->queryParams;
        array_walk_recursive($params, fn (&$v) => $v = trim($v));
        
        $model = $this->model ?? $this->controller->model->searchModel;
        $model->enums = $this->controller->model->enums;
        $model->load($params);
        $model->beforeSearch();
        
        $dataProvider = $model->search();
        
        $render_type = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        $render_params = $this->render_params ?? fn () => [];
        
        $model->afterSearch();
        
        $trash_state_tabs_widget = isset($model->state) ? TrashStateTabsWidget::widget([
            'model' => $model,
            'states' => $this->trash_state_tabs,
        ]) : null;
        
        return call_user_func(
            [$this->controller, $render_type],
            $this->render_view,
            ArrayHelper::merge([
                'searchModel' => $model,
                'dataProvider' => $dataProvider,
                'trash_state_tabs_widget' => $trash_state_tabs_widget,
            ], $render_params())
        );
    }
}
