<?php
namespace console\models\managers;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Parser;

class ManagerArrest extends Module
{
    public function torg($torg)
    {
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.managers';
        $parser->tableNameFrom = 'bailiff.torgs';
        $parser->tableIdFrom = $torg->trgId;

        $chekManager = Managers::find()->where(['oldId' => $torg->trgOrganizationId, 'type' => 'organizer'])->all();
        if (!empty($chekManager[0])) {
            
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Организатора торгов (Менеджер) уже был добавлен ID ".$chekManager[0]->id.". \n";

            return $chekManager[0]->id;
        } else {
            if ($torg->trgOrganizationId != null || $torg->trgFullName != null || $torg->trgAddress != null || $torg->trgFullName != null || $torg->trgInn != null) {

                $newManager = new Managers();
        
                $address = GetInfoFor::address((($torg->trgLocation)? $torg->trgLocation : $torg->postaddress));
        
                $info = [
                    'address'           => $address['address'],
                    'organizationKind'  => $torg->trgBidOrgKind,
                    'headOrganization'  => $torg->trgHeadOrg,
                    'limitBidDeal'      => $torg->trgLimitBidDeal,
                    'kpp'               => $torg->trgKpp,
                    'okato'             => $torg->trgOkato,
                    'okpo'              => $torg->trgOkpo,
                    'okved'             => $torg->trgOkved,
                    'ogrn'              => $torg->trgOgrn,
                    'contacts'          => [
                        'address'   => $torg->trgAddress,
                        'location'  => $torg->trgLocation,
                        'phone'     => $torg->trgPhone,
                        'fax'       => $torg->trgFax,
                        'email'     => $torg->trgEmail,
                        'url'       => $torg->trgUrl,
                    ]
                ];
        
                $newManager->type           = 'organizer';
                $newManager->fullName       = GetInfoFor::mb_ucfirst($torg->trgFullName);
                $newManager->inn            = $torg->trgInn;
                $newManager->sroId          = null;
                $newManager->address        = $address['fullAddress'];
                $newManager->arbId          = $torg->trgOrganizationId;
                $newManager->info           = $info;
                $newManager->regionId       = $address['regionId'];
                $newManager->city           = $address['address']['city'];
                $newManager->district       = $address['address']['district'];
                $newManager->oldId          = $torg->trgOrganizationId;
        
                try {
                    $newManager->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();
        
                    echo "Успешно добавлена в таблицу Организатора торгов (Менеджеров) ID ".$newManager->id.", старый ID ".$torg->trgOrganizationId.". \n";
                    return $newManager->id;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Организатора торгов (Менеджеров) ID ".$torg->trgOrganizationId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Организатора торгов (Менеджеров) ID ".$torg->trgOrganizationId.". \n";
                return false;
            }
        }
        return false;
    }
}