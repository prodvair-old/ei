<?php
namespace console\models\bankrupt;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Bankrupts;
use common\models\Query\Lot\Parser;

class BankruptsBankrupt extends Module
{
    public function id($id)
    {
        $bankrupt = \common\models\Query\Bankrupt\Bankrupts::findOne($id);
        $parser = new Parser();

        $chekBankrupt = Bankrupts::find()->where(['oldId' => $bankrupt->id])->all();

        if ($chekBankrupt[0]) {
            $parser->tableNameTo = 'eiLot.bankrupts';
            $parser->tableNameFrom = 'uds.obj$bankrupts';
            $parser->tableIdFrom = $bankrupt->id;
            $parser->message = 'Успешно добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Должник уже был добавлен ID ".$bankrupt->id.". \n";

            return true;
        } else {
            if ($bankrupt->bankrupttype != null && $bankrupt->bankruptcategory != null && $bankrupt->address != null && ($bankrupt->person->fullName != null && $bankrupt->person->inn != null) || ($bankrupt->company->inn != null && $bankrupt->company->shortname != null)) {
                $newBankrupt = new Bankrupts();
        
                $address = GetInfoFor::address($bankrupt->address);
                
        
                if ($bankrupt->bankrupttype == 'Person') {
                    $newBankrupt->name  = GetInfoFor::mb_ucfirst($bankrupt->person->fullName);
                    $newBankrupt->inn   = $bankrupt->person->inn;

                    $info = [
                        'lastName'      => GetInfoFor::mb_ucfirst($bankrupt->person->lname),
                        'firstName'     => GetInfoFor::mb_ucfirst($bankrupt->person->fname),
                        'middleName'    => GetInfoFor::mb_ucfirst($bankrupt->person->mname),
                        'snils'         => $bankrupt->person->snils,
                        'birthDay'      => $bankrupt->person->birthday,
                        'birthPlace'    => $bankrupt->person->birthplace,
                        'pol'           => ($bankrupt->person->sexid != NULL)? (($bankrupt->person->sexid == '1009')? 'Женщина' : 'Мужчина') : null,
                        'polId'         => ($bankrupt->person->sexid != NULL)? (($bankrupt->person->sexid == '1009')? 0 : 1) : null,
                    ];
                } else {
                    $newBankrupt->name  = GetInfoFor::mb_ucfirst($bankrupt->company->shortname);
                    $newBankrupt->inn   = $bankrupt->company->inn;

                    $info = [
                        'fullName'      => GetInfoFor::mb_ucfirst($bankrupt->company->fullname),
                        'okpo'          => $bankrupt->company->okpo,
                        'ogrn'          => $bankrupt->company->ogrn,
                        'legalAddress'  => $bankrupt->company->legaladdress,
                    ];
                }

                $info['address'] = $address['address'];
                
                $newBankrupt->type       = GetInfoFor::mb_lcfirst($bankrupt->bankrupttype);
                $newBankrupt->category   = GetInfoFor::mb_lcfirst($bankrupt->bankruptcategory);
                $newBankrupt->address    = $address['fullAddress'];
                $newBankrupt->info       = $info;
                $newBankrupt->regionId   = $address['regionId'];
                $newBankrupt->city       = $address['address']['city'];
                $newBankrupt->district   = $address['address']['district'];
                $newBankrupt->oldId      = $bankrupt->id;
        
                try {
                    $newBankrupt->save();
        
                    $parser->tableNameTo = 'eiLot.bankrupts';
                    $parser->tableNameFrom = 'uds.obj$bankrupts';
                    $parser->tableIdFrom = $bankrupt->id;
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 2;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Должников ID ".$newBankrupt->id.", старый ID ".$bankrupt->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->tableNameTo = 'eiLot.bankrupts';
                    $parser->tableNameFrom = 'uds.obj$srbankruptso';
                    $parser->tableIdFrom = $bankrupt->id;
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Должников ID ".$bankrupt->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->tableNameTo = 'eiLot.bankrupts';
                $parser->tableNameFrom = 'uds.obj$bankrupts';
                $parser->tableIdFrom = $bankrupt->id;
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Должника ID ".$bankrupt->id.". \n";
                return 2;
            }
        }
        return false;
    }
}