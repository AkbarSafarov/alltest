<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use speedrunner\validators\SlugValidator;
use speedrunner\validators\DatesCompareValidator;
use speedrunner\validators\EitherValidator;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;


class OrderPromocode extends ActiveRecord
{
    public $courses_tmp;
    public $packages_tmp;
    
    public static function tableName()
    {
        return '{{%order_promocode}}';
    }
    
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => \speedrunner\behaviors\TranslationBehavior::className(),
                'attributes' => ['name', 'description'],
            ],
            'relations_many_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'manyMany',
                'attributes' => [
                    'courses_tmp' => [
                        'model' => new OrderPromocodeProductRef(),
                        'relation' => 'courses',
                        'attributes' => [
                            'main' => 'promocode_id',
                            'relational' => 'model_id',
                            'extra' => ['model_class' => 'Course'],
                        ],
                    ],
                    'packages_tmp' => [
                        'model' => new OrderPromocodeProductRef(),
                        'relation' => 'packages',
                        'attributes' => [
                            'main' => 'promocode_id',
                            'relational' => 'model_id',
                            'extra' => ['model_class' => 'CoursePackage'],
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'percent'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['percent'], 'integer', 'min' => 1, 'max' => 100],
            [['description'], 'string'],
            [['max_activations', 'max_products'], 'integer', 'min' => 1, 'max' => 10000],
            [['date_from', 'date_to'], 'date', 'format' => 'php: d.m.Y'],
            [['key'], 'default', 'value' => md5(uniqid())],
            
            [['key'], SlugValidator::className(), 'params' => ['pattern' => '/^[a-zA-Z0-9]+$/', 'min' => 7, 'max' => 32]],
            [['date_to'], DatesCompareValidator::className()],
            [['date_from', 'date_to'], EitherValidator::className(), 'either_attributes' => ['max_activations']],
            [['max_activations'], EitherValidator::className(), 'either_attributes' => ['date_from', 'date_to']],
            
            [['courses_tmp'], 'exist', 'targetClass' => Course::className(), 'targetAttribute' => 'id', 'allowArray' => true],
            [['packages_tmp'], 'exist', 'targetClass' => CoursePackage::className(), 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'percent' => Yii::t('app', 'Процент'),
            'description' => Yii::t('app', 'Описание'),
            'key' => Yii::t('app', 'Секретный ключ'),
            'max_activations' => Yii::t('app', 'Общее число активаций'),
            'used_activations' => Yii::t('app', 'Активировано'),
            'date_from' => Yii::t('app', 'Дата (от)'),
            'date_to' => Yii::t('app', 'Дата (до)'),
            'max_products' => Yii::t('app', 'Максимальное количество продуктов'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'courses_tmp' => Yii::t('app', 'Курсы'),
            'packages_tmp' => Yii::t('app', 'Пакеты курсов'),
        ];
    }
    
    public function getProductsRef()
    {
        return $this->hasMany(OrderPromocodeProductRef::className(), ['promocode_id' => 'id']);
    }
    
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'model_id'])
            ->viaTable('order_promocode_product_ref', ['promocode_id' => 'id'], function ($query) {
                $query->onCondition(['model_class' => 'Course']);
            });
    }
    
    public function getPackages()
    {
        return $this->hasMany(CoursePackage::className(), ['id' => 'model_id'])
            ->viaTable('order_promocode_product_ref', ['promocode_id' => 'id'], function ($query) {
                $query->onCondition(['model_class' => 'CoursePackage']);
            });
    }
}
