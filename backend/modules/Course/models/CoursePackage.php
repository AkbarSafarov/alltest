<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use speedrunner\validators\DatesCompareValidator;

use backend\modules\System\models\SystemLanguage;
use backend\modules\Order\models\OrderDiscount;


class CoursePackage extends ActiveRecord
{
    public $students_total_quantity;
    
    public $courses_tmp;
    
    public static function tableName()
    {
        return '{{%course_package}}';
    }
    
    public function behaviors()
    {
        return [
            'relations_many_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'manyMany',
                'attributes' => [
                    'courses_tmp' => [
                        'model' => new CoursePackageRef(),
                        'relation' => 'courses',
                        'attributes' => [
                            'main' => 'package_id',
                            'relational' => 'course_id',
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'image', 'price', 'language_id', 'date_from', 'optimal_time', 'courses_tmp'], 'required'],
            [['name', 'optimal_time'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 1000],
            [['description'], 'string'],
            [['price', 'students_start_quantity'], 'integer', 'min' => 0],
            [['date_from'], 'date', 'format' => 'php: d.m.Y'],
            
            [['language_id'], 'exist', 'targetClass' => SystemLanguage::className(), 'targetAttribute' => 'id'],
            [['courses_tmp'], 'exist', 'targetClass' => Course::className(), 'targetAttribute' => 'id', 'allowArray' => true],
            
            [['date_from'], DatesCompareValidator::className()],
            [['date_from'], 'courseDatesValidation'],
        ];
    }
    
    public function courseDatesValidation($attribute, $params, $validator)
    {
        $courses = Course::find()->andWhere(['id' => $this->courses_tmp])->asArray()->all();
        $min_date = strtotime(max(ArrayHelper::getColumn($courses, 'date_from')));
        
        if (strtotime($this->date_from) < $min_date) {
            return $this->addError('date_from', Yii::t('app', '{date} должная быть не меньше {available_date}', [
                'date' => $this->getAttributeLabel('date_from'),
                'available_date' => date('d.m.Y', $min_date),
            ]));
        }
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'image' => Yii::t('app', 'Изображение'),
            'description' => Yii::t('app', 'Описание'),
            'price' => Yii::t('app', 'Цена'),
            'language_id' => Yii::t('app', 'Язык'),
            'date_from' => Yii::t('app', 'Дата (от)'),
            'date_to' => Yii::t('app', 'Дата (до)'),
            'optimal_time' => Yii::t('app', 'Оптимальное время для прохождения'),
            'students_start_quantity' => Yii::t('app', 'Начальное значение для счётчика студентов'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'courses_tmp' => Yii::t('app', 'Курсы'),
        ];
    }
    
    public function getLanguage()
    {
        return $this->hasOne(SystemLanguage::className(), ['id' => 'language_id']);
    }
    
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'course_id'])
            ->viaTable('course_package_ref', ['package_id' => 'id']);
    }
    
    public function getActiveCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'course_id'])
            ->viaTable('course_package_ref', ['package_id' => 'id'])
            ->active();
    }
    
    public function getActiveDiscount()
    {
        return $this->hasOne(OrderDiscount::className(), ['id' => 'discount_id'])
            ->onCondition([
                'and',
                ['<=', 'date_from', date('Y-m-d')],
                ['>=', 'date_to', date('Y-m-d')],
            ])
            ->viaTable('order_discount_product_ref', ['model_id' => 'id'], function($query) {
                $query->onCondition(['model_class' => 'CoursePackage']);
            });
    }
    
    public function beforeValidate()
    {
        $courses = Course::find()->andWhere(['id' => $this->courses_tmp])->select(['date_to'])->asArray()->all();
        $this->date_to = $courses ? date('d.m.Y', strtotime(min(ArrayHelper::getColumn($courses, 'date_to')))) : null;
        
        return parent::beforeValidate();
    }
    
    public function afterFind()
    {
        $this->students_total_quantity = $this->students_start_quantity + $this->students_now_quantity;
        return parent::afterFind();
    }
}
