<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\ArrayHelper;

use backend\modules\Library\models\LibraryTest;


class LibraryAttachmentTestResultWidget extends Widget
{
    public Model $model;
    
    public function run()
    {
        $test_groups = [
            'test' => Yii::$app->services->array->toObjects($this->model->library_attachment_json['tests']),
            'test_pack' => Yii::$app->session->get("unit_{$this->model->id}_test_pack", []),
        ];
        
        return $this->render('library_attachment_test_result', [
            'model' => $this->model,
            'test_groups' => $test_groups,
        ]);
    }
}
