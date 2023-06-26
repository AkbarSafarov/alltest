<?php

namespace backend\modules\Library\query;

use Yii;
use yii\helpers\StringHelper;
use speedrunner\db\ActiveQuery;


class LibraryTestSubjectQuery extends ActiveQuery
{
    public function itemsList($attr, $type, $show_deleted = false, $q = null, $limit = 20)
    {
        if ($show_deleted) {
            $this->where(true);
        }
        
        $this->select(['library_test_subject.id', "library_test_subject.$attr as text"])
            ->andFilterWhere(['like', "library_test_subject.$attr", $q]);
        
        switch ($type) {
            case 'by_user':
                if (Yii::$app->user->identity->role == 'teacher') {
                    $this->joinWith(['teachers'], false)->andFilterWhere(['user.id' => Yii::$app->user->id]);
                }
                
                break;
        }
        
        return $this->limit($limit);
    }
}