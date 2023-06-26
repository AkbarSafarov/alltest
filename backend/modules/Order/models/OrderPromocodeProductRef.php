<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;


class OrderPromocodeProductRef extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_promocode_product_ref}}';
    }
}
