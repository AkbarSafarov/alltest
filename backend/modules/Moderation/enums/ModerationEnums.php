<?php

namespace backend\modules\Moderation\enums;

use Yii;
use yii\helpers\ArrayHelper;


class ModerationEnums
{
    public static function modelClasses()
    {
        return [
            'CourseUnit' => [
                'label' => Yii::t('app', 'Юнит курса'),
            ],
            'LibraryTest' => [
                'label' => Yii::t('app', 'Тест'),
            ],
        ];
    }
    
    public static function statuses()
    {
        $user = Yii::$app->user->identity;
        
        $statuses = [
            'development' => [
                'label' => Yii::t('app', 'В разработке'),
                'single_label' => Yii::t('app', 'В разработке'),
                'action_label' => Yii::t('app', 'Вернуть в разработку'),
                'icon' => 'fas fa-tools',
                'css_class' => 'secondary',
                'can_change' => ['sent_for_moderation'],
            ],
            'sent_for_moderation' => [
                'label' => Yii::t('app', 'Отправленные на модерацию'),
                'single_label' => Yii::t('app', 'Отправлен на модерацию'),
                'action_label' => Yii::t('app', 'Отправить на модерацию'),
                'icon' => 'fas fa-file-export',
                'css_class' => 'warning',
                'can_change' => ['return_from_moderation', 'rejected', 'active'],
            ],
            'return_from_moderation' => [
                'label' => Yii::t('app', 'Вернуть с модерации'),
                'single_label' => Yii::t('app', 'Вернуть с модерации'),
                'action_label' => Yii::t('app', 'Вернуть с модерации'),
                'icon' => 'fas fa-file-import',
                'css_class' => 'info',
                'can_change' => [],
            ],
            'rejected' => [
                'label' => Yii::t('app', 'Возвращённые'),
                'single_label' => Yii::t('app', 'Возвращён'),
                'action_label' => Yii::t('app', 'Вернуть'),
                'icon' => 'fas fa-ban',
                'css_class' => 'danger',
                'can_change' => ['sent_for_moderation'],
            ],
            'active' => [
                'label' => Yii::t('app', 'Активные'),
                'single_label' => Yii::t('app', 'Активен'),
                'action_label' => Yii::t('app', 'Активировать'),
                'icon' => 'fas fa-check-square',
                'css_class' => 'blue',
                'can_change' => [],
            ],
        ];
        
        if ($user->service->checkByRole(['admin', 'moderator'])) {
            array_walk($statuses, function(&$value, $key) {
                if ($key != 'active') {
                    $value['can_change'][] = 'active';
                }
            });
        }
        
        switch ($user->role) {
            case 'moderator':
                array_walk($statuses, function(&$value, $key) {
                    $value['can_change'] = array_diff($value['can_change'], ['sent_for_moderation', 'return_from_moderation']);
                });
                break;
            case 'teacher':
                array_walk($statuses, function(&$value, $key) {
                    $value['can_change'] = array_diff($value['can_change'], ['rejected', 'active']);
                });
                break;
        }
        
        return $statuses;
    }
}
