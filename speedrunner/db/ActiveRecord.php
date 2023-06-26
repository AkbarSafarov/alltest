<?php

namespace speedrunner\db;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

use backend\modules\Seo\services\SeoMetaService;
use backend\modules\Trash\models\Trash;


class ActiveRecord extends \yii\db\ActiveRecord
{
    public $enums = null;
    public $searchModel = null;
    public $service = null;
    
    public function init()
    {
        //        Setting enums
        
        $enums_class_name = str_replace('\models\\', '\enums\\', get_called_class()) . 'Enums';
        
        if ($this->enums === null) {
            $this->enums = class_exists($enums_class_name) ? new $enums_class_name($this) : null;
        }
        
        //        Setting search model
        
        $search_model_class_name = str_replace('\models\\', '\search\\', get_called_class()) . 'Search';
        
        if ($this->searchModel === null) {
            $this->searchModel = class_exists($search_model_class_name) ? new $search_model_class_name($this) : null;
        }
        
        //        Setting service
        
        $service_class_name = str_replace('\models\\', '\services\\', get_called_class()) . 'Service';
        
        if ($this->service === null) {
            $this->service = class_exists($service_class_name) ? new $service_class_name($this) : null;
        }
        
        return parent::init();
    }
    
    //        Applying API fields
    
    public function fields()
    {
        $class_name = StringHelper::basename(get_called_class());
        
        $module_name = str_replace('backend\modules\\', null, get_called_class());
        $module_name = str_replace("\models\\$class_name", null, $module_name);
        
        $api_module_name = 'v1';
        $api_class_name = "api\modules\\$api_module_name\models\\$module_name\\$class_name";
        
        return class_exists($api_class_name) ? (new $api_class_name())->fields() : parent::fields();
    }
    
    //        Seo meta registration
    
    public function registerSeoMeta($group = 'page')
    {
        $seo_meta_service = new SeoMetaService($this);
        $seo_meta = $seo_meta_service->getMetaValue();
        
        Yii::$app->view->params['seo_meta'][$group] = [
            'head' => ArrayHelper::getValue($seo_meta, 'head'),
            'body' => [
                'top' => ArrayHelper::getValue($seo_meta, 'body_top'),
                'bottom' => ArrayHelper::getValue($seo_meta, 'body_bottom'),
            ],
        ];
    }
    
    //        Setting query
    
    public static function find()
    {
        $query_class_name = str_replace('\models\\', '\query\\', get_called_class()) . 'Query';
        
        if (class_exists($query_class_name)) {
            $query = Yii::createObject($query_class_name, [get_called_class()]);
        } else {
            $query = Yii::createObject(ActiveQuery::className(), [get_called_class()]);
        }
        
        if (StringHelper::basename(get_called_class()) != 'Trash') {
            $query->byState(false);
        }
        
        return $query;
    }
    
    protected function createRelationQuery($class, $link, $multiple)
    {
        return parent::createRelationQuery($class, $link, $multiple)->where(true);
    }
    
    public function afterFind()
    {
        //        Changing dates format
        
        foreach (Yii::$app->params['date_formats'] as $key => $d_f) {
            foreach ($d_f['attributes'] as $a) {
                if (array_key_exists($a, $this->attributes)) {
                    $this->{$a} = $this->{$a} ? date($d_f['formats']['afterFind'], strtotime($this->{$a})) : null;
                }
            }
        }
        
        return parent::afterFind();
    }
    
    public function beforeSave($insert)
    {
        //        Changing dates format
        
        foreach (Yii::$app->params['date_formats'] as $key => $d_f) {
            foreach ($d_f['attributes'] as $a) {
                if (array_key_exists($a, $this->attributes)) {
                    $this->{$a} = $this->{$a} ? date($d_f['formats']['beforeSave'], strtotime($this->{$a})) : null;
                }
            }
        }
        
        //        Setting values to default attributes
        
        if (array_key_exists('created_at', $this->attributes)) {
            $this->created_at = $this->created_at ?? date('Y-m-d H:i:s');
        }
        
        if (array_key_exists('updated_at', $this->attributes)) {
            $this->updated_at = date('Y-m-d H:i:s');
        }
        
        //        HTML purifier
        
        if (!in_array(StringHelper::basename(get_called_class()), ['SeoMeta'])) {
            foreach ($this->dirtyAttributes as $attribute => $value) {
                if ($value) {
                    if (is_array($value)) {
                        array_walk_recursive($value, function(&$v, $k) {
                            $v = Yii::$app->services->html->purify($v);
                        });
                        
                        $this->{$attribute} = $value;
                    } else {
                        $this->{$attribute} = Yii::$app->services->html->purify($value);
                    }
                }
            }
        }
        
        return parent::beforeSave($insert);
    }
    
    protected function deleteInternal()
    {
        if (!$this->beforeDelete()) {
            return false;
        }
        
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        
        if ($lock !== null) {
            $condition[$lock] = $this->$lock;
        }
        
        $model_class = StringHelper::basename($this->className());
        $non_trash_models = [
            'LibraryAttachmentTemplate',
            'LibraryAttachmentTest',
            'LibraryAttachmentTestPack',
            'Trash',
            'UserCart',
            'UserCartProduct',
        ];
        
        if (in_array($model_class, $non_trash_models)) {
            $result = static::deleteAll($condition);
        } else {
            $trash_model = Trash::find()->andWhere(['trash_model_class' => $model_class, 'trash_model_id' => $this->id])->one();
            
            if ($trash_model) {
                $result = $trash_model->delete();
            } else {
                $result = (new Trash(['trash_model_class' => $model_class, 'trash_model_id' => $this->id]))->save();
            }
        }
        
        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being deleted is outdated.');
        }
        
        $this->setOldAttributes(null);
        $this->afterDelete();
        
        return $result;
    }
    
    public function beforeSearch()
    {
        //        Changing dates format
        
        foreach (Yii::$app->params['date_formats'] as $key => $d_f) {
            foreach ($d_f['attributes'] as $a) {
                if (array_key_exists($a, $this->attributes)) {
                    $this->{$a} = $this->{$a} ? date($d_f['formats']['beforeSearch'], strtotime($this->{$a})) : null;
                }
            }
        }
    }
    
    public function afterSearch()
    {
        //        Changing dates format
        
        foreach (Yii::$app->params['date_formats'] as $key => $d_f) {
            foreach ($d_f['attributes'] as $a) {
                if (array_key_exists($a, $this->attributes)) {
                    $this->{$a} = $this->{$a} ? date($d_f['formats']['afterSearch'], strtotime($this->{$a})) : null;
                }
            }
        }
    }
}
