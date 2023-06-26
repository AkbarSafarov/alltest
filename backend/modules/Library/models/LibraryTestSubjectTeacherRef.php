<?php

namespace backend\modules\Library\models;

use Yii;
use speedrunner\db\ActiveRecord;


class LibraryTestSubjectTeacherRef extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%library_test_subject_teacher_ref}}';
    }
}
