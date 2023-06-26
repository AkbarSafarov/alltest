<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

use backend\modules\Blog\models\Blog;


class BlogController extends Controller
{
    public function actionIndex($offset = 0)
    {
        $page = Yii::$app->services->staticpage->blog;
        
        $query = Blog::find()->published()->orderBy('published_at_from DESC')->offset($offset);
        
        $blogs = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $offset,
            ],
        ]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/particles/blogs', [
                'models' => $blogs->getModels(),
            ]);
        } else {
            return $this->render('index', [
                'page' => $page['page'],
                'blogs' => $blogs,
            ]);
        }
    }
    
    public function actionView($slug)
    {
        if (!($model = Blog::find()->bySlug($slug)->published()->one())) {
            return $this->redirect(['index']);
        }
        
        return $this->render('view', [
            'model' => $model,
            'related' => Blog::find()->published()->orderBy('published_at_from DESC')->andWhere(['!=', 'id', $model->id])->limit(3)->all(),
        ]);
    }
}
