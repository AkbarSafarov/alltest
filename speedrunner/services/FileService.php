<?php

namespace speedrunner\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

use yii\imagine\Image;
use Imagine\Image\ManipulatorInterface;


class FileService
{
    private $base_dir;
    
    public function __construct()
    {
        $this->base_dir = Yii::getAlias("@frontend/web");
    }
    
    public function save(UploadedFile $file, $save_dir = 'uploaded', $width_height = [])
    {
        $save_dir .= '/' . date('Y-m-d');
        $dir = "$this->base_dir/$save_dir";
        FileHelper::createDirectory($dir);
        
        $file_name = md5(strtotime('now') . Yii::$app->security->generateRandomString()) . '.' . $file->extension;
        
        if ($width_height) {
            $image = new Image();
            $image::$thumbnailBackgroundAlpha = 0;
            $image = $image->thumbnail($file->tempName, $width_height[0], $width_height[1], ManipulatorInterface::THUMBNAIL_INSET);
            $image->save("$dir/$file_name", ['quality' => 100]);
        } else {
            $file->saveAs("$dir/$file_name");
        }
        
        return "/$save_dir/$file_name";
    }
    
    public function duplicate($file_url, $save_dir, $is_absolute_path = false)
    {
        $file_url = $is_absolute_path ? $file_url : Yii::getAlias("@frontend/web/$file_url");
        
        if (is_file($file_url)) {
            $extension = pathinfo($file_url)['extension'];
            
            $save_dir .= '/' . date('Y-m-d');
            $dir = "$this->base_dir/$save_dir";
            FileHelper::createDirectory($dir);
            
            $file_name = md5(strtotime('now') . Yii::$app->security->generateRandomString()) . '.' . $extension;
            
            copy($file_url, "$dir/$file_name");
            return "/$save_dir/$file_name";
        }
    }
    
    public function delete($file_url)
    {
        $file_url = "$this->base_dir/$file_url";
        is_file($file_url) ? unlink($file_url) : null;
    }
}
