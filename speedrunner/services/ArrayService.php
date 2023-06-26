<?php

namespace speedrunner\services;

use Yii;


class ArrayService
{
    public static function buildFullPath(array $array, $attr, $children_attr = 'children', $parent_value = null, $separator = '/')
    {
        foreach ($array as &$arr) {
            $arr[$attr] = $parent_value ? $parent_value . $separator . $arr[$attr] : $arr[$attr];
            
            if ($arr[$children_attr]) {
                $arr[$children_attr] = self::buildFullPath($arr[$children_attr], $attr, $children_attr, $arr[$attr], $separator);
            }
        }
        
        return $array;
    }
    
    public static function toObjects(array $array)
    {
        return json_decode(json_encode($array, JSON_UNESCAPED_UNICODE), false);
    }
    
    public static function leaves(array $array, $separator = null, $parent_value = null, $result = [])
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $parent_value .= $key . $separator;
                $result = self::leaves($value, $separator, $parent_value, $result);
                $parent_value = null;
            } else {
                $result[] = $separator ? $parent_value . $value : $value;
            }
        }
        
        return $result;
    }
    
    public static function getChildrenRecursion($array, $result = null) {
        foreach ($array as $arr) {
            if ($arr['children']) {
                $result = self::getChildrenRecursion($arr['children'], $result);
            }
            
            unset($arr['children']);
            $result[] = $arr;
        }
        
        return $result;
    }
}
