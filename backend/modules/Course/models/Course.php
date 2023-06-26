<?php

namespace backend\modules\Course\models;

use Yii;
use speedrunner\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use speedrunner\validators\SlugValidator;
use speedrunner\validators\DatesCompareValidator;

use backend\modules\Library\traits\LibraryAttachmentTrait;
use backend\modules\User\models\User;
use backend\modules\User\models\UserCourse;
use backend\modules\System\models\SystemLanguage;
use backend\modules\Order\models\OrderDiscount;


class Course extends ActiveRecord
{
    use LibraryAttachmentTrait {
        behaviors as libraryAttachmentBehaviors;
    }
    
    public $students_total_quantity;
    
    public $teachers_tmp;
    public $advantages_tmp;
    public $authors_tmp;
    
    public static function tableName()
    {
        return '{{%course}}';
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge($this->libraryAttachmentBehaviors(), [
            'sluggable' => [
                'class' => \yii\behaviors\SluggableBehavior::className(),
                'attribute' => ['name', 'language.code'],
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique' => true,
            ],
            'file' => [
                'class' => \speedrunner\behaviors\FileBehavior::className(),
                'attributes' => ['certificate_file'],
                'multiple' => false,
                'save_dir' => 'uploaded/certificates',
            ],
            'seo_meta' => [
                'class' => \speedrunner\behaviors\SeoMetaBehavior::className(),
            ],
            'relations_one_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'oneMany',
                'attributes' => [
                    'advantages_tmp' => [
                        'model' => new CourseAdvantage(),
                        'relation' => 'advantages',
                        'attributes' => [
                            'main' => 'course_id',
                            'relational' => ['name', 'icon'],
                        ],
                    ],
                    'authors_tmp' => [
                        'model' => new CourseAuthor(),
                        'relation' => 'authors',
                        'attributes' => [
                            'main' => 'course_id',
                            'relational' => ['full_name', 'image', 'experience'],
                        ],
                    ],
                ],
            ],
            'relations_many_many' => [
                'class' => \speedrunner\behaviors\RelationBehavior::className(),
                'type' => 'manyMany',
                'attributes' => [
                    'teachers_tmp' => [
                        'model' => new CourseTeacherRef(),
                        'relation' => 'teachers',
                        'attributes' => [
                            'main' => 'course_id',
                            'relational' => 'teacher_id',
                        ],
                    ],
                ],
            ],
        ]);
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'empty' => [],
        ]);
    }
    
    public function rules()
    {
        return [
            [['name', 'type', 'date_from', 'date_to', 'language_id', 'optimal_time', 'price', 'preview_image'], 'required'],
            [['passing_percent'], 'required', 'enableClientValidation' => false, 'when' => fn ($model) => $model->type == 'linear'],
            
            [['name', 'author', 'optimal_time'], 'string', 'max' => 100],
            [['author'], 'default', 'value' => 'Alltest'],
            [['preview_image', 'image', 'video', 'short_description'], 'string', 'max' => 1000],
            [['full_description'], 'string'],
            [['price', 'demo_time', 'students_start_quantity'], 'integer', 'min' => 0],
            [['demo_time'], 'default', 'value' => 48, 'when' => fn ($model) => $model->type == 'linear'],
            [['passing_percent'], 'integer', 'min' => 0, 'max' => 100],
            [['date_from', 'date_to'], 'date', 'format' => 'php: d.m.Y'],
            
            [['type'], 'in', 'range' => array_keys($this->enums->types())],
            [['advantages_tmp', 'authors_tmp'], 'safe'],
            
            [['certificate_file'], 'file', 'extensions' => ['docx'], 'maxSize' => 1024 * 1024 * 10],
            
            [['slug'], SlugValidator::className()],
            [['date_to'], DatesCompareValidator::className()],
            
            [['language_id'], 'exist', 'targetClass' => SystemLanguage::className(), 'targetAttribute' => 'id'],
            [['teachers_tmp'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id', 'allowArray' => true, 'filter' => function($query) {
                ArrayHelper::remove($query->where, 1);
                $query->andWhere(['role' => 'teacher']);
            }],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' => Yii::t('app', 'Название'),
            'slug' => Yii::t('app', 'Ссылка'),
            'type' => Yii::t('app', 'Тип'),
            'date_from' => Yii::t('app', 'Дата (от)'),
            'date_to' => Yii::t('app', 'Дата (до)'),
            'language_id' => Yii::t('app', 'Язык'),
            'author' => Yii::t('app', 'Автор'),
            'price' => Yii::t('app', 'Цена'),
            'optimal_time' => Yii::t('app', 'Оптимальное время для прохождения'),
            'demo_time' => Yii::t('app', 'Время для ознакомления (в часах)'),
            'students_start_quantity' => Yii::t('app', 'Начальное значение для счётчика студентов'),
            'passing_percent' => Yii::t('app', 'Проходной процент'),
            'certificate_file' => Yii::t('app', 'Сертификат'),
            
            'preview_image' => Yii::t('app', 'Изображение (превью)'),
            'image' => Yii::t('app', 'Изображение (внутреннее)'),
            'video' => Yii::t('app', 'Видео'),
            'short_description' => Yii::t('app', 'Краткое описание'),
            'full_description' => Yii::t('app', 'Полное описание'),
            
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            
            'teachers_tmp' => Yii::t('app', 'Учителя'),
            'advantages_tmp' => Yii::t('app', 'Преимущества'),
            'authors_tmp' => Yii::t('app', 'Авторы'),
        ];
    }
    
    public function getLanguage()
    {
        return $this->hasOne(SystemLanguage::className(), ['id' => 'language_id']);
    }
    
    public function getTeachers()
    {
        return $this->hasMany(User::className(), ['id' => 'teacher_id'])
            ->viaTable('course_teacher_ref', ['course_id' => 'id']);
    }
    
    public function getAdvantages()
    {
        return $this->hasMany(CourseAdvantage::className(), ['course_id' => 'id'])->orderBy('sort');
    }
    
    public function getAuthors()
    {
        return $this->hasMany(CourseAuthor::className(), ['course_id' => 'id'])->orderBy('sort');
    }
    
    public function getPackages()
    {
        return $this->hasMany(CoursePackage::className(), ['id' => 'package_id'])
            ->viaTable('course_package_ref', ['course_id' => 'id']);
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
                $query->onCondition(['model_class' => 'Course']);
            });
    }
    
    public function getActiveUnits()
    {
        return $this->hasMany(CourseUnit::className(), ['tree' => 'id'])->byState(false)->orderBy('lft');
    }
    
    public function getUserCourse()
    {
        return $this->hasOne(UserCourse::className(), ['course_id' => 'id'])->onCondition(['user_id' => Yii::$app->user->id]);
    }
    
    public static function find()
    {
        $query = parent::find()->select(['course.*', "('[]') as active_structure"]);
        
        if (Yii::$app->id == 'app-backend' && ArrayHelper::getValue(Yii::$app->user, 'identity.role') == 'teacher') {
            return $query->byTeacher();
        }
        
        return $query;
    }
    
    public function afterFind()
    {
        $this->students_total_quantity = $this->students_start_quantity + $this->students_now_quantity;
        $this->active_structure = $this->active_structure ?: [];
        return parent::afterFind();
    }
    
    public function beforeSave($insert)
    {
        $this->passing_percent = $this->type == 'exam' ? 0 : $this->passing_percent;
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changed_attributes)
    {
        if ($insert && $this->scenario == 'default') {
            $course_unit = new CourseUnit();
            $course_unit->scenario = 'update_section';
            $course_unit->tree = $this->id;
            $course_unit->name = 'Root';
            $course_unit->makeRoot();
        }
        
        return parent::afterSave($insert, $changed_attributes);
    }
}
