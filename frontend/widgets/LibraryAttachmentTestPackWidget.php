<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\ArrayHelper;

use backend\modules\Library\models\LibraryTest;


class LibraryAttachmentTestPackWidget extends Widget
{
    public Model $model;
    public array $test_packs;
    
    public function run()
    {
        if ($this->test_packs) {
            foreach ($this->test_packs as $test_pack) {
                $tests[] = LibraryTest::find()
                    ->andFilterWhere([
                        'subject_id' => $test_pack->subject_id,
                        'category_id' => is_array($test_pack->category_ids) ? $test_pack->category_ids : json_decode($test_pack->category_ids),
                        'input_type' => is_array($test_pack->input_types) ? $test_pack->input_types : json_decode($test_pack->input_types),
                        'status' => 'active',
                    ])
                    ->limit($test_pack->quantity <= 100 ? $test_pack->quantity : 100)
                    ->orderBy('RAND()')
                    ->asObject()->all();
            }
            
            $tests = array_merge(...$tests);
            Yii::$app->session->set("unit_{$this->model->id}_test_pack", $tests);
            
            return $this->render('library_attachment_test', [
                'group' => 'test_pack',
                'model' => $this->model,
                'tests' => $tests,
            ]);
        }
    }
}
