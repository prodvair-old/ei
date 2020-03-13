<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Query\Arrest\LotsArrest;

use console\models\GetInfoFor;

/**
 * import FIle Form
 */
class ImportFIleForm extends Model
{
    public $fileImport;
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['fileImport', 'required'],
            // ['fileImport', 'file','maxSize'=>1024*1024*20],
        ];
    }

    public function excelArrest()
    {
        $rowsData = explode("\r\n", str_replace('"', "'", $this->fileImport));
        foreach ($rowsData as $id => $rowData) {
            $colsData[$id] = explode("\t", $rowData);
        }
        $searchData = [];
        $i = 0;

        foreach ($colsData as $row => $value) {
            if ($row != 0 && !empty($value[3]) && !empty($value[2]) && !empty($value[1])) {
                $where = ['or'];

                $where[] = ['like', 'lotPropName', (string)$value[1]];
                
                if (strpos(mb_strtolower((string)$value[2],'UTF-8'), 'ип') !== false || strpos(mb_strtolower((string)$value[2],'UTF-8'), 'ооо') !== false || strpos(mb_strtolower((string)$value[2],'UTF-8'), 'оао') !== false) {
                    $where[] = ['like', 'lotPropName', (string)$value[2]];
                } else {
                    $names = explode(' ', (string)$value[2]);
                    $where[] = ['like', 'lotPropName', $names[0]];
                    $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'.'.mb_substr($names[2],0,1,'UTF-8').'.'];
                    $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'. '.mb_substr($names[2],0,1,'UTF-8').'.'];
                    $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').' '.mb_substr($names[2],0,1,'UTF-8')];
                    $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').mb_substr($names[2],0,1,'UTF-8')];
                }

                $where[] = ['like', 'lotPropName', (string)$value[3]];

                $lots = LotsArrest::find()->joinWith('torgs')->where($where)->orderBy('trgPublished ASC')->all();
                
                foreach ($lots as $key => $lot) {
                    $result[] = [
                        'inn'   => (string)$value[1],
                        'name'  => (string)$value[2],
                        'id'            => $lot->lotId,
                        'title'         => $lot->lotPropName,
                        'torg'          => 'http://torgi.gov.ru',
                        'repeat'        => (($key != 0)? 'Да' : 'Нет' ),
                        'publication'   => $lot->torgs->trgPublished,
                        'auction'       => $lot->torgs->trgBidAuctionDate,
                        'form'          => $lot->torgs->trgBidFormName,
                        'winner'        => $lot->lotWinnerName,
                        'price'         => $lot->lotStartPrice,
                        'url'           => $lot->torgs->trgNotificationUrl,
                    ];
                }
                
            }
        }
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";

    //     $check = false;

    //     $inputFileType = \PHPExcel_IOFactory::identify($this->fileImport->tempName);
    //     $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    //     $objPHPExcel = $objReader->load($this->fileImport->tempName);
    //     $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

    //     $baseRow = 2;

    //     $result = ['or'];

    //     while (!empty($sheetData[$baseRow]['B'])) {
    //         $where = ['or'];

    //         $where[] = ['like', 'lotPropName', (string)$sheetData[$baseRow]['B']];
            
    //         if (strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'ип') !== false || strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'ооо') !== false || strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'оао') !== false) {
    //             $where[] = ['like', 'lotPropName', (string)$sheetData[$baseRow]['C']];
    //         } else {
    //             $names = explode(' ', (string)$sheetData[$baseRow]['C']);
    //             $where[] = ['like', 'lotPropName', $names[0]];
    //             $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'.'.mb_substr($names[2],0,1,'UTF-8').'.'];
    //             $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'. '.mb_substr($names[2],0,1,'UTF-8').'.'];
    //             $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').' '.mb_substr($names[2],0,1,'UTF-8')];
    //             $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').mb_substr($names[2],0,1,'UTF-8')];
    //         }

    //         $where[] = 'to_tsvector("lotPropName") @@ plainto_tsquery(\''.(string)$sheetData[$baseRow]['D'].'\')';

    //         $result[] = $where;
    //         $baseRow++;
    //     }

        return $result;
    }
}