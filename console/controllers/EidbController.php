<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\eidb\SroFill;
use console\models\eidb\ManagerFill;
use console\models\eidb\BankruptFill;
use console\models\eidb\EtpFill;
use console\models\eidb\OwnerFill;
use console\models\eidb\CasefileFill;
use console\models\eidb\TorgFill;
use console\models\eidb\LotFill;
use console\models\eidb\LotStatusFill;
use console\models\eidb\LotCategoryFill;
use console\models\eidb\LotPriceFill;
use console\models\eidb\LotImageFill;

/**
 * Eidb controller
 */
class EidbController extends Controller
{
    // Полный парсинг
    // php yii eidb
    public function actionIndex($step = 100)
    {
        echo "\nЗапуск парсера! ---------------\n";
        $this->actionSro($step);
        $this->actionManager($step);
        $this->actionBankrupt($step);
        $this->actionEtp($step);
        $this->actionOwner($step);
        $this->actionCasefile($step);
        $this->actionTorg($step);
        $this->actionLot($step);
        $this->actionLotCategory($step);
        $this->actionLotPrice($step);
        echo "\nЗавершение работы! ------------\n";
    }
    // Полное удаление
    // php yii eidb/del
    public function actionDel($step = 100)
    {
        echo "\nЗапуск парсера! ---------------\n";
        $this->actionLotPriceDel($step);
        $this->actionLotCategoryDel($step);
        $this->actionLotDel($step);
        $this->actionTorgDel($step);
        $this->actionCasefileDel($step);
        $this->actionOwnerDel($step);
        $this->actionEtpDel($step);
        $this->actionBankruptDel($step);
        $this->actionManagerDel($step);
        $this->actionSroDel($step);
        echo "\nЗавершение работы! ------------\n";
    }


