<?php

namespace backend\modules\Course\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use speedrunner\services\ActiveService;
use speedrunner\services\CloneService;

use backend\modules\Library\enums\LibraryAttachmentEnums;
use backend\modules\Course\models\CourseUnit;
use backend\modules\User\models\UserCourse;
use backend\modules\User\models\UserCourseUnit;
use backend\modules\Trash\models\Trash;


class CourseService extends ActiveService
{
    public function realPrice()
    {
        $percent = ArrayHelper::getValue($this->model, 'activeDiscount.percent', 0);
        return $this->model->price - ($this->model->price * $percent / 100);
    }
    
    public function isActive()
    {
        $conditions_1 = date('Y-m-d', strtotime($this->model->date_from)) <= date('Y-m-d');
        $conditions_2 = date('Y-m-d', strtotime($this->model->date_to)) >= date('Y-m-d');
        return $conditions_1 && $conditions_2;
    }
    
    public function isAllowed()
    {
        $conditions_1 = !Yii::$app->services->trash->isDeleted($this->model);
        $conditions_2 = date('Y-m-d', strtotime($this->model->date_to)) >= date('Y-m-d');
        
        switch (Yii::$app->user->identity->role) {
            case 'teacher':
                $conditions_3 = in_array(Yii::$app->user->id, ArrayHelper::getColumn($this->model->teachers, 'id'));
                break;
            default:
                $conditions_3 = true;
        }
        
        return $conditions_1 && $conditions_2 && $conditions_3;
    }
    
    public function isOwned()
    {
        return in_array($this->model->id, ArrayHelper::getValue(Yii::$app->user, 'identity.owned_courses', []));
    }
    
