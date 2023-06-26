<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use frontend\forms\FeedbackForm;

use backend\modules\Page\models\Page;
use backend\modules\Blog\models\Blog;


class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'contact' => [
                'class' => Actions\web\FormAction::className(),
                'model_class' => FeedbackForm::className(),
                'render_view' => 'contact',
                'render_params' => fn() => [
                    'page' => Yii::$app->services->staticpage->contact['page'],
                    'blocks' => Yii::$app->services->staticpage->contact['blocks'],
                ],
                'run_method' => 'sendEmail',
                'success_message' => 'contact_success_alert',
                'redirect_route' => ['site/index'],
            ],
        ];
    }
    
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['user/course/index']);
        }
        
        $page = Yii::$app->services->staticpage->home;
        
        return $this->render('index', [
            'page' => $page['page'],
            'blocks' => $page['blocks'],
            'news' => Blog::find()->published()->orderBy('published_at_from DESC')->limit(3)->all(),
        ]);
    }
    
    public function actionHome()
    {
        $page = Yii::$app->services->staticpage->home;
        
        return $this->render('index', [
            'page' => $page['page'],
            'blocks' => $page['blocks'],
            'news' => Blog::find()->published()->orderBy('published_at_from DESC')->limit(3)->all(),
        ]);
    }
    
    public function actionFaq()
    {
        $page = Yii::$app->services->staticpage->faq;
        
        return $this->render('faq', [
            'page' => $page['page'],
            'blocks' => $page['blocks'],
        ]);
    }
    
    public function actionPage($slug)
    {
        if (!($model = Page::find()->bySlug($slug)->one())) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('page', [
            'model' => $model,
        ]);
    }
}
