<?php

namespace speedrunner\services;

use Yii;


class DateService
{
    public static function interval($date_from, $date_to)
    {
        $date_from = date_create(date('Y-m-d', strtotime($date_from)));
        $date_to = date_create(date('Y-m-d', strtotime($date_to)));
        $interval = date_diff($date_from, $date_to);
        
        $y = null;
        $m = null;
        $d = null;
        
        if ($interval->y > 0) {
            if ($interval->y > 4)
                $y .= Yii::t('app', '{count} лет', ['count' => $interval->y]); 
            else if ($interval->y == 1)
                $y .= Yii::t('app', '{count} год', ['count' => $interval->y]); 
            else
                $y .= Yii::t('app', '{count} года', ['count' => $interval->y]); 
            $y .= ', ';
        }
        
        if ($interval->m > 0) {
            if ($interval->m > 4)
                $m .= Yii::t('app', '{count} месяцев', ['count' => $interval->m]); 
            else if ($interval->m > 1)
                $m .= Yii::t('app', '{count} месяца', ['count' => $interval->m]); 
            else
                $m .= Yii::t('app', '{count} месяц', ['count' => $interval->m]); 
            $m .= ', ';
        }
        
        if ($interval->d > 0) {
            if ($interval->d > 4)
                $d .= Yii::t('app', '{count} дней', ['count' => $interval->d]);
            else if ($interval->d > 1)
                $d .= Yii::t('app', '{count} дня', ['count' => $interval->d]);
            else
                $d .= Yii::t('app', '{count} день', ['count' => $interval->d]);
        }
        
        return $y . $m . $d;
    }
}
