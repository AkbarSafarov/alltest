<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class LibraryAttachmentTestWidget extends Widget
{
    public Model $model;
    public array $tests;
    
    public function run()
    {
        return $this->tests ? $this->render('library_attachment_test', [
            'group' => 'test',
            'model' => $this->model,
            'tests' => $this->tests,
        ]) : null;
    }
}
