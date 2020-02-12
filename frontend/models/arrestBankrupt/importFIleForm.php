<?php
namespace frontend\models;

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
        $where = ['or'];

        $inputFileType = \PHPExcel_IOFactory::identify($this->fileImport->tempName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($this->fileImport->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $baseRow = 3;
        $loadCount = 0;
        while(!empty($sheetData[$baseRow]['B'])){
            if (!LotsAll::find()->joinWith('torg')->where(['lotNumber'=>(int)$sheetData[$baseRow]['A'], 'torg.publisherId' => Yii::$app->user->id])->one()) {
                $lot = new Lots();
                $torg = new Torgs();
                $torg->typeId     = 3;
                $lot->msgId         = (string)$sheetData[$baseRow]['A'];
                $torg->msgId        = (string)$sheetData[$baseRow]['A'];
                $info = [];

                $lot->status              = 'Опубликован';
                $lot->lotNumber           = (int)$sheetData[$baseRow]['A'];
                $lot->title               = GetInfoFor::mb_ucfirst(GetInfoFor::title((string)$sheetData[$baseRow]['B']));
                $lot->description         = (string)$sheetData[$baseRow]['C'];
                $torg->publishedDate      = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['D']), 'php:Y-m-d H:i:s');
                $torg->startDate          = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['E']), 'php:Y-m-d H:i:s');
                $torg->endDate            = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['F']), 'php:Y-m-d H:i:s');
                $torg->completeDate       = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['G']), 'php:Y-m-d H:i:s');
                $lot->startPrice          = floatval(str_replace(' ', '',$sheetData[$baseRow]['H']));
                $lot->step                = floatval(str_replace(' ', '',$sheetData[$baseRow]['I']));
                $info['stepCount']        = (int)$sheetData[$baseRow]['J'];
                $address = GetInfoFor::address((string)$location['address']);

                $info['address']          = $address['address'];
                $lot->regionId            = $address['regionId'];
                $lot->city                = $address['address']['city'];
                $lot->district            = $address['address']['district'];
                $torg->tradeTypeId        = ((string)$sheetData[$baseRow]['N'] == 'Аукцион')? 2 : 1;
                $info['procedureDate']    = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['O']), 'php:Y-m-d H:i:s');
                $info['conclusionDate']   = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['P']), 'php:Y-m-d H:i:s');
                $info['viewInfo']         = (string)$sheetData[$baseRow]['Q'];
                $info['collateralPrice']  = floatval(str_replace(' ', '',$sheetData[$baseRow]['R']));
                $info['paymentDetails']   = (string)$sheetData[$baseRow]['S'];
                $info['currentPeriod']    = (string)$sheetData[$baseRow]['U'];
                $info['additionalConditions']  = (string)$sheetData[$baseRow]['T'];
                $info['basisBidding']     = (string)$sheetData[$baseRow]['V'];
                $info['dateAuction']      = (string)$sheetData[$baseRow]['W'];
                
                $lot->info = $info;
                $torg->publisherId         = Yii::$app->user->id;
                $torg->ownerId             = Yii::$app->user->identity->ownerId;

                $torg->save();

                $lot->torgId            = $torg->id;

                if (Yii::$app->params['exelParseResult'][$baseRow]['status'] = $lot->save()) {
                    $check = true;
                    $where[] = ['id' => $lot->id];
                    $loadCount++;
                } else {
                    Yii::$app->params['exelParseResult'][$baseRow]['info'] = $lot->errors;
                }
                
            }
            $baseRow++;
        }
        Yii::$app->getSession()->setFlash('success','Success');
        Yii::$app->params['exelParseResult'][$baseRow]['count'] = $loadCount;

        return ['check' => $check, 'loadCount' => $loadCount, 'where' => $where];
    }
}