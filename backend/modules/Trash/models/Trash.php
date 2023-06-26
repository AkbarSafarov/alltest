<?php

namespace backend\modules\Trash\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class Trash extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%trash}}';
    }
}
