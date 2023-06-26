<?php

namespace backend\modules\User\services;

use Yii;
use yii\helpers\ArrayHelper;
use speedrunner\services\ActiveService;


class UserCourseUnitService extends ActiveService
{
    public function setCurrent()
    {
        $this->model->updateAll(['is_current' => 0], ['tree' => $this->model->tree]);
        
        $this->model->updateAll(['is_current' => 1], [
            'and',
            ['tree' => $this->model->tree],
            ['<=', 'lft', $this->model->lft],
            ['>=', 'rgt', $this->model->rgt],
        ]);
        
        Yii::$app->session->remove("unit_{$this->model->id}_is_submitted");
    }
    
    public function unlockParents()
    {
        $this->model->updateAll(['is_unlocked' => 1], [
            'and',
            ['tree' => $this->model->tree],
            ['<=', 'lft', $this->model->lft],
            ['>=', 'rgt', $this->model->rgt],
        ]);
    }
    
    public function unlockNextUnit()
    {
        $this->model->updateAttributes(['is_passed' => 1]);
        
        $next_unit = $this->model->find()
            ->andWhere([
                'tree' => $this->model->tree,
                'depth' => 3,
            ])
            ->andWhere(['>', 'lft', $this->model->lft])
            ->orderBy('lft')
            ->one();
        
        if ($next_unit) {
            $next_unit->updateAttributes(['is_unlocked' => 1]);
            $next_unit->service->unlockParents();
            
            return ['view', 'course_id' => $next_unit->tree, 'id' => $next_unit->id];
        } else {
            if ($this->model->course->course_json['type'] == 'linear') {
                $this->model->course->service->assignCertificate();
                Yii::$app->session->addFlash('success', Yii::t('app_notification', 'Вы успешно окончили курс'));
            }
            
            return ['user/course/index'];
        }
    }
    
    public function prev()
    {
        return $this->model->find()
            ->andWhere([
                'and',
                ['tree' => $this->model->tree],
                ['depth' => $this->model->depth],
                ['<', 'lft', $this->model->lft],
            ])
            ->orderBy('lft DESC')
            ->one();
    }
    
    public function next()
    {
        return $this->model->find()
            ->andWhere([
                'and',
                ['tree' => $this->model->tree],
                ['depth' => $this->model->depth],
                ['>', 'lft', $this->model->lft],
            ])
            ->orderBy('lft')
            ->one();
    }
}