    // СРО
    // php yii eidb/sro
    public function actionSro($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.sro'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".manager' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data   = SroFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n sro            := ".$data['sro'];
                echo "\n organization   := ".$data['organization'];
                echo "\n place          := ".$data['place'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".sro'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // СРО - удаление
    // php yii eidb/sro-del
    public function actionSroDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.sro'\n";

        $data = SroFill::remove();

        echo "\n sro            := ".$data['sro'];
        echo "\n organization   := ".$data['organization'];
        echo "\n place          := ".$data['place'];

        echo "\n\nЗавершено.\n";
    }


    // Менеджеры
    // php yii eidb/manager
    public function actionManager($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.manager'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
       
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".manager' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = ManagerFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n manager        := ".$data['manager'];
                echo "\n manager_sro    := ".$data['manager_sro'];
                echo "\n profile        := ".$data['profile'];
                echo "\n organization   := ".$data['organization'];
                echo "\n place          := ".$data['place'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".manager'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Менеджеры - удаление
    // php yii eidb/manager-del
    public function actionManagerDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.manager'\n";

        $data = ManagerFill::remove();

        echo "\n manager        := ".$data['manager'];
        echo "\n manager_sro    := ".$data['manager_sro'];
        echo "\n profile        := ".$data['profile'];
        echo "\n organization   := ".$data['organization'];
        echo "\n place          := ".$data['place'];

        echo "\n\nЗавершено.\n";
    }


    // Должники
    // php yii eidb/bankrupt
    public function actionBankrupt($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.bankrupt'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
       
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".bankrupt' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = BankruptFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n bankrupt       := ".$data['bankrupt'];
                echo "\n profile        := ".$data['profile'];
                echo "\n organization   := ".$data['organization'];
                echo "\n place          := ".$data['place'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".bankrupt'
        );
        $count = $select->queryAll();
        
        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Должники - удаление
    // php yii eidb/bankrupt-del
    public function actionBankruptDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.bankrupt'\n";

        $data = BankruptFill::remove();

        echo "\n bankrupt       := ".$data['bankrupt'];
        echo "\n profile        := ".$data['profile'];
        echo "\n organization   := ".$data['organization'];
        echo "\n place          := ".$data['place'];

        echo "\n\nЗавершено.\n";
    }
    

    // Торговые площадки
    // php yii eidb/etp
    public function actionEtp($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.etp'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".etp' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = EtpFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n etp            := ".$data['etp'];
                echo "\n organization   := ".$data['organization'];
                echo "\n place          := ".$data['place'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".etp'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Торговые площадки - удаление
    // php yii eidb/etp-del
    public function actionEtpDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.etp'\n";

        $data = EtpFill::remove();

        echo "\n etp            := ".$data['etp'];
        echo "\n organization   := ".$data['organization'];
        echo "\n place          := ".$data['place'];

        echo "\n\nЗавершено.\n";
    }
        

    // Владельцы
    // php yii eidb/owner
    public function actionOwner($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.owner'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".owner' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {

            $data = OwnerFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n owner          := ".$data['owner'];
                echo "\n organization   := ".$data['organization'];
                echo "\n place          := ".$data['place'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".owner'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Владельцы - удаление
    // php yii eidb/owner-del
    public function actionOwnerDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.owner'\n";

        $data = OwnerFill::remove();

        echo "\n owner          := ".$data['owner'];
        echo "\n organization   := ".$data['organization'];
        echo "\n place          := ".$data['place'];

        echo "\n\nЗавершено.\n";
    }
            

    // Дело по лоту
    // php yii eidb/casefile
    public function actionCasefile($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.casefile'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".casefile' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = CasefileFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n casefile       := ".$data['casefile'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".casefile'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\nЗавершено.\n";
    }

    // Дело по лоту - удаление
    // php yii eidb/casefile-del
    public function actionCasefileDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.casefile'\n";

        $data = CasefileFill::remove();

        echo "\n casefile       := ".$data['casefile'];

        echo "\n\nЗавершено.\n";
    }
        

    // Торг
    // php yii eidb/torg
    public function actionTorg($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.torg'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".torg' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];
        
        $data = null;

        while ($data !== false) {
            $data = TorgFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n torg           := ".$data['torg'];
                echo "\n torg_debtor    := ".$data['torg_debtor'];
                echo "\n torg_pledge    := ".$data['torg_pledge'];
                echo "\n torg_drawish   := ".$data['torg_drawish'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".torg'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Торг - удаление
    // php yii eidb/torg-del
    public function actionTorgDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.torg'\n";

        $data = TorgFill::remove();

        echo "\n torg           := ".$data['torg'];
        echo "\n torg_debtor    := ".$data['torg_debtor'];
        echo "\n torg_pledge    := ".$data['torg_pledge'];
        echo "\n torg_drawish   := ".$data['torg_drawish'];

        echo "\n\nЗавершено.\n";
    }
       

    // Лот
    // php yii eidb/lot
    public function actionLot($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.lot'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".lot' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = LotFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n lot            := ".$data['lot'];
                echo "\n place          := ".$data['place'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".lot'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Лот - удаление
    // php yii eidb/lot-del
    public function actionLotDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.lot'\n";

        $data = LotFill::remove();

        echo "\n lot            := ".$data['lot'];
        echo "\n place          := ".$data['place'];

        echo "\n\nЗавершено.\n";
    }

    // Лот Статус
    // php yii eidb/lot-status
    public function actionLotStatus($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.lot' - Статусы\n";
        echo "\nШаг := ".$step."\n";
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        // var_dump($db);
        $select = $db->createCommand(
            'SELECT count("pheLotId") FROM "bailiff".purchaselots WHERE "pheLotIsNotUpdated" = 0'  
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = 0;
        $data = null;

        if ($dataCount[0]['count']) {
            while ($data !== false) {
                $data = LotStatusFill::getData($limit, $offset);

                if ($data !== false) {
                    echo "\n\n------------------------";
                    echo "\n lot-update     := ".$data['lotsCount'];
            } else {
                    echo "\n\nКонец даннх";
                }

                $offset = $offset + $step;
            }
        }

        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT count("pheLotId") FROM "bailiff".purchaselots WHERE "pheLotIsNotUpdated" = 0'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }


    // Категории лотов
    // php yii eidb/lot-category
    public function actionLotCategory($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.lot_category'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(lot_id) FROM "eidb".lot_category' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = LotCategoryFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n lot_category   := ".$data['lot_category'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(lot_id) FROM "eidb".lot_category'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // Категории лотов - удаление
    // php yii eidb/lot-category-del
    public function actionLotCategoryDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.lot_category'\n";

        $data = LotCategoryFill::remove();

        echo "\n lot_category   := ".$data['lot_category'];

        echo "\n\nЗавершено.\n";
    }


    // История снижения цены лотов
    // php yii eidb/lot-price
    public function actionLotPrice($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.lot_price'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(lot_id) FROM "eidb".lot_price' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = LotPriceFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n lot_price      := ".$data['lot_price'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(lot_id) FROM "eidb".lot_price'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // История снижения цены лотов - удаление
    // php yii eidb/lot-price-del
    public function actionLotPriceDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.lot_price'\n";

        $data = LotPriceFill::remove();

        echo "\n lot_price      := ".$data['lot_price'];

        echo "\n\nЗавершено.\n";
    }
    

    // История снижения цены лотов
    // php yii eidb/lot-image
    public function actionLotImage($step = 100) 
    {
        echo "Начало парсинга таблицы: 'eidb.onefile'\n";
        echo "\nШаг := ".$step."\n";
        $db     = \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".onefile' 
        );
        $dataCount = $select->queryAll();

        $limit  = $step;
        $offset = $dataCount[0]['count'];

        $data = null;

        while ($data !== false) {
            $data = LotImageFill::getData($limit, $offset);

            if ($data !== false) {
                echo "\n\n------------------------";
                echo "\n onefile        := ".$data['onefile'];
            } else {
                echo "\n\nКонец даннх";
            }

            $offset = $offset + $step;
        }

        $select = $db->createCommand(
            'SELECT count(id) FROM "eidb".onefile'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount[0]['count']." после ".$count[0]['count'];
        echo "\n\nЗавершено.\n";
    }

    // История снижения цены лотов - удаление
    // php yii eidb/lot-image-del
    public function actionLotImageDel() 
    {
        echo "Начало удаление данных таблицы: 'eidb.onefile'\n";

        $data = LotImageFill::remove();

        echo "\n onefile        := ".$data['onefile'];

        echo "\n\nЗавершено.\n";
    }
}

