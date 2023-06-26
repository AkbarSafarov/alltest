<?php

namespace backend\modules\Integration\forms\check;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


class CourseCertificateFileForm extends Model
{
    public $file;
    
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => ['docx'], 'maxSize' => 1024 * 1024 * 10],
        ];
    }
    
    public function attributeLabels()
    {
        return [
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
        $file = $this->file->tempName;
        
        $temp_file_qr_code = tempnam('', 'course_certificate_qr_code');
        $temp_file_doc = tempnam('', 'course_certificate_doc');
        $temp_file_pdf = tempnam('', 'course_certificate_pdf');
        
        //        QR code generator
        
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(1000),
            new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
        );
        
        $writer = new \BaconQrCode\Writer($renderer);
        $writer->writeFile('test', $temp_file_qr_code);
        
        //        Updating doc
        
        $template = new \PhpOffice\PhpWord\TemplateProcessor($file);
        $template->setValue('full_name', 'Tester');
        $template->setValue('course', 'Course name');
        $template->setValue('date', date('d.m.Y'));
        $template->setImageValue('qr_code', ['path' => $temp_file_qr_code, 'width' => 200, 'height' => 200]);
        $template->saveAs($temp_file_doc);
        
        //        Exporting and rendering pdf
        
        shell_exec("export HOME=/home/admin/tmp && /usr/bin/unoconv -f pdf -o $temp_file_pdf $temp_file_doc");
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=Certificate.pdf');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        
        readfile("$temp_file_pdf.pdf");
        
        unlink($temp_file_qr_code);
        unlink($temp_file_doc);
        unlink($temp_file_pdf);
        unlink("$temp_file_pdf.pdf");
        
        die;
    }
}
