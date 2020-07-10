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

use yii\helpers\Url;
use yii\helpers\FileHelper;
use common\models\db\Lot;
use common\models\db\Torg;
use console\traits\Keeper;
use sergmoro1\uploader\models\OneFile;
use sergmoro1\uploader\behaviors\ImageTransformationBehavior;

use common\models\db\LotPrice;

/**
 * Eidb controller
 */
class EidbController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => ImageTransformationBehavior::className()],
        ];
    }

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
        $this->actionLotImage($step);
        $this->actionLotDocumentDel();
        $this->actionLotDocument($step);
        $this->actionLotSearchIndex();
        echo "\nЗавершение работы! ------------\n";
    }
    // Полное удаление
    // php yii eidb/del
    public function actionDel($step = 100)
    {
        echo "\nЗапуск парсера! ---------------\n";
        $this->actionLotDocumentDel();
        $this->actionLotSearchIndexDel();
        $this->actionLotImageDel($step);
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
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT count("ofrRdnId") FROM "bailiff"."offerreductions" '
        );
        $dataCount = Lot::find()
                ->joinWith(['torg', 'prices'])
                ->where([
                    Torg::tableName().'.property' => Torg::PROPERTY_BANKRUPT, 
                    Torg::tableName().'.offer' => Torg::OFFER_PUBLIC,
                    LotPrice::tableName().'.price' => NULL,
                    ])->count();

        $limit  = $step;
        $offset = 0;
        $data = null;

        if ($dataCount) {
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
        }

        $select = $db->createCommand(
            'SELECT count(lot_id) FROM "eidb".lot_price'
        );
        $count = $select->queryAll();

        echo "\n\nЗаписей до ".$dataCount." после ".$count[0]['count'];
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
            $data = $this->insertImagePoole($limit, $offset);

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

    const TABLE = '{{%onefile}}';
    const OLD_TABLE = 'lots';
    const LIMIT = 1000;
    const SITE  = 'https://ei.ru';

    private $contextOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ];  

    public $sizes;

    public function insertImagePoole($limit, $offset)
    {
        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT id, images, "createdAt", "updatedAt" FROM "eiLot"."'.self::OLD_TABLE.'" WHERE images NOTNULL ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }
        
        $files = [];

        foreach($rows as $row)
        {
            $lot_id = $row['id'];

            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);

            $images = json_decode($row['images']);
            
            foreach($images as $image) {
                $full_file_name = $image->max;
                $full_file_name = (substr($image->max, 0, 4) == 'http')
                    ? $image->max
                    : self::SITE . '/' . $image->max;
                $a = explode('/', $image->max);
                $tmp_name = $a[count($a) - 1];
                $a = explode('.', $tmp_name);
                $ext = $a[count($a) - 1];
                $new_name = 'i' . uniqid() . '.' . $ext;

                $headers = get_headers($full_file_name, 0, stream_context_create($this->contextOptions));
                if (strpos($headers[0], '200')) {
                    $image = file_get_contents($full_file_name, false, stream_context_create($this->contextOptions));

                    // OneFile
                    $of = [
                        'model'      => Lot::className(),
                        'parent_id'  => $lot_id,
                        'original'   => $tmp_name,
                        'name'       => $new_name,
                        'subdir'     => $lot_id,
                        'type'       => ('image/' . $ext),
                        'size'       => strlen($image),
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $onefile = new OneFile($of);
                
                    Keeper::validateAndKeep($onefile, $files, $of);

                    $dir = Yii::getAlias('@absolute') . Yii::getAlias('@uploader') . '/lot/' . $lot_id;
                    if (!is_dir($dir))
                        mkdir($dir);
                    file_put_contents($dir . '/' . $tmp_name, $image);
                    
                    $lot = Lot::findOne($lot_id);
                    $this->sizes = $lot->sizes;
                    $this->resizeSave($lot->setFilePath($lot_id), $tmp_name, $new_name);
                }
            }
        }
        
        $result = [];

        $result['onefile'] = Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['model', 'parent_id', 'original', 'name', 'subdir', 'type', 'size', 'created_at', 'updated_at'], $files)->execute();
            
        return $result;
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

    // Индексация поиска лотов
    // php yii eidb/lot-search-index
    public function actionLotSearchIndex($addColumn = false)
    {
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            '
        DO
            $$BEGIN
        CREATE TEXT SEARCH DICTIONARY ispell_ru (
            template = ispell,
            dictfile = ru_ru,
            afffile = ru_ru,
            stopwords = russian
        );
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
           '
        )->execute();
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            '
        DO
        $$BEGIN
            CREATE TEXT SEARCH CONFIGURATION ru ( COPY = russian );
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
        '
        )->execute();
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            'ALTER TEXT SEARCH CONFIGURATION ru
           ALTER MAPPING
           FOR word, hword, hword_part
           WITH ispell_ru, russian_stem;
           '
        )->execute();
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand('SET default_text_search_config = \'ru\';')->execute();

        /** ADD tsvector column **/
        if ($addColumn) {
            $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
            $db->createCommand(
                '
            ALTER TABLE {{%lot}} ADD COLUMN fts tsvector;
            '
            )->execute();
        }
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            '
            UPDATE {{%lot}} SET fts=
                setweight( coalesce( to_tsvector(\'ru\', [[title]]),\'\'),\'A\') || \' \' ||
                setweight( coalesce( to_tsvector(\'ru\', [[description]]),\'\'),\'B\') || \' \';
        '
        )->execute();
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand('create index fts_index on {{%lot}} using gin (fts);')->execute();

        /**
         * ---   ADD AUTO FILL fts TRIGGER ON INSERT AND UPDATE NEW RECORD
         **/
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            '
        CREATE OR REPLACE FUNCTION fts_vector_update() RETURNS TRIGGER AS
        $$
        BEGIN
            NEW.fts = setweight(coalesce(to_tsvector(\'ru\', NEW.title), \'\'), \'A\') || \' \' ||
                      setweight(coalesce(to_tsvector(\'ru\', NEW.description), \'\'), \'B\') || \' \';
            RETURN NEW;
        END;
        $$ LANGUAGE \'plpgsql\';
        '
        )->execute();

        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            '
        DO
        $$BEGIN
            CREATE TRIGGER lot_fts_insert
                BEFORE INSERT
                ON eidb.lot
                FOR EACH ROW
            EXECUTE PROCEDURE fts_vector_update();
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
        '
        )->execute();

        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $db->createCommand(
            '
            DO
        $$BEGIN
            CREATE TRIGGER lot_fts_update
                BEFORE UPDATE
                ON eidb.lot
                FOR EACH ROW
            EXECUTE PROCEDURE fts_vector_update();
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
        '
        )->execute();
    }
    public function actionLotSearchIndxDel() {
        $db = \Yii::$app->db;
        $db->createCommand('DROP FUNCTION IF EXISTS fts_vector_update() CASCADE')->execute();
    }

    /**
     * php yii eidb/lot-document
     * 
     * Документов возможно перенести одним запросом, поэтому
     * миграция может выполняться только на сервере, когда и источник и цель в одной БД.
     */
    public function actionLotDocument() {
        $db = \Yii::$app->db;
        
        $command = $db->createCommand(
            'INSERT INTO {{%document}} (model, parent_id, name, ext, url, hash, created_at, updated_at) '.
            'SELECT 
                 CASE
                     WHEN "tableTypeId" = 1 THEN 6 
                     WHEN "tableTypeId" = 2 THEN 7
                     ELSE 4
                 END as model, 
                 CAST("tableId" AS INTEGER) as parent_id,
                 name, format as ext, url, hash,
                 CAST(EXTRACT(EPOCH FROM "createdAt") AS INTEGER) as cteated_at,
                 CAST(EXTRACT(EPOCH FROM "updatedAt") AS INTEGER) as updated_at
             FROM "eiLot".documents
             WHERE "tableId" NOTNULL'
        );
        print_r($command->execute());
    }

    public function actionLotDocumentDel() {
        $db = \Yii::$app->db;
        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE {{%document}}')->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else
            print_r($db->createCommand('TRUNCATE TABLE {{%document}} CASCADE')->execute());
    }
}

