<?php

namespace backend\modules\Integration\services\export;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use backend\modules\User\search\UserSearch;
use backend\modules\User\search\UserSubscriberSearch;


class UserXlsService
{
    private $user_search_params;
    
    public function __construct($user_search_params)
    {
        $this->user_search_params = $user_search_params;
    }
    
    public function process()
    {
        array_walk_recursive($this->user_search_params, fn (&$v) => $v = trim($v));
        
        switch ($this->user_search_params['model'] ?? null) {
            case 'user':
                $user_search_model = new UserSearch();
                break;
            case 'user_subscriber':
                $user_search_model = new UserSubscriberSearch();
                break;
            default:
                Yii::$app->session->addFlash('danger', Yii::t('app', 'Неверные данные'));
                return false;
        }
        
        $user_search_model->load([$user_search_model->formName() => $this->user_search_params]);
        $dataProvider = $user_search_model->search();
        
        $total_count = $dataProvider->totalCount;
        $limit = 10000;
        
        if (!$total_count || $total_count > $limit) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'Пользователей должно быть не менее {min} и не более {max}', [
                'min' => 1,
                'max' => $limit,
            ]));
            
            return false;
        }
        
        return $this->export($user_search_model, $dataProvider->query->asObject()->all());
    }
    
    private function export($user_search_model, $users)
    {
        $max_row = count($users) + 1;
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:H$max_row")->getAlignment()->setVertical('center');
        $sheet->getStyle("A1:H$max_row")->getFont()->setSize(12);
        $sheet->getStyle("A1:H$max_row")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A1:H$max_row")->getNumberFormat()->setFormatCode('@');
        
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $sheet->freezePane('A2');
        $sheet->setCellValue('A1', $user_search_model->getAttributeLabel('full_name'));
        $sheet->setCellValue('B1', $user_search_model->getAttributeLabel('username'));
        $sheet->setCellValue('C1', $user_search_model->getAttributeLabel('phone'));
        $sheet->setCellValue('D1', $user_search_model->getAttributeLabel('parent_phone'));
        $sheet->setCellValue('E1', $user_search_model->getAttributeLabel('address'));
        $sheet->setCellValue('F1', $user_search_model->getAttributeLabel('last_activity'));
        $sheet->setCellValue('G1', Yii::t('app', 'Приобретённые курсы'));
        $sheet->setCellValue('H1', Yii::t('app', 'Подписан на курсы'));
        
        foreach ($users as $key => $user) {
            $index = $key + 2;
            
            $all_courses = ArrayHelper::index(
                $user->courses,
                fn($value) => json_decode($value->course_json)->name,
                fn($value) => $value->demo_datetime_to ? 1 : 0,
            );
            
            $sheet->setCellValue("A$index", $user->profile->full_name);
            $sheet->setCellValue("B$index", $user->username);
            $sheet->setCellValue("C$index", $user->profile->phone ? '+' . $user->profile->phone . ' ' : null);
            $sheet->setCellValue("D$index", $user->profile->parent_phone ? '+' . $user->profile->parent_phone . ' ' : null);
            $sheet->setCellValue("E$index", $user->profile->address);
            $sheet->setCellValue("F$index", date('d.m.Y H:i', strtotime($user->last_activity)));
            $sheet->setCellValue("G$index", implode("\n", array_keys($all_courses[0] ?? [])));
            $sheet->setCellValue("H$index", implode("\n", array_keys($all_courses[1] ?? [])));
        }
        
        $doc_name = Html::encode(Yii::t('app', 'Пользователи'));
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$doc_name.xlsx");
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        
        die;
    }
}
