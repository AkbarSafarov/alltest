<?php

namespace speedrunner\services;

use Yii;


class StringService
{
    public static function randomize(int $length = 16)
    {
        return md5(uniqid() . Yii::$app->security->generateRandomString($length));
    }
    
    public static function getInbetweenStrings($string, $pattern = null)
    {
        if (!$pattern) {
            $template = '[\w\dа-яёА-ЯЁ_]';
            $template_space = '[\w\dа-яёА-ЯЁ_ ]+';
            $pattern = "{({$template}{$template_space}{$template}):([\w\d_]+)}";
        }
        
        $matches = [];
        preg_match_all($pattern, $string, $matches);
        
        return $matches;
    }
    
    public static function firstLetters($string)
    {
        $result = array_map(function($value) {
            return ucfirst(substr($value, 0, 1));
        }, explode(' ', $string));
        
        return implode(null, $result);
    }
    
    public static function xmlAttribute($xml, $attribute)
    {
        return isset($xml[$attribute]) ? (string)$xml[$attribute] : null;
    }
}
