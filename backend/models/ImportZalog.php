<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\LotsAll;
use common\models\Query\Lot\Torgs;

use console\models\GetInfoFor;

/**
 * Import Zalog
 */
class ImportZalog extends Model
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

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function xml()
    {
        $check = false;
        $where = ['or'];

        $xml = (array)simplexml_load_file($this->fileImport->tempName);

        $i = 0;
        foreach ($xml as $key => $then) {
            if ($key == 'generation-date') {
                $dateGenereate = $then;
            }
            if ($key == 'offer') {
                if ($then[0] != null) {
                    foreach ($then as $value) {
                        $lot = new Lots();
                        $torg = new Torgs();

                        $torg->typeId     = 3;
                        $torg->publisherId  = Yii::$app->user->id;
                        $torg->ownerId      = Yii::$app->user->identity->ownerId;

                        if (!LotsAll::find()->where(['msgId'=>(string)$value['internal-id']])->one()) {
                            $lot->lotNumber     = (string)$value['lot-number'];
                            $lot->msgId         = (string)$value['internal-id'];
                            $torg->msgId        = (string)$value['internal-id'];

                            $images = [];
                            if ($value->image) {
                                foreach ($value->image as $image) {
                                    $images[] = ['max' => (string)$image, 'min' => (string)$image];
                                }
                            }
                            $value = (array)$value;
                            $info = [];
                        
                            switch ($value['category']) {
                                case 'commercial':
                                    $info['category'] = 'Коммерческая';
                                    switch ($value['commercial-building-type']) {
                                        case 'business center':
                                            $info['category-building-type'] = 'Бизнес-центр';
                                            break;
                                        case 'detached building':
                                            $info['category-building-type'] = 'Отдельно стоящее здание';
                                            break;
                                        case 'residential building':
                                            $info['category-building-type'] = 'Встроенное помещение';
                                            break;
                                        case 'shopping center':
                                            $info['category-building-type'] = 'Торговый центр';
                                            break;
                                        case 'warehouse':
                                            $info['category-building-type'] = 'Складской комплекс';
                                        break;
                                    }
                                break;
                                case 'cottage':
                                    $info['category'] = 'Коттедж или дача';
                                break;
                                case 'house':
                                    $info['category'] = 'Дом';
                                break;
                                case 'house with lot':
                                    $info['category'] = 'Дом с участком';
                                break;
                                case 'lot':
                                    $info['category'] = 'Участок';
                                break;
                                case 'flat':
                                    $info['category'] = 'Квартира';
                                break;
                                case 'room':
                                    $info['category'] = 'Комната';
                                break;
                                case 'townhouse':
                                    $info['category'] = 'Таунхаус';
                                break;
                                case 'duplex':
                                    $info['category'] = 'Дуплекс';
                                break;
                                case 'garage':
                                    $info['category'] = 'Гараж';
                                    switch ($value['garage-type']) {
                                        case 'garage':
                                            $info['category-type'] = 'Гараж';
                                            break;
                                        case 'parking place':
                                            $info['category-type'] = 'Машиноместо';
                                            break;
                                        case 'box':
                                            $info['category-type'] = 'Бокс';
                                            break;
                                    }
                                break;
                                default:
                                    $info['category'] = 'Часть дома';
                                break;
                            }

                            foreach ($value as $type => $typeValue) {
                                if ($type == 'commercial-type') {
                                    switch ($typeValue) {
                                        case 'auto repair':
                                            $info['category-type'][] = 'Автосервис';
                                        break;
                                        case 'business':
                                            $info['category-type'][] = 'Готовый бизнес';
                                        break;
                                        case 'free purpose':
                                            $info['category-type'][] = 'Помещения свободного назначения';
                                        break;
                                        case 'hotel':
                                            $info['category-type'][] = 'Гостиница';
                                        break;
                                        case 'land':
                                            $info['category-type'][] = 'Земли коммерческого назначения';
                                        break;
                                        case 'legal address':
                                            $info['category-type'][] = 'Юридический адрес';
                                        break;
                                        case 'manufacturing':
                                            $info['category-type'][] = 'Производственное помещение';
                                        break;
                                        case 'office':
                                            $info['category-type'][] = 'Офисные помещения';
                                        break;
                                        case 'public catering':
                                            $info['category-type'][] = 'Общепит';
                                        break;
                                        case 'retail':
                                            $info['category-type'][] = 'Торговые помещения';
                                        break;
                                        case 'warehouse':
                                            $info['category-type'][] = 'Склад';
                                        break;
                                    }
                                }
                                if ($type == 'purpose') {
                                    switch ($typeValue) {
                                        case 'bank':
                                        $info['purpose'][] = 'Помещение для банка';
                                        break;
                                        case 'beauty shop':
                                        $info['purpose'][] = 'Салон красоты';
                                        break;
                                        case 'food store':
                                        $info['purpose'][] = 'Продуктовый магазин';
                                        break;
                                        case 'medical center':
                                        $info['purpose'][] = 'Медицинский центр';
                                        break;
                                        case 'show room':
                                        $info['purpose'][] = 'Шоу-рум';
                                        break;
                                        case 'touragency':
                                        $info['purpose'][] = 'Турагентство';
                                        break;
                                    }
                                }
                                if ($type == 'purpose-warehouse') {
                                    switch ($typeValue) {
                                        case 'alcohol':
                                        $info['purpose'][] = 'Алкогольный склад';
                                        break;
                                        case 'pharmaceutical storehouse':
                                        $info['purpose'][] = 'Фармацевтический склад';
                                        break;
                                        case 'vegetable storehouse':
                                        $info['purpose'][] = 'Овощехранилище';
                                        break;
                                    }
                                }
                            }
                        
                            $torg->tradeTypeId          = (((string)$value['type'] == 'продажа')? 1 : 2);
                            $info['url']                = (string)$value['url'];
                            $info['cadastreNumber']     = (string)$value['cadastral-number'];
                            $torg->publishedDate        = (string)($value['creation-date'])? $value['creation-date'] : $dateGenereate ;
                            $lot->updatedAt             = (string)$value['last-update-date'];
                            $lot->startPrice            = floatval($value['price']->value);


                            $location   = (array)$value['location'];
                            $address = GetInfoFor::address((string)$location['address']);

                            $info['address']        = $address['address'];
                            $lot->regionId          = $address['regionId'];
                            $lot->city              = $address['address']['city'];
                            $lot->district          = $address['address']['district'];

                            $info['sub-locality-name']  = (string)$location['sub-locality-name'];
                            $info['sub-locality-name']  = (string)$location['sub-locality-name'];
                            

                            $info['floor']              = (string)$value['floor'];
                            $info['built-year']         = (string)$value['built-year'];
                            $info['area']               = (string)$value['area']->value.' '.(string)$value['area']->unit;

                            switch ($value['deal-status']) {
                                case 'direct rent':
                                    $lot->status = 'Прямая аренда';
                                break;
                                case 'subrent':
                                    $lot->status = 'Субаренда';
                                break;
                                case 'sale of lease rights':
                                    $lot->status = 'Продажа права аренды';
                                break;
                                default:
                                    $lot->status = 'Продажа';
                                break;
                            }

                            $lot->info              = $info;
                            $lot->images            = $images;
                            $lot->description       = (string)$value['description'];
                            $lot->title             = GetInfoFor::mb_ucfirst(GetInfoFor::title((string)$value['description']));
                            
                            $torg->save();

                            $lot->torgId            = $torg->id;

                            if (!Yii::$app->params['exelParseResult'][$baseRow]['status'] = $lot->save()) {
                                Yii::$app->params['exelParseResult'][$baseRow]['info'] = $lot->errors;
                            } else {
                                $check = true;
                                $where[] = ['id' => $lot->id];
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        
        Yii::$app->params['exelParseResult'][$baseRow]['count'] = $i;

        return ['check' => $check, 'loadCount' => $i, 'where' => $where];

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
        Yii::$app->params['exelParseResult'][$baseRow]['count'] = $loadCount;

        return ['check' => $check, 'loadCount' => $loadCount, 'where' => $where];
    }
}