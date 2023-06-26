<?php

namespace backend\modules\Reward\enums;

use Yii;


class RewardLeagueEnums
{
    public static function types()
    {
        return [
            'explorer',
            'nerd',
            'persistent',
            'intellectual',
        ];
    }
    
    public static function levels()
    {
        return [
            'bronze' => [30, 49],
            'silver' => [50, 69],
            'gold' => [70, 89],
            'platinum' => [90, 98],
            'diamond' => [99, 100],
        ];
    }
}
