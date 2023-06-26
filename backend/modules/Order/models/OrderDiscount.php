<?php

namespace backend\modules\Order\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use speedrunner\validators\EitherValidator;
use speedrunner\validators\DatesCompareValidator;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;


class OrderDiscount extends ActiveRecord
{
    public $courses_tmp;
    public $packages_tmp;
    
    public $is_for_all_courses;
    public $is_for_all_packages;
    
    public static function tableName()
    {
        return '{{%order_discount}}';
    }
    
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => \speedrunner\behaviors\TranslationBehavior::className(),
                'attributes' => ['name'],
            ],
            'relations_many_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'manyMany',
                'attributes' => [
                    'courses_tmp' => [
                        'model' => new OrderDiscountProductRef(),
                        'relation' => 'courses',
                        'attributes' => [
                            'main' => 'discount_id',
                            'relational' => 'model_id',
                            'extra' => ['model_class' => 'Course'],
                        ],
                    ],
                    'packages_tmp' => [
                        'model' => new OrderDiscountProductRef(),
                        'relation' => 'packages',
                        'attributes' => [
                            'main' => 'discount_id',
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
            [['name', 'percent', 'date_from', 'date_to'], 'required'],
            
            [['name'], 'string', 'max' => 100],
            [['percent'], 'integer', 'min' => 1, 'max' => 100],
            [['date_from', 'date_to'], 'date', 'format' => 'php: d.m.Y'],
            [['is_for_all_courses', 'is_for_all_packages'], 'boolean'],
            
            [['courses_tmp'], 'exist', 'targetClass' => Course::className(), 'targetAttribute' => 'id', 'allowArray' => true],
            [['packages_tmp'], 'exist', 'targetClass' => CoursePackage::className(), 'targetAttribute' => 'id', 'allowArray' => true],
            
            [['date_to'], DatesCompareValidator::className()],
            [['courses_tmp'], EitherValidator::className(), 'either_attributes' => ['packages_tmp']],
            [['packages_tmp'], EitherValidator::className(), 'either_attributes' => ['courses_tmp']],
            
            [['courses_tmp', 'packages_tmp'], 'uniqueProductsValidation'],
        ];
    }
    
    public function uniqueProductsValidation($attribute, $params, $validator)
    {
        switch ($attribute) {
            case 'courses_tmp':
                $model_class = 'Course';
                break;
            case 'packages_tmp':
                $model_class = 'CoursePackage';
                break;
        }
        
        $date_from = date('Y-m-d', strtotime($this->date_from));
        $date_to = date('Y-m-d', strtotime($this->date_to));
        
        $model = self::find()
            ->joinWith(['productsRef'], false)
            ->andWhere([
                'and',
                ['order_discount_product_ref.model_class' => $model_class],
                ['order_discount_product_ref.model_id' => $this->{$attribute}],
                ['!=', 'order_discount.id', $this->id],
            ])
            ->andWhere([
                'or',
                [
                    'and',
                    ['<=', 'order_discount.date_from', $date_from],
                    ['>=', 'order_discount.date_to', $date_from],
                ],
                [
                    'and',
                    ['<=', 'order_discount.date_from', $date_to],
                    ['>=', 'order_discount.date_to', $date_to],
                ],
                [
                    'and',
                    ['>=', 'order_discount.date_from', $date_from],
                    ['<=', 'order_discount.date_to', $date_to],
                ],
            ])
            ->groupBy('order_discount.id')
            ->one();
        
        if ($model) {
            return $this->addError($attribute, Yii::t('app', 'Некоторые из этих {attribute} уже используются в скидке №{discount_id}', [
                'attribute' => $this->getAttributeLabel($attribute),
                'discount_id' => $model->id,
            ]));
        }
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'percent' => Yii::t('app', 'Процент'),
            'date_from' => Yii::t('app', 'Дата (от)'),
            'date_to' => Yii::t('app', 'Дата (до)'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'courses_tmp' => Yii::t('app', 'Курсы'),
            'packages_tmp' => Yii::t('app', 'Пакеты курсов'),
            'is_for_all_courses' => Yii::t('app', 'Все курсы'),
            'is_for_all_packages' => Yii::t('app', 'Все пакеты'),
        ];
    }
    
    public function getProductsRef()
    {
        return $this->hasMany(OrderDiscountProductRef::className(), ['discount_id' => 'id']);
    }
    
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'model_id'])
            ->viaTable('order_discount_product_ref', ['discount_id' => 'id'], function ($query) {
                $query->onCondition(['model_class' => 'Course']);
            });
    }
    
    public function getPackages()
    {
        return $this->hasMany(CoursePackage::className(), ['id' => 'model_id'])
            ->viaTable('order_discount_product_ref', ['discount_id' => 'id'], function ($query) {
                $query->onCondition(['model_class' => 'CoursePackage']);
            });
    }
    
    public function beforeValidate()
    {
        $this->courses_tmp = $this->is_for_all_courses ? Course::find()->column() : $this->courses_tmp;
        $this->packages_tmp = $this->is_for_all_packages ? CoursePackage::find()->column() : $this->packages_tmp;
        
        return parent::beforeValidate();
    }
}
