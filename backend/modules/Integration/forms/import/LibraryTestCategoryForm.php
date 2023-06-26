<?php

namespace backend\modules\Integration\forms\import;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

use backend\modules\Library\models\LibraryTestCategory;
use backend\modules\Library\models\LibraryTest;

use backend\modules\Integration\queue\import\LibraryTestCategoryQueue;
use backend\modules\Integration\queue\import\NotificationQueue;


class LibraryTestCategoryForm extends Model
{
    use \backend\modules\Integration\traits\import\ArchiveTrait;
    
    public $id;
    public $file;
    
    public $category;
    
    public function rules()
    {
        return [
            [['id', 'file'], 'required'],
            [['id'], 'exist', 'targetClass' => LibraryTestCategory::className(), 'targetAttribute' => 'id'],
            [['file'], 'file', 'extensions' => ['tar.gz'], 'checkExtensionByMimeType' => false, 'maxSize' => 1024 * 1024 * 50],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Курс'),
            'file' => Yii::t('app', 'Файл'),
        ];
    }
    
    public function beforeValidate()
    {
        if ($file = UploadedFile::getInstance($this, 'file')) {
            $this->file = $file;
        }
        
        return parent::beforeValidate();
    }
    
    public function process()
    {
        $this->category = LibraryTestCategory::findOne($this->id);
        
        $this->archive_base_path = Yii::getAlias("@backend/runtime/integration/" . uniqid());
        FileHelper::createDirectory($this->archive_base_path, 0777);
        copy($this->file->tempName, "$this->archive_base_path.tar.gz");
        (new \PharData("$this->archive_base_path.tar.gz"))->extractTo($this->archive_base_path);
        
        $this->archive_base_path .= '/library';
        $this->archive_drafts_path = $this->archive_base_path;
        
        if (!$this->checkSubfolders(['problem'])) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Структура архива составлена некорректно'));
            return false;
        }
        
        $file_content = new \SimpleXMLElement(file_get_contents("$this->archive_base_path/library.xml"));
        $tests_path_groups = array_chunk($file_content->xpath('/library/problem'), 100);
        
        if (!$tests_path_groups) {
            $this->deleteFiles(rtrim($this->archive_base_path, '/library'));
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Структура архива составлена некорректно'));
            return false;
        }
        
        if (!$this->prepareToSave()) {
            $this->deleteFiles(rtrim($this->archive_base_path, '/library'));
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Тема предметов не является пустой'));
            return false;
        }
        
        $form = new self([
            'archive_drafts_path' => $this->archive_drafts_path,
        ]);
        
        foreach ($tests_path_groups as $tests_path_group) {
            Yii::$app->queue->push(new LibraryTestCategoryQueue([
                'form' => $form,
                'category' => $this->category,
                'tests_path_xml_group' => array_map(fn($value) => $value->asXML(), $tests_path_group),
            ]));
        }
        
        Yii::$app->queue->push(new NotificationQueue([
            'form' => $form,
            'archive_base_path' => rtrim($this->archive_base_path, '/library'),
            'user_id' => Yii::$app->user->id,
            'message' => Yii::t('app_notification', 'Архив был успешно импортирован в "{value}"', [
                'value' => $this->category->name,
            ]),
        ]));
        
        return true;
    }
    
    public function prepareToSave()
    {
        $tests_exists = LibraryTest::find()
            ->byState(false)
            ->andWhere(['category_id' => $this->id])
            ->exists();
        
        return !$tests_exists;
    }
}
