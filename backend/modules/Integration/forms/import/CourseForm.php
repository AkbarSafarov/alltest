<?php

namespace backend\modules\Integration\forms\import;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CourseUnit;
use backend\modules\User\models\UserCourse;

use backend\modules\Integration\queue\import\CourseQueue;
use backend\modules\Integration\queue\import\NotificationQueue;


class CourseForm extends Model
{
    use \backend\modules\Integration\traits\import\ArchiveTrait;
    
    public $id;
    public $file;
    
    public $course;
    public $root_unit;
    
    public $structure = [];
    public $saved_subsections = [];
    public $media_files = [];
    
    public function rules()
    {
        return [
            [['id', 'file'], 'required'],
            [['id'], 'exist', 'targetClass' => Course::className(), 'targetAttribute' => 'id'],
            [['file'], 'file', 'extensions' => ['tar.gz'], 'checkExtensionByMimeType' => false, 'maxSize' => 1024 * 1024 * 1024],
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
        $this->course = Course::findOne($this->id);
        $this->root_unit = CourseUnit::find()->andWhere(['tree' => $this->id, 'depth' => 0])->one();
        
        $this->archive_base_path = Yii::getAlias("@backend/runtime/integration/" . uniqid());
        FileHelper::createDirectory($this->archive_base_path, 0777);
        copy($this->file->tempName, "$this->archive_base_path.tar.gz");
        (new \PharData("$this->archive_base_path.tar.gz"))->extractTo($this->archive_base_path);
        
        $this->archive_base_path .= '/course';
        $this->archive_drafts_path = "$this->archive_base_path/drafts";
        
        if (!$this->checkSubfolders(['chapter', 'drafts/vertical', 'sequential'])) {
            $this->deleteFiles(rtrim($this->archive_base_path, '/course'));
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Структура архива составлена некорректно'));
            return false;
        }
        
        if (!$this->checkUserCourseExistance()) {
            $this->deleteFiles(rtrim($this->archive_base_path, '/course'));
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Некоторые пользователи приобретали данный курс'));
            return false;
        }
        
        if (!$this->prepareToSave()) {
            $this->deleteFiles(rtrim($this->archive_base_path, '/course'));
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Курс не является пустым'));
            return false;
        }
        
        $this->parseMediaFiles();
        
        if (!$this->parseSections() || !$this->parseSubsections()) {
            $this->deleteFiles(rtrim($this->archive_base_path, '/course'));
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Структура архива составлена некорректно'));
            return false;
        }
        
        $this->saveSections();
        
        $this->parseUnits();
        
        return true;
    }
    
    public function checkUserCourseExistance()
    {
        return !UserCourse::find()->andWhere(['course_id' => $this->id])->exists();
    }
    
    public function prepareToSave()
    {
        $units_exists = CourseUnit::find()
            ->byState(false)
            ->withoutRoots()
            ->andWhere(['tree' => $this->id])
            ->exists();
        
        if ($units_exists) {
            return false;
        }
        
        $this->root_unit->updateAttributes(['lft' => 1, 'rgt' => 2]);
        
        CourseUnit::deleteAll([
            'and',
            ['tree' => $this->id],
            ['>', 'depth', 0],
        ]);
        
        return true;
    }
    
    public function parseMediaFiles()
    {
        if (is_dir("$this->archive_base_path/static")) {
            $media_extensions = array_map(fn($value) => "*.$value", Yii::$app->services->array->leaves(Yii::$app->params['extensions']));
            
            $this->media_files = FileHelper::findFiles("$this->archive_base_path/static", [
                'only' => $media_extensions,
            ]);
            
            $this->media_files = array_filter($this->media_files, function($value) {
                return filesize($value) <= Yii::$app->params['integration']['max_file_size'];
            });
            
            foreach ($this->media_files as &$value) {
                $value_from = str_replace($this->archive_base_path, null, $value);
                $value_from = str_replace('\\', '/', $value_from);
                
                $value = [
                    'from' => '"' . $value_from . '"',
                    'to' => '"' . Yii::$app->services->file->duplicate($value, 'uploaded', true) . '"',
                ];
            }
            
            $this->media_files = ArrayHelper::map($this->media_files, 'from', 'to');
        }
    }
    
    public function parseSections()
    {
        $section_files = FileHelper::findFiles("$this->archive_base_path/chapter", [
            'only' => ['*.xml'],
        ]);
        
        natsort($section_files);
        
        foreach (array_values($section_files) as $file) {
            $file_content = new \SimpleXMLElement(file_get_contents($file));
            
            if (!($section_label = Yii::$app->services->string->xmlAttribute($file_content, 'display_name'))) return false;
            
            $section_label = Html::encode($section_label);
            $this->structure[$section_label] = [];
            
            foreach ($file_content->xpath('/chapter/sequential') as $subsection) {
                $this->structure[$section_label][Yii::$app->services->string->xmlAttribute($subsection, 'url_name')] = null;
            }
        }
        
        return true;
    }
    
    public function parseSubsections()
    {
        array_walk_recursive($this->structure, function(&$value, $key) {
            $file = "$this->archive_base_path/sequential/$key.xml";
            
            if (!is_file($file)) return false;
            
            $file_content = new \SimpleXMLElement(file_get_contents($file));
            
            if (!($subsection_label = Yii::$app->services->string->xmlAttribute($file_content, 'display_name'))) return false;
            
            $value = Html::encode($subsection_label);
        });
        
        $this->structure = array_map('array_flip', $this->structure);
        
        return true;
    }
    
    public function saveSections()
    {
        foreach ($this->structure as $section_name => $subsections) {
            $section_model = new CourseUnit();
            $section_model->scenario = 'update_section';
            $section_model->name = $section_name;
            $section_model->appendTo($this->root_unit);
            
            foreach ($subsections as $subsection_name => $subsection_key) {
                $subsection_model = new CourseUnit();
                $subsection_model->scenario = 'update_section';
                $subsection_model->name = $subsection_name;
                $subsection_model->appendTo($section_model);
                
                $this->saved_subsections[$subsection_model->id] = $subsection_key;
            }
        }
    }
    
    public function parseUnits()
    {
        $form = new self([
            'archive_drafts_path' => $this->archive_drafts_path,
            'saved_subsections' => $this->saved_subsections,
            'media_files' => $this->media_files,
        ]);
        
        $unit_files = FileHelper::findFiles("$this->archive_drafts_path/vertical", [
            'only' => ['*.xml'],
        ]);
        
        $unit_file_groups = array_chunk($unit_files, 50);
        
        foreach ($unit_file_groups as $unit_file_group) {
            Yii::$app->queue->push(new CourseQueue([
                'form' => $form,
                'unit_file_group' => $unit_file_group,
            ]));
        }
        
        Yii::$app->queue->push(new NotificationQueue([
            'form' => $form,
            'archive_base_path' => rtrim($this->archive_base_path, '/course'),
            'user_id' => Yii::$app->user->id,
            'message' => Yii::t('app_notification', 'Архив был успешно импортирован в "{value}"', [
                'value' => $this->course->name,
            ]),
        ]));
        
        return true;
    }
}
