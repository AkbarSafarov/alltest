<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use speedrunner\actions as Actions;
use yii\helpers\ArrayHelper;

use backend\modules\Course\models\Course;
use backend\modules\Course\models\CoursePackage;
use backend\modules\Course\models\CourseUnitType;
use backend\modules\Library\models\LibraryTemplateCategory;
use backend\modules\Order\models\OrderDiscount;
use backend\modules\Order\models\OrderPromocode;
use backend\modules\Library\models\LibraryTestCategory;
use backend\modules\Library\models\LibraryTestSubject;
use backend\modules\User\models\User;


class ItemsListController extends Controller
{
    public function actions()
    {
        return [
            'courses' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => Course::className(),
                'attribute' => 'name',
                'type' => Yii::$app->request->get('type', 'all'),
            ],
            'course-packages' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => CoursePackage::className(),
                'attribute' => 'name',
                'type' => Yii::$app->request->get('type', 'all'),
            ],
            'courses-unit-types' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => CourseUnitType::className(),
                'attribute' => 'name',
                'type' => 'translation',
            ],
            'library-template-categories' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => LibraryTemplateCategory::className(),
                'attribute' => 'name',
                'type' => 'translation',
            ],
            'library-test-categories' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => LibraryTestCategory::className(),
                'attribute' => 'name',
                'type' => 'self',
                'filter' => [
                    'library_test_category.subject_id' => Yii::$app->request->get('subject_id'),
                    'library_test_category.creator_id' => Yii::$app->request->get('creator_id'),
                ],
            ],
            'library-test-subjects' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => LibraryTestSubject::className(),
                'attribute' => 'name',
                'type' => Yii::$app->request->get('type', 'by_user'),
            ],
            'order-discounts' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => OrderDiscount::className(),
                'attribute' => 'name',
                'type' => 'translation',
            ],
            'order-promocodes' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => OrderPromocode::className(),
                'attribute' => 'key',
                'type' => 'self',
            ],
            'users' => [
                'class' => Actions\web\ItemsListAction::className(),
                'model_class' => User::className(),
                'attribute' => 'username',
                'type' => 'self',
                'filter' => ['user.role' => Yii::$app->request->get('role')],
            ],
        ];
    }
}
