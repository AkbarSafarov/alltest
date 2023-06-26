<?php

namespace backend\modules\User\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;

use backend\modules\User\models\UserCourseUnit;


class UserCourseService extends ActiveService
{
    public function isActive()
    {
        $condition_1 = date('Y-m-d', strtotime($this->model->date_to)) >= date('Y-m-d');
        $condition_2 = $this->model->demo_datetime_to ? date('Y-m-d H:i:s', strtotime($this->model->demo_datetime_to)) >= date('Y-m-d H:i:s') : true;
        
        return $condition_1 && $condition_2;
    }
    
    public function unitsTree()
    {
        foreach ($this->model->units as $key_1 => $unit) {
            $unit_data = [
                'id' => $unit->id,
                'name' => $unit->unit_json['name'],
                'is_unlocked' => $unit->is_unlocked,
                'is_current' => $unit->is_current,
                'is_passed' => $unit->is_passed,
                'performance' => $this->model->course_json['type'] == 'linear' ? max($unit->performance ?: [0]) : avg($unit->performance ?: [0]),
                'icon' => ArrayHelper::getValue($unit, 'type.icon'),
            ];
            
            $unit_data = Yii::$app->services->array->toObjects($unit_data);
            
            switch ($unit->depth) {
                case 1:
                    $result[$key_1] = $unit_data;
                    break;
                case 2:
                    $key_2 = $key_1;
                    
                    while ($this->model->units[$key_2]->depth != 1) { $key_2--; }
                    
                    $result[$key_2]->children[$key_1] = $unit_data;
                    break;
                case 3:
                    $key_2 = $key_1;
                    $key_3 = $key_1;
                    
                    while ($this->model->units[$key_2]->depth != 2) { $key_2--; }
                    while ($this->model->units[$key_3]->depth != 1) { $key_3--; }
                    
                    $result[$key_3]->children[$key_2]->children[$key_1] = $unit_data;
                    break;
            }
        }
        
        return $result ?? [];
    }
    
    public function updatePerformance()
    {
        $units_performance = UserCourseUnit::find()
            ->andWhere([
                'and',
                'JSON_LENGTH(performance) > 0',
                ['tree' => $this->model->id],
            ])
            ->select(['performance'])
            ->column();
        
        $units_performance = array_filter($units_performance);
        
        foreach ($units_performance as &$u_p) {
            $u_p = json_decode($u_p);
            $u_p = $this->model->course_json['type'] == 'linear' ? max($u_p) : avg($u_p);
        }
        
        $this->model->updateAttributes(['performance' => avg($units_performance)]);
    }
    
    public function updateProgress()
    {
        $units_count = UserCourseUnit::find()
            ->andWhere([
                'tree' => $this->model->id,
                'depth' => 3,
            ])
            ->select([
                'COUNT(*) as total',
                'SUM(IF(is_passed, 1, 0)) as passed',
            ])
            ->asObject()->one();
        
        $this->model->updateAttributes(['progress' => floor($units_count->passed / $units_count->total * 100)]);
    }
    
    public function assignCertificate()
    {
        $course = $this->model->course;
        
        if (!$course->certificate_file || $this->model->certificate_file) {
            return false;
        }
        
        $file = Yii::getAlias("@frontend/web$course->certificate_file");
        
        if (!is_file($file)) {
            return false;
        }
        
        $temp_file_qr_code = tempnam('', 'course_certificate_qr_code');
        $temp_file_doc = tempnam('', 'course_certificate_doc');
        
        $date_time = date('Y-m-d');
        $certificate_name = Yii::$app->services->string->randomize();
        $certificate_file = Yii::getAlias("@frontend/web/uploaded/user-certificates/$date_time/$certificate_name.pdf");
        $certificate_url = str_replace(Yii::getAlias('@frontend/web'), null, $certificate_file);
        
        //        QR code generator
        
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(1000),
            new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
        );
        
        $writer = new \BaconQrCode\Writer($renderer);
        $writer->writeFile(Yii::$app->urlManagerFrontend->createAbsoluteFileUrl($certificate_url), $temp_file_qr_code);
        
        //        Updating doc
        
        $template = new \PhpOffice\PhpWord\TemplateProcessor($file);
        $template->setValue('full_name', $this->model->user->full_name);
        $template->setValue('course', $this->model->course_json['name']);
        $template->setValue('date', date('d.m.Y'));
        $template->setImageValue('qr_code', ['path' => $temp_file_qr_code, 'width' => 200, 'height' => 200]);
        $template->saveAs($temp_file_doc);
        
        //        Exporting and assigning pdf
        
        shell_exec("export HOME=/home/admin/tmp && /usr/bin/unoconv -f pdf -o $certificate_file $temp_file_doc");
        
        $this->model->updateAttributes(['certificate_file' => $certificate_url]);
        
        unlink($temp_file_qr_code);
        unlink($temp_file_doc);
    }
}