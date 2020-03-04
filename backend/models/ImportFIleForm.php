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
            ['fileImport', 'file','maxSize'=>1024*1024*20],
        ];
    }

    public function excelArrest()
    {
        $check = false;

        $inputFileType = \PHPExcel_IOFactory::identify($this->fileImport->tempName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($this->fileImport->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $baseRow = 21;

        $result = ['or'];

        while(!empty($sheetData[$baseRow]['B']) || $sheetData[$baseRow]['B'] != ' '){
            $where = ['or'];

            $where[] = ['like', 'lotPropName', (string)$sheetData[$baseRow]['B']];
            
            
            if (strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'ип') !== false || strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'ооо') !== false || strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'оао') !== false) {
                $where[] = ['like', 'lotPropName', (string)$sheetData[$baseRow]['C']];
            } else {
                $names = explode(' ', (string)$sheetData[$baseRow]['C']);
                $where[] = ['like', 'lotPropName', $names[0]];
                $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'.'.mb_substr($names[2],0,1,'UTF-8').'.'];
                $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'. '.mb_substr($names[2],0,1,'UTF-8').'.'];
                $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').' '.mb_substr($names[2],0,1,'UTF-8')];
                $where[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').mb_substr($names[2],0,1,'UTF-8')];
            }

            $where[] = 'to_tsvector("lotPropName") @@ plainto_tsquery(\''.(string)$sheetData[$baseRow]['D'].'\')';

            $result[] = $where;
            $baseRow++;
        }

        return $result;
    }
}