<?php

namespace backend\modules\Library\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

use backend\modules\Library\enums\LibraryTestEnums;


class LibraryAttachmentTestPackWidget extends Widget
{
    public Model $model;
    
    public function run()
    {
        return $this->render('library_attachment_test_pack', [
            'model' => $this->model,
            'form' => new ActiveForm(),
            'input_types' => LibraryTestEnums::inputTypes(),
        ]);
    }
}
