<?php

namespace backend\modules\Course\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;
use speedrunner\services\CloneService;

use backend\modules\Library\enums\LibraryAttachmentEnums;
use backend\modules\User\models\UserCourseUnit;
use backend\modules\Trash\models\Trash;


class CourseUnitService extends ActiveService
{
    public function isAllowed()
    {
        return $this->model->course->service->isAllowed();
    }
    
    public function updateUserCourseUnit()
    {
        UserCourseUnit::updateAll([
            'unit_json' => $this->model->attributes,
            'type_id' => $this->model->type_id,
            'library_attachment_json' => [
                'templates' => ArrayHelper::getColumn($this->model->attachmentTemplates, 'attributes'),
                'tests' => ArrayHelper::getColumn($this->model->attachmentTests, 'attributes'),
                'test_packs' => ArrayHelper::getColumn($this->model->attachmentTestPacks, 'attributes'),
            ],
        ], ['unit_id' => $this->model->id]);
    }
    
    public function clone()
    {
        //        Cloning unit
        
        $class_name = $this->model::className();
        
        $new_model = new $class_name;
        $new_model->scenario = 'empty';
        $new_model->setAttributes($this->model->attributes, false);
        $new_model->id = null;
        $new_model->name = $this->model->name . ' - ' . Yii::t('app', 'копия');
        $new_model->status = 'development';
        $new_model->created_at = date('Y-m-d H:i:s');
        $new_model->updated_at = date('Y-m-d H:i:s');
        
        if (!$new_model->insertAfter($this->model)) {
            return false;
        }
        
        $increment = $this->model->allChildren()->count() * 2;
        $increment_children = $increment + 2;
        
        $new_model->updateCounters(['rgt' => $increment]);
        
        //        Pushing apart tree elements
        
        $this->model->updateAllCounters(['rgt' => $increment], [
            'and',
            ['tree' => $this->model->tree],
            ['<', 'lft', $this->model->lft],
            ['>', 'rgt', $this->model->rgt],
        ]);
        
        $this->model->updateAllCounters(['lft' => $increment, 'rgt' => $increment], [
            'and',
            ['tree' => $this->model->tree],
            ['>', 'lft', $this->model->lft],
            ['>', 'rgt', $this->model->rgt],
            ['!=', 'id', $new_model->id],
        ]);
        
        //        Cloning unit library attachments
        
        foreach (LibraryAttachmentEnums::tables() as $table) {
            CloneService::process(
                $table,
                ['model_class' => 'CourseUnit', 'model_id' => $this->model->id],
                ['model_id' => "($new_model->id) as model_id"]
            );
        }
        
        //        Cloning children
        
        $clone_unit_count = CloneService::process(
            'course_unit',
            [
                'and',
                ['tree' => $this->model->tree],
                ['>', 'lft', $this->model->lft],
                ['<', 'rgt', $this->model->rgt],
            ],
            [
                'lft' => "lft + ({$increment_children}) as lft",
                'rgt' => "rgt + ({$increment_children}) as rgt",
            ]
        );
        
        if ($clone_unit_count) {
            $first_clone_unit_id = Yii::$app->db->getMasterPdo()->lastInsertId();
            $last_clone_unit_id = $first_clone_unit_id + $clone_unit_count - 1;
            
            $unit_ids = $this->model->allChildren()->orderBy('id')->column();
            $unit_ids = array_combine($unit_ids, range($first_clone_unit_id, $last_clone_unit_id));
            
            //        Collecting trash model ids
            
            $trash_unit_ids = Trash::find()
                ->andWhere([
                    'trash_model_class' => 'CourseUnit',
                    'trash_model_id' => array_keys($unit_ids),
                ])
                ->select('trash_model_id')
                ->indexBy('trash_model_id')
                ->asArray()->all();
            
            $new_trash_unit_ids = array_map(fn($value) => ['CourseUnit', $value], ArrayHelper::filter($unit_ids, array_keys($trash_unit_ids)));
            
            Yii::$app->db->createCommand()->batchInsert('trash', ['trash_model_class', 'trash_model_id'], $new_trash_unit_ids)->execute();
            
            //        Cloning children library attachments
            
            foreach ($unit_ids as $unit_old_id => &$unit_new_id) {
                $unit_new_id = "WHEN model_id = '$unit_old_id' THEN '$unit_new_id'";
            }
            
            foreach (LibraryAttachmentEnums::tables() as $table) {
                CloneService::process(
                    $table,
                    ['model_class' => 'CourseUnit', 'model_id' => array_keys($unit_ids)],
                    ['model_id' => "(CASE " . implode(' ', $unit_ids) . " END) as model_id"]
                );
            }
        }
        
        return true;
    }
}