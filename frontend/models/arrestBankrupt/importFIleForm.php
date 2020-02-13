<?php
namespace frontend\models\arrestBankrupt;

use Yii;
use yii\base\Model;
use common\models\Query\Arrest\LotsArrest;

use console\models\GetInfoFor;

/**
 * import FIle Form
 */
class importFIleForm extends Model
{
    public $fileImport;
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['fileImport', 'required'],
            ['fileImport', 'file', 'extensions'=>'xls,xlsx,xml', 'maxSize'=>1024*1024*20],
        ];
    }

    public function excel()
    {
        $check = false;

        $inputFileType = \PHPExcel_IOFactory::identify($this->fileImport->tempName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($this->fileImport->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $baseRow = 2;

        $result = [];

        $where = ['or'];

        while(!empty($sheetData[$baseRow]['B'])){

            $where[] = ['like', 'lotPropName', (string)$sheetData[$baseRow]['B']];

            // $names = explode(' ', (string)$sheetData[$baseRow]['C']);
            // $where[] = ['like', 'lotPropName', $names[0]];
            // //     foreach ($names as $name) {
            // //     $where[] = ['like', 'lotPropName', $name];
            // // }

            $where[] = ['lotPropName' => (string)$sheetData[$baseRow]['D']];


            // foreach ($lots as $lot) {
            //     $result[] = $lot;
            // }

            $baseRow++;
        }

        return $where;
    }
}