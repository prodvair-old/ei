<?php
namespace console\models\torgs;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\managers\ArbitrsBankrupt;
use console\models\etp\EtpBankrupt;
use console\models\bankrupt\BankruptsBankrupt;
use console\models\cases\CasesBankrupt;

use common\models\ErrorSend;

use common\models\Query\Bankrupt\Auction;

use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Etp;
use common\models\Query\Lot\Cases;
use common\models\Query\Lot\Bankrupts;
use common\models\Query\Lot\Owners;

use common\models\Query\Lot\Parser;

class TorgsBankrupt extends Module
{
    public function id($id)
    {
        $torg = Auction::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.torgs';
        $parser->tableNameFrom = 'uds.obj$auctions';
        $parser->tableIdFrom = $torg->id;

        $chekTorg = Torgs::find()->where(['oldId' => $torg->id, 'typeId' => 1])->all();
        if (!empty($chekTorg[0])) {

            $parser->message = 'Был добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Торга уже был добавлен ID ".$torg->id.". \n";

            return true;
        } else {
            if ($torg->description != null && $torg->tradetype != null) {

                // Арбитражный управляющий
                if (!$manager = Managers::find()->where(['oldId' => $torg->case->arbitr->id])->one()) {
                    echo "Арбитражниый управляющии для связи отцуствует! \nПробуем спарсить данный Арбитражного управляющего. \n";

                    $parsingManager = ArbitrsBankrupt::id($torg->case->arbitr->id);

                    if (!$parsingManager && $parsingManager !== 2){
                        
                        $parser->message = 'Арбитражниый управляющии для связи отсуствует!';
                        $parser->messageJson = [
                            'oldManagerId' => $torg->case->arbitr->id,
                        ];
                        $parser->statusId = 2;
            
                        $parser->save();

                        echo "Отсутствует Арбитражный управляющии...\n";
                    } else {
                        $manager = Managers::find()->where(['oldId' => $torg->case->arbitr->id])->one();
                    }
                }

                // Торговая площадка
                if (!$etp = Etp::find()->where(['oldId' => $torg->etp->id])->one()) {
                    echo "Торговая площадка для связи отцуствует! \nПробуем спарсить данный Торговой площадки. \n";

                    $parsingEtp = EtpBankrupt::id($torg->etp->id);

                    if (!$parsingEtp && $parsingEtp !== 2){
                        
                        $parser->message = 'Торговая площадка для связи отсуствует!';
                        $parser->messageJson = [
                            'oldEtpId' => $torg->etp->id,
                        ];
                        $parser->statusId = 2;
            
                        $parser->save();

                        echo "Отсутствует Торговая площадка...\n";
                    } else {
                        $etp = Etp::find()->where(['oldId' => $torg->etp->id])->one();
                    }
                }

                // Должник
                if (!$bankrupt = Bankrupts::find()->where(['oldId' => $torg->case->bnkr->id])->one()) {
                    echo "Должник для связи отцуствует! \nПробуем спарсить данный Должника. \n";

                    $parsingBankrupt = BankruptsBankrupt::id($torg->case->bnkr->id);

                    if (!$parsingBankrupt && $parsingBankrupt !== 2){
                        
                        $parser->message = 'Должник для связи отсуствует!';
                        $parser->messageJson = [
                            'oldBankruptId' => $torg->case->bnkr->id,
                            'oldBankruptPersonId' => $torg->case->bnkr->person->id,
                            'oldBankruptCompanyId' => $torg->case->bnkr->company->id,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Торгов ID ".$torg->id.". \nОтсутствует Должник...\n";
                        return false;
                    } else {
                        $bankrupt = Bankrupts::find()->where(['oldId' => $torg->case->bnkr->id])->one();
                    }
                }

                // Дело по должнику
                if (!$case = Cases::find()->where(['oldId' => $torg->case->id])->one()) {
                    echo "Дело для связи отцуствует! \nПробуем спарсить данный Дел по должнику. \n";

                    $parsingCase = CasesBankrupt::id($torg->case->id);

                    if (!$parsingCase && $parsingCase !== 2){
                        
                        $parser->message = 'Дело по должнику для связи отсуствует!';
                        $parser->messageJson = [
                            'oldCaseId' => $torg->case->id,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Торгов ID ".$torg->id.". \nОтсутствует Дело по должнику...\n";
                        return false;
                    } else {
                        $case = Cases::find()->where(['oldId' => $torg->case->id])->one();
                    }
                }

                $newTorg = new Torgs();
        
                $info = [
                    'priceType'     => $torg->pricetype,
                    'rules'         => $torg->rules,
                    'reasonChange'  => $torg->reasonchange,
                    'reasonTime'    => $torg->reasontime,
                    'tradeSite'     => $torg->tradesite,
                    'tradeSiteId'   => $torg->idtradeplace,
                ];
        
                $newTorg->typeId        = 1;
                $newTorg->publisherId   = $manager->id;
                $newTorg->etpId         = $etp->id;
                $newTorg->bankruptId    = $bankrupt->id;
                $newTorg->caseId        = $case->id;
                $newTorg->msgId         = $torg->msgid;
                $newTorg->description   = GetInfoFor::mb_ucfirst($torg->description);
                $newTorg->startDate     = GetInfoFor::date_check($torg->timebegin);
                $newTorg->endDate       = GetInfoFor::date_check($torg->timeend, 3);
                $newTorg->completeDate  = GetInfoFor::date_check($torg->timeend);
                $newTorg->publishedDate = GetInfoFor::date_check($torg->timepublication);
                $newTorg->tradeTypeId   = ($torg->tradetype == 'PublicOffer')? 1 : 2;
                $newTorg->info          = $info;
                $newTorg->oldId         = $torg->id;
        
                try {
                    $newTorg->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();
        
                    echo "Успешно добавлена в таблицу Торгов ID ".$newTorg->id.", старый ID ".$torg->id.". \n";
                    return [true, $newTorg->id];

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Торгов ID ".$torg->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Торгов ID ".$torg->id.". \n";
                return 2;
            }
        }
        return false;
    }
}