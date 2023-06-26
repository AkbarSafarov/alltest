<?php

namespace backend\modules\Blog\query;

use Yii;
use yii\db\Expression;
use speedrunner\db\ActiveQuery;


class BlogQuery extends ActiveQuery
{
    public function published()
    {
        $date = date('Y-m-d H:i:s');
        
        return $this->andWhere(['<=', 'blog.published_at_from', $date])
            ->andWhere([
                'or',
                ['>=', 'blog.published_at_to', $date],
                ['IS', 'blog.published_at_to', null],
            ]);
    }
}