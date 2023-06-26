<?php

namespace backend\modules\Library\traits;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\Library\models\LibraryAttachmentTemplate;
use backend\modules\Library\models\LibraryAttachmentTest;
use backend\modules\Library\models\LibraryAttachmentTestPack;


trait LibraryAttachmentTrait
{
    public function behaviors()
    {
        return [
            'library_attachment_template' => [
                'class' => \backend\modules\Library\behaviors\LibraryAttachmentTemplateBehavior::className(),
                'relation' => 'attachmentTemplates',
            ],
            'library_attachment_test' => [
                'class' => \backend\modules\Library\behaviors\LibraryAttachmentTestBehavior::className(),
                'relation' => 'attachmentTests',
            ],
            'library_attachment_test_pack' => [
                'class' => \backend\modules\Library\behaviors\LibraryAttachmentTestPackBehavior::className(),
                'relation' => 'attachmentTestPacks',
            ],
        ];
    }
    
    public function getAttachmentTemplates()
    {
        return $this->hasMany(LibraryAttachmentTemplate::className(), ['model_id' => 'id'])
            ->onCondition(['model_class' => StringHelper::basename($this->className())])
            ->orderBy('sort');
    }
    
    public function getAttachmentTests()
    {
        return $this->hasMany(LibraryAttachmentTest::className(), ['model_id' => 'id'])
            ->onCondition(['model_class' => StringHelper::basename($this->className())])
            ->orderBy('sort');
    }
    
    public function getAttachmentTestPacks()
    {
        return $this->hasMany(LibraryAttachmentTestPack::className(), ['model_id' => 'id'])
            ->onCondition(['model_class' => StringHelper::basename($this->className())])
            ->orderBy('sort');
    }
}
