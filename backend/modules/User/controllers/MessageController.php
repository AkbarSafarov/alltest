<?php

namespace backend\modules\User\controllers;

use Yii;
use speedrunner\controllers\CrudController;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\User\models\UserMessage;


class MessageController extends CrudController
{
    public function init()
    {
        $this->model = new UserMessage();
        return parent::init();
    }
    
    public function actions()
    {
        return [
            'create' => [
                'class' => Actions\crud\CreateAction::className(),
                'render_view' => 'create',
                'render_params' => fn() => [
                    'user_search_params' => json_encode(Yii::$app->request->get('user_search_params', []), JSON_UNESCAPED_UNICODE),
                ],
                'success_message' => 'Сообщение было отправлено',
                'redirect_route' => Yii::$app->request->referrer,
            ],
        ];
    }
}
