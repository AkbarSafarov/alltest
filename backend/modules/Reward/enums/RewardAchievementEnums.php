<?php

namespace backend\modules\Reward\enums;

use Yii;


class RewardAchievementEnums
{
    public static function types()
    {
        return [
            'gradation' => [
                'linear' => [
                    'explorer' => [
                        'bronze' => 25,
                        'silver' => 50,
                        'gold' => 100,
                    ],
                    'nerd' => [
                        'bronze' => 75,
                        'silver' => 80,
                        'gold' => 90,
                        'flawless' => 100,
                    ],
                ],
                'exam' => [
                    'persistent' => [
                        'bronze' => 50,
                        'silver' => 100,
                        'gold' => 250,
                    ],
                    'intellectual' => [
                        'bronze' => 70,
                        'silver' => 80,
                        'gold' => 90,
                    ],
                ],
            ],
        ];
    }
}
