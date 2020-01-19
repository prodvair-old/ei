<?php
namespace console\models\cases;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Cases;
use common\models\Query\Lot\Parser;

class BankruptsBankrupt extends Module
{
    public function id($id)
    {
        $case = \common\models\Query\Bankrupt\Bankrupts::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.cases';
        $parser->tableNameFrom = 'uds.obj$cases';
        $parser->tableIdFrom = $case->id;

        $chekBankrupt = Bankrupts::find()->where(['oldId' => $case->id])->all();

        if ($chekBankrupt[0]) {
            $parser->message = 'Успешно добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Должник уже был добавлен ID ".$case->id.". \n";

            return true;
        } else {
            if ($case->casetype != null && $case->casecategory != null && $case->address != null && ($case->person->fullName != null && $case->person->inn != null) || ($case->company->inn != null && $case->company->shortname != null)) {
                $newBankrupt = new Bankrupts();
        
                $address = GetInfoFor::address($case->address);
                
        
                if ($case->casetype == 'Person') {
                    $newBankrupt->name  = GetInfoFor::mb_ucfirst($case->person->fullName);
                    $newBankrupt->inn   = $case->person->inn;

                    $info = [
                        'lastName'      => GetInfoFor::mb_ucfirst($case->person->lname),
                        'firstName'     => GetInfoFor::mb_ucfirst($case->person->fname),
                        'middleName'    => GetInfoFor::mb_ucfirst($case->person->mname),
                        'snils'         => $case->person->snils,
                        'birthDay'      => $case->person->birthday,
                        'birthPlace'    => $case->person->birthplace,
                        'pol'           => ($case->person->sexid != NULL)? (($case->person->sexid == '1009')? 'Женщина' : 'Мужчина') : null,
                        'polId'         => ($case->person->sexid != NULL)? (($case->person->sexid == '1009')? 0 : 1) : null,
                    ];
                } else {
                    $newBankrupt->name  = GetInfoFor::mb_ucfirst($case->company->shortname);
                    $newBankrupt->inn   = $case->company->inn;

                    $info = [
                        'fullName'      => GetInfoFor::mb_ucfirst($case->company->fullname),
                        'okpo'          => $case->company->okpo,
                        'ogrn'          => $case->company->ogrn,
                        'legalAddress'  => $case->company->legaladdress,
                    ];
                }

                $info['address'] = $address['address'];
                
                $newBankrupt->type       = GetInfoFor::mb_lcfirst($case->casetype);
                $newBankrupt->category   = GetInfoFor::mb_lcfirst($case->casecategory);
                $newBankrupt->address    = $address['fullAddress'];
                $newBankrupt->info       = $info;
                $newBankrupt->regionId   = $address['regionId'];
                $newBankrupt->city       = $address['address']['city'];
                $newBankrupt->district   = $address['address']['district'];
                $newBankrupt->oldId      = $case->id;
        
                try {
                    $newBankrupt->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 2;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Должников ID ".$newBankrupt->id.", старый ID ".$case->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Должников ID ".$case->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Должника ID ".$case->id.". \n";
                return 2;
            }
        }
        return false;
    }
}