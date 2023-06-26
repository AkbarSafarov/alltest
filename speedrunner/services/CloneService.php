<?php

namespace speedrunner\services;

use Yii;
use yii\db\Query;


class CloneService
{
    public static function process(string $table_name, $condition = [], array $replace_attributes = [])
    {
        $attributes = Yii::$app->db->getTableSchema($table_name)->getColumnNames();
        
        $datetime = date('Y-m-d H:i:s');
        $replace_attributes['created_at'] = "('$datetime') as created_at";
        $replace_attributes['updated_at'] = "('$datetime') as updated_at";
        
        $exclude_attributes = ['id', 'students_now_quantity', 'status', 'active_unit_ids'];
        
        foreach ($exclude_attributes as $e_a) {
            if (($key = array_search($e_a, $attributes)) !== false) {
                unset($attributes[$key]);
            }
        }
        
        $select_attributes = $attributes;
        
        foreach ($select_attributes as &$s_a) {
            $s_a = $replace_attributes[$s_a] ?? $s_a;
        }
        
        $records = (new Query())->from($table_name)->andWhere($condition)->select($select_attributes)->all();
        
        return $records ? Yii::$app->db->createCommand()->batchInsert($table_name, $attributes, $records)->execute() : 0;
    }
}
