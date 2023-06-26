<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;


class OrderDiscountProductRef extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_discount_product_ref}}';
    }
}
