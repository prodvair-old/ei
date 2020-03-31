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
                
                $orWhere = ['or'];
                if (strpos(mb_strtolower((string)$value[2],'UTF-8'), 'ип') !== false || strpos(mb_strtolower((string)$value[2],'UTF-8'), 'ооо') !== false || strpos(mb_strtolower((string)$value[2],'UTF-8'), 'оао') !== false) {
                    $orWhere[] = ['like', 'lotPropName', (string)$value[2]];
                } else {
                    $names = explode(' ', (string)$value[2]);
                    $orWhere[] = ['like', 'lotPropName', $names[0]];
                    $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'.'.mb_substr($names[2],0,1,'UTF-8').'.'];
                    $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').'. '.mb_substr($names[2],0,1,'UTF-8').'.'];
                    $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').' '.mb_substr($names[2],0,1,'UTF-8')];
                    $orWhere[] = ['like', 'lotPropName', $names[0].' '.mb_substr($names[1],0,1,'UTF-8').mb_substr($names[2],0,1,'UTF-8')];
                }
                $where[] = $orWhere;

                foreach (explode(',',(string)$value[3]) as $cad_vin) {
                    $where[] = ['like', 'lotPropName', $cad_vin];
                }

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

        return $result;
    }
}