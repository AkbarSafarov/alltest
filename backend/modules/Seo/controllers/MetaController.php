<?php

namespace backend\modules\Seo\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use backend\modules\Seo\models\SeoMeta;


class MetaController extends Controller
{
    public function actionUpdate()
    {
        $filter = ['model_class' => 'SeoMeta', 'lang' => Yii::$app->language];
        $model = SeoMeta::find()->andWhere($filter)->one() ?: new SeoMeta($filter);
        
        if (($model->value = Yii::$app->request->post('SeoMeta')) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Процесс выполнен'));
            return $this->refresh();
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
