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
            ['fileImport', 'file', 'maxSize'=>1024*1024*20],
        ];
    }

    public function excelArrest()
    {
        $inputFileType = \PHPExcel_IOFactory::identify($this->fileImport->tempName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($this->fileImport->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $baseRow = 2;
        $loadCount = 0;

        while(!empty($sheetData[$baseRow]['B']) || $sheetData[$baseRow]['B'] != null){
            $whereCad   = ['or'];
            $whereF     = ['and'];

            if ($cad = $this->getCadastre((string)$sheetData[$baseRow]['D'])) {
                $orWhereCad = ['or'];

                if (count($cad) > 1) {
                    foreach ($cad as $value) {
                        $orWhereCad[] = ['like', 'lotPropName', $value];
                    }
                    $whereF[] = $whereSecond = $orWhereCad;
                } else {
                    $whereF[] = $whereSecond = ['like', 'lotPropName', $cad[0]];
                }
            }
            if ($vin = $this->getVin((string)$sheetData[$baseRow]['D'])) {
                $whereVin = ['like', 'lotPropName', $vin];
                $whereF[] = $whereSecond = $whereVin;
            }

            // ----------------------------
            $whereS     = ['or'];
            $whereS[]   = ['like', 'lotPropName', (string)$sheetData[$baseRow]['B']];
            
            $orWhere = ['or'];
            if (strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'ооо') !== false || strpos(mb_strtolower((string)$sheetData[$baseRow]['C'],'UTF-8'), 'оао') !== false) {
                $orWhere[] = ['like', 'lotPropName', (string)$sheetData[$baseRow]['C']];
            } else {
                $fio = str_replace(['ИП ', 'ип ','ИП', 'ип'], ['', '', '', ''], (string)$sheetData[$baseRow]['C']);
                $names = explode(' ',  ltrim($fio));
                $orWhere[] = ['like', 'lotPropName', $names[0]];

                $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'.'.mb_substr($names[2],0,1,'UTF-8').'.'];
                $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'. '.mb_substr($names[2],0,1,'UTF-8').'.'];
                $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').' '.mb_substr($names[2],0,1,'UTF-8')];
                $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').mb_substr($names[2],0,1,'UTF-8')];
                
                $orWhere[] = ['like', 'lotPropName', mb_substr($names[1],0,1,'UTF-8').'.'.mb_substr($names[2],0,1,'UTF-8').'. '.$names[0]];
                $orWhere[] = ['like', 'lotPropName', mb_substr($names[1],0,1,'UTF-8').'. '.mb_substr($names[2],0,1,'UTF-8').'. '.$names[0]];
                $orWhere[] = ['like', 'lotPropName', mb_substr($names[1],0,1,'UTF-8').' '.mb_substr($names[2],0,1,'UTF-8').' '.$names[0]];
                $orWhere[] = ['like', 'lotPropName', mb_substr($names[1],0,1,'UTF-8').mb_substr($names[2],0,1,'UTF-8').' '.$names[0]];
            }
            $whereF[] = $whereS[] = $orWhere;
            // ----------------------------

            $whereSecond[]  = $whereS;
            $whereFirst   = $whereF;

            $lotsFirst = LotsArrest::find()->joinWith('torgs')->where($whereFirst)->orderBy('trgPublished ASC')->limit(30)->all();
            $lotsSecond = LotsArrest::find()->joinWith('torgs')->where($whereSecond)->orderBy('trgPublished ASC')->limit(30)->all();
            
            foreach ($lotsFirst as $key => $lot) {
                $result['first'][] = [
                    'inn'   => (string)$sheetData[$baseRow]['B'],
                    'name'  => (string)$sheetData[$baseRow]['C'],
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
            foreach ($lotsSecond as $key => $lot) {
                $result['second'][] = [
                    'inn'   => (string)$sheetData[$baseRow]['B'],
                    'name'  => (string)$sheetData[$baseRow]['C'],
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
            
            $baseRow++;
        }
        return $result;
    }
    public function getCadastre($text) 
    {
        $kadastr_check = preg_match_all("/[0-9]{2}:[0-9]{2}:[0-9]{6,7}:[0-9]{1,35}/", $text, $kadastr);
        return ($kadastr_check)? $kadastr[0] : false;
    }
    public function getVin($text) 
    {
        $vin_text = str_replace(['WIN', 'VIN', 'ВИН', 'win', 'vin', 'вин'], ['', '', '', '', '', ''],$text);
        $vin_check = preg_match("/[A-HJ-NPR-Z0-9]{17}/", $vin_text, $vin);
        return ($vin_check)? $vin[0] : false;
    }
}