    public function updateUserCourseUnits($active_structure)
    {
        $this->model->updateAttributes(['active_structure' => $active_structure]);
        
        $units = Yii::$app->services->array->getChildrenRecursion($active_structure);
        $units = ArrayHelper::index($units ?: [], 'id');
        
        //        Getting user courses and units
        
        $user_courses = UserCourse::find()->andWHere(['course_id' => $this->model->id])->all();
        $user_course_units = UserCourseUnit::find()
            ->andWHere("CAST(unit_json->'$.tree' as SIGNED) = {$this->model->id}")
            ->select(['unit_id', "JSON_EXTRACT(unit_json, '$.tree')"])
            ->indexBy('unit_id')
            ->asArray()->all();
        
        //        Creating/updating and deleting user units
        
        $insert_records = [];
        $update_case_query = [
            'unit_json' => null,
            'type_id' => null,
            'lft' => null,
            'rgt' => null,
            'library_attachment_json' => null,
        ];
        
        foreach ($units as $unit) {
            if (isset($user_course_units[$unit['id']])) {
                if ($unit['attachmentTemplates']) {
                    foreach ($unit['attachmentTemplates'] as &$template) {
                        $template['template_information'] = json_decode($template['template_information']);
                    }
                }
                
                if ($unit['attachmentTests']) {
                    foreach ($unit['attachmentTests'] as &$test) {
                        $test['options'] = json_decode($test['options']);
                    }
                }
                
                if ($unit['attachmentTestPacks']) {
                    foreach ($unit['attachmentTestPacks'] as &$test_pack) {
                        $test_pack['category_ids'] = json_decode($test_pack['category_ids']);
                        $test_pack['input_types'] = json_decode($test_pack['input_types']);
                    }
                }
                
                $unit_json = json_encode($unit, JSON_UNESCAPED_UNICODE);
                $type_id = $unit['type_id'] ?: 'NULL';
                $library_attachment_json = json_encode([
                    'templates' => $unit['attachmentTemplates'],
                    'tests' => $unit['attachmentTests'],
                    'test_packs' => $unit['attachmentTestPacks'],
                ], JSON_UNESCAPED_UNICODE);
                
                $unit_json = addslashes($unit_json);
                $library_attachment_json = addslashes($library_attachment_json);
                
                $update_case_query['unit_json'] .= "WHEN unit_id = {$unit['id']} THEN ('$unit_json') ";
                $update_case_query['type_id'] .= "WHEN unit_id = {$unit['id']} THEN $type_id ";
                $update_case_query['lft'] .= "WHEN unit_id = {$unit['id']} THEN {$unit['lft']} ";
                $update_case_query['rgt'] .= "WHEN unit_id = {$unit['id']} THEN {$unit['rgt']} ";
                $update_case_query['library_attachment_json'] .= "WHEN unit_id = {$unit['id']} THEN ('$library_attachment_json') ";
                
                ArrayHelper::remove($user_course_units, $unit['id']);
            } else {
                foreach ($user_courses as $user_course) {
                    $insert_records[] = [
                        $unit['id'], $unit, $unit['type_id'], [],
                        $user_course->id, $unit['lft'], $unit['rgt'], $unit['depth'],
                        [
                            'templates' => $unit['attachmentTemplates'],
                            'tests' => $unit['attachmentTests'],
                            'test_packs' => $unit['attachmentTestPacks'],
                        ],
                    ];
                }
            }
        }
        
        if ($insert_records) {
            Yii::$app->db->createCommand()->batchInsert(
                'user_course_unit',
                ['unit_id', 'unit_json', 'type_id', 'performance', 'tree', 'lft', 'rgt', 'depth', 'library_attachment_json'],
                $insert_records
            )->execute();
        }
        
        if ($update_case_query['unit_json']) {
            $update_total_query[] = "SET unit_json = CASE {$update_case_query['unit_json']} ELSE unit_json END";
            $update_total_query[] = "type_id = CASE {$update_case_query['type_id']} ELSE type_id END";
            $update_total_query[] = "lft = CASE {$update_case_query['lft']} ELSE lft END";
            $update_total_query[] = "rgt = CASE {$update_case_query['rgt']} ELSE rgt END";
            $update_total_query[] = "library_attachment_json = CASE {$update_case_query['library_attachment_json']} ELSE library_attachment_json END";
            
            Yii::$app->db->createCommand('UPDATE user_course_unit ' . implode(', ', $update_total_query))->execute();
        }
        
        UserCourseUnit::deleteAll(['unit_id' => array_keys($user_course_units)]);
        
        //        Unlocking first unit
        
        UserCourseUnit::updateAll(['is_unlocked' => 1], [
            'and',
            "CAST(unit_json->'$.tree' as SIGNED) = {$this->model->id}",
            ['<=', 'lft', 4],
        ]);
        
        //        Updating user course progress
        
        Yii::$app->db->createCommand(
            'UPDATE user_course SET progress = IFNULL((
                SELECT FLOOR(SUM(user_course_unit.is_passed) / COUNT(user_course_unit.id) * 100)
                FROM user_course_unit
                WHERE user_course_unit.tree = user_course.id AND user_course_unit.depth = 3
                GROUP BY user_course.id
            ), 0)'
        )->execute();
    }
    
    public function clone()
    {
        //        Cloning course
        
        $class_name = $this->model::className();
        
        $new_model = new $class_name;
        $new_model->scenario = 'empty';
        $new_model->setAttributes($this->model->attributes, false);
        $new_model->id = null;
        $new_model->slug = null;
        $new_model->certificate_file = null;
        $new_model->name = $this->model->name . ' - ' . Yii::t('app', 'копия');
        $new_model->date_from = date('d.m.Y', strtotime('+1 year'));
        $new_model->date_to = date('d.m.Y', strtotime('+1 year'));
        $new_model->created_at = date('Y-m-d H:i:s');
        $new_model->updated_at = date('Y-m-d H:i:s');
        
        if (!$new_model->save()) {
            return false;
        }
        
        //        Cloning relations
        
        $relation_tables = ['course_teacher_ref', 'course_advantage', 'course_author'];
        
        foreach ($relation_tables as $r_t) {
            CloneService::process(
                $r_t,
                ['course_id' => $this->model->id],
                ['course_id' => "($new_model->id) as course_id"]
            );
        }
        
        //        Cloning course library attachments
        
        foreach (LibraryAttachmentEnums::tables() as $table) {
            CloneService::process(
                $table,
                ['model_class' => 'Course', 'model_id' => $this->model->id],
                ['model_id' => "($new_model->id) as model_id"]
            );
        }
        
        //        Cloning units
        
        $clone_unit_count = CloneService::process(
            'course_unit',
            ['tree' => $this->model->id],
            ['tree' => "($new_model->id) as tree"]
        );
        
        if ($clone_unit_count) {
            $first_clone_unit_id = Yii::$app->db->getMasterPdo()->lastInsertId();
            $last_clone_unit_id = $first_clone_unit_id + $clone_unit_count - 1;
            
            $unit_ids = CourseUnit::find()->where(['tree' => $this->model->id])->select('id')->column();
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
            
            //        Cloning units library attachments
            
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