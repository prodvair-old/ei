<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\db\Messages;
use common\models\db\Log;
use common\models\db\Casefile;
use common\models\db\Document;
use common\models\db\Category;
use common\models\db\LotCategory;
use common\models\db\Lot;
use common\models\db\TorgDebtor;
use common\models\db\Torg;
use common\models\db\Etp;
use common\models\db\Bankrupt;
use common\models\db\Sro;
use common\models\db\Manager;
use common\models\db\ManagerSro;
use common\models\db\Organization;
use common\models\db\Profile;
use common\models\db\Place;
use console\traits\Keeper;
use console\traits\XmlToArray;
use console\traits\District;
use console\models\GetInfoFor;

use moonland\phpexcel\Excel;

/**
 * Новые поля для таблиц
 * 
 * Profile - "snils" varchar(11) null
 * Profile - "orgn_ip" varchar(15) null
 * Profile - "birthplace" text null
 * Bankrupt - "bankrupt_id"  bigint null
 * Torg - "is_repeat"  smallint null = 1,0
 * Torg - "price_type"  smallint null = 1,0
 * Torg - "additional_text"  text null
 * Torg - "rules"  text null
 * 
 * ORGANIZATION_ACTIVITY:
 * 17 - Company = Юридическое лицо
 * 18 - ArbitrManagerSro = СРО АУ
 * 19 - FirmTradeOrganizer = Компания организатора торгов
 * 20 - CentralBankRf = Центральный Банк РФ
 * 21 - Asv = Агенств по страхованию вкладов
 * 22 - FnsDepartment = ФНС
 * 23 - ЕФРСБ = ЕФРСБ
 * 24 - МФС = МФС
 * 
 * PERSON_ACTIVITY
 * 25 - Person = Физическое лицо
 * 26 - PersonTradeOrganizer = Организатор торгов
 */

/**
 * Получение и парсинг данных из ЕФРСБ
 * EFRSB Controller
 * 
 * @var object client
 * @var integer messageIds
 * @var datetime dateNow
 * @var integer msgId
 * @var integer caseId
 * @var integer sroId
 * @var integer managerId
 * @var integer etpId
 * @var integer torgId
 * @var integer lotId
 * 
 * @var array DISTRICT
 * @var array TYPE
 * @var array BANKRUPT_CATEGORY
 * @var array OFFER
 * @var array PRICE_TYPE
 * @var array MEASURE
 * @var array CATEGORY_CODE
 */
class EfrsbController extends Controller
{
  use XmlToArray;

  public $lockHandle;
  
  private $client;
  private $messageIds;
  private $dateNow;

  
  private $msgId;
  private $caseId;
  private $sroId;
  private $managerId;
  private $bankruptId;
  private $etpId;
  private $torgId;
  private $lotId;

  CONST DISTRICT = [
    'Центральный'       => 1,
    'Северо-Западный'   => 2,
    'Южный'             => 3,
    'Северо-Кавказский' => 4,
    'Приволжский'       => 5,
    'Уральский'         => 6,
    'Сибирский'         => 7,
    'Дальневосточный'   => 8,    
  ];
  CONST TYPE = [
    'ArbitralDecree' => [
      'id'      => 1,
      'element' => 'CourtDecision'
    ],
    'Auction' => [
      'id'      => 2,
      'element' => 'Auction'
    ],
    'Meeting' => [
      'id'      => 3,
      'element' => 'Meeting'
    ],
    'MeetingResult' => [
      'id'      => 4,
      'element' => 'MeetingResult'
    ],
    'TradeResult' => [
      'id'      => 5,
      'element' => 'TradeResult'
    ],
    'Other' => [
      'id'      => 6,
      'element' => 'Other'
    ],
    'AppointAdministration' => [
      'id'      => 7,
      'element' => 'AppointAdministration'
    ],
    'ChangeAdministration' => [
      'id'      => 8,
      'element' => 'ChangeAdministration'
    ],
    'TerminationAdministration' => [
      'id'      => 9,
      'element' => 'TerminationAdministration'
    ],
    'BeginExecutoryProcess' => [
      'id'      => 10,
      'element' => 'BeginExecutoryProcess'
    ],
    'TransferAssertsForImplementation' => [
      'id'      => 11,
      'element' => 'TransferAssertsForImplementation'
    ],
    'Annul' => [
      'id'      => 12,
      'element' => 'Annul'
    ],
    'PropertyInventoryResult' => [
      'id'      => 13,
      'element' => 'PropertyInventoryResult'
    ],
    'PropertyEvaluationReport' => [
      'id'      => 14,
      'element' => 'PropertyEvaluationReport'
    ],
    'AssessmentReport' => [
      'id'      => 15,
      'element' => 'AssessmentReport'
    ],
    'SaleContractResult' => [
      'id'      => 16,
      'element' => 'SaleContractResult'
    ],
    'SaleContractResult2' => [
      'id'      => 17,
      'element' => 'SaleContractResult2'
    ],
    'Committee' => [
      'id'      => 18,
      'element' => 'Committee'
    ],
    'CommitteeResult' => [
      'id'      => 19,
      'element' => 'Other'
    ],
    'SaleOrderPledgedProperty' => [
      'id'      => 20,
      'element' => 'SaleOrderPledgedProperty'
    ],
    'ReceivingCreditorDemand' => [
      'id'      => 21,
      'element' => 'ReceivingCreditorDemand>'
    ],
    'DemandAnnouncement' => [
      'id'      => 22,
      'element' => 'Other'
    ],
    'CourtAssertAcceptance' => [
      'id'      => 23,
      'element' => 'Other'
    ],
    'FinancialStateInformation' => [
      'id'      => 24,
      'element' => 'Other'
    ],
    'BankPayment' => [
      'id'      => 25,
      'element' => 'Other'
    ],
    'AssetsReturning' => [
      'id'      => 26,
      'element' => 'Other'
    ],
    'CourtAcceptanceStatement' => [
      'id'      => 27,
      'element' => 'Other'
    ],
    'DeliberateBankruptcy' => [
      'id'      => 28,
      'element' => 'DeliberateBankruptcy'
    ],
    'IntentionCreditOrg' => [
      'id'      => 29,
      'element' => 'IntentionCreditOrg'
    ],
    'LiabilitiesCreditOrg' => [
      'id'      => 30,
      'element' => 'LiabilitiesCreditOrg'
    ],
    'PerformanceCreditOrg' => [
      'id'      => 31,
      'element' => 'PerformanceCreditOrg'
    ],
    'BuyingProperty' => [
      'id'      => 32,
      'element' => 'BuyingProperty'
    ],
    'DeclarationPersonDamages' => [
      'id'      => 33,
      'element' => 'DeclarationPersonDamages'
    ],
    'ActPersonDamages' => [
      'id'      => 34,
      'element' => 'ActPersonDamages'
    ],
    'ActReviewPersonDamages' => [
      'id'      => 35,
      'element' => 'ActReviewPersonDamages'
    ],
    'DealInvalid' => [
      'id'      => 36,
      'element' => 'DealInvalid'
    ],
    'ActDealInvalid' => [
      'id'      => 37,
      'element' => 'ActDealInvalid'
    ],
    'ActDealInvalid2' => [
      'id'      => 38,
      'element' => 'ActDealInvalid2'
    ],
    'ActReviewDealInvalid' => [
      'id'      => 39,
      'element' => 'ActReviewDealInvalid'
    ],
    'ActReviewDealInvalid2' => [
      'id'      => 40,
      'element' => 'ActReviewDealInvalid2'
    ],
    'DeclarationPersonSubsidiary' => [
      'id'      => 41,
      'element' => 'DeclarationPersonSubsidiary'
    ],
    'ActPersonSubsidiary' => [
      'id'      => 42,
      'element' => 'ActPersonSubsidiary'
    ],
    'ActPersonSubsidiary2' => [
      'id'      => 43,
      'element' => 'ActPersonSubsidiary2'
    ],
    'ActReviewPersonSubsidiary' => [
      'id'      => 44,
      'element' => 'ActReviewPersonSubsidiary'
    ],
    'ActReviewPersonSubsidiary' => [
      'id'      => 45,
      'element' => 'ActReviewPersonSubsidiary2'
    ],
    'MeetingWorker' => [
      'id'      => 46,
      'element' => 'MeetingWorker'
    ],
    'MeetingWorkerResult' => [
      'id'      => 47,
      'element' => 'MeetingWorkerResult'
    ],
    'ViewDraftRestructuringPlan' => [
      'id'      => 48,
      'element' => 'ViewDraftRestructuringPlan'
    ],
    'ViewExecRestructuringPlan' => [
      'id'      => 49,
      'element' => 'ViewExecRestructuringPlan'
    ],
    'TransferOwnershipRealEstate' => [
      'id'      => 50,
      'element' => 'TransferOwnershipRealEstate'
    ],
    'CancelAuctionTradeResult' => [
      'id'      => 51,
      'element' => 'CancelAuctionTradeResult'
    ],
    'CancelDeliberateBankruptcy' => [
      'id'      => 52,
      'element' => 'CancelDeliberateBankruptcy'
    ],
    'ChangeAuction' => [
      'id'      => 53,
      'element' => 'ChangeAuction'
    ],
    'ChangeDeliberateBankruptcy' => [
      'id'      => 54,
      'element' => 'ChangeDeliberateBankruptcy'
    ],
    'ReducingSizeShareCapital' => [
      'id'      => 55,
      'element' => 'ReducingSizeShareCapital'
    ],
    'SelectionPurchaserAssets' => [
      'id'      => 56,
      'element' => 'SelectionPurchaserAssets'
    ],
    'EstimatesCurrentExpenses' => [
      'id'      => 57,
      'element' => 'EstimatesCurrentExpenses'
    ],
    'OrderAndTimingCalculations' => [
      'id'      => 58,
      'element' => 'OrderAndTimingCalculations'
    ],
    'InformationAboutBankruptcy' => [
      'id'      => 59,
      'element' => 'InformationAboutBankruptcy'
    ],
    'EstimatesAndUnsoldAssets' => [
      'id'      => 60,
      'element' => 'EstimatesAndUnsoldAssets'
    ],
    'RemainingAssetsAndRight' => [
      'id'      => 61,
      'element' => 'RemainingAssetsAndRight'
    ],
    'ImpendingTransferAssets' => [
      'id'      => 62,
      'element' => 'ImpendingTransferAssets'
    ],
    'TransferAssets' => [
      'id'      => 63,
      'element' => 'TransferAssets'
    ],
    'TransferInsurancePortfolio' => [
      'id'      => 64,
      'element' => 'TransferInsurancePortfolio'
    ],
    'BankOpenAccountDebtor' => [
      'id'      => 65,
      'element' => 'BankOpenAccountDebtor'
    ],
    'ProcedureGrantingIndemnity' => [
      'id'      => 66,
      'element' => 'ProcedureGrantingIndemnity'
    ],
    'RightUnsoldAsset' => [
      'id'      => 67,
      'element' => 'RightUnsoldAsset'
    ],
    'TransferResponsibilitiesFund' => [
      'id'      => 68,
      'element' => 'TransferResponsibilitiesFund'
    ],
    'ExtensionAdministration' => [
      'id'      => 69,
      'element' => 'ExtensionAdministration'
    ],
    'MeetingParticipantsBuilding' => [
      'id'      => 70,
      'element' => 'MeetingParticipantsBuilding'
    ],
    'MeetingPartBuildResult' => [
      'id'      => 71,
      'element' => 'MeetingPartBuildResult'
    ],
    'PartBuildMonetaryClaim' => [
      'id'      => 72,
      'element' => 'PartBuildMonetaryClaim'
    ],
    'StartSettlement' => [
      'id'      => 73,
      'element' => 'StartSettlement'
    ],
    'ProcessInventoryDebtor' => [
      'id'      => 74,
      'element' => 'ProcessInventoryDebtor'
    ],
    'Rebuttal' => [
      'id'      => 75,
      'element' => 'Rebuttal'
    ],
    'CreditorChoiceRightSubsidiary' => [
      'id'      => 76,
      'element' => 'CreditorChoiceRightSubsidiary'
    ],
    'AccessionDeclarationSubsidiary' => [
      'id'      => 77,
      'element' => 'AccessionDeclarationSubsidiary'
    ],
    'DisqualificationArbitrationManager' => [
      'id'      => 78,
      'element' => 'DisqualificationArbitrationManager'
    ],
    'DisqualificationArbitrationManager2' => [
      'id'      => 79,
      'element' => 'DisqualificationArbitrationManager2'
    ],
    'ChangeEstimatesCurrentExpenses' => [
      'id'      => 80,
      'element' => 'ChangeEstimatesCurrentExpenses'
    ],
    'ReturnOfApplicationOnExtrajudicialBankruptcy' => [
      'id'      => 81,
      'element' => 'ReturnOfApplicationOnExtrajudicialBankruptcy'
    ],
    'StartOfExtrajudicialBankruptcy' => [
      'id'      => 82,
      'element' => 'StartOfExtrajudicialBankruptcy'
    ],
    'TerminationOfExtrajudicialBankruptcy' => [
      'id'      => 83,
      'element' => 'TerminationOfExtrajudicialBankruptcy'
    ],
    'CompletionOfExtrajudicialBankruptcy' => [
      'id'      => 84,
      'element' => 'CompletionOfExtrajudicialBankruptcy'
    ],
  ];
  CONST BANKRUPT_CATEGORY = [
    'AbsentBankrupt'                => 1,
    'AgricultureOrganization'       => 2,
    'CityOrganization'              => 3,
    'CreditOrganization'            => 4,
    'DevelopmentOrganization'       => 5,
    'DissolvedBankruptOrganization' => 6,
    'InsuranceOrganization'         => 7,
    'MonopolyOrganization'          => 8,
    'OtherOrganization'             => 9,
    'PrivatePensionFund'            => 10,
    'SimpleOrganization'            => 11,
    'StrategicOrganization'         => 12,
    'EnterpreneurPerson'            => 13,
    'FarmerPerson'                  => 14,
    'SimplePerson'                  => 15,
  ];
  CONST OFFER = [
    'OpenedAuction'     => 2,
    'ClosedAuction'     => 2,
    'OpenedConcours'    => 3,
    'ClosedConcours'    => 3,
    'PublicOffer'       => 1,
    'ClosePublicOffer'  => 1,
  ];
  CONST PRICE_TYPE = [
    'Public'  => Torg::PRICE_TYPE_PUBLIC,
    'Private' => Torg::PRICE_TYPE_PRIVATE,
  ];
  CONST MEASURE = [
    'Percent'   => Lot::MEASURE_PERCENT,
    'Currency'  => Lot::MEASURE_SUM,
  ];
  CONST CATEGORY_CODE = [
    "99"      => 11063,
    "0402006" => 11072,
    "0202002" => 11067,
    "0301"    => 11069,
    "0101006" => 11070,
    "0102"    => 11076,
    "0101004" => 11089,
    "0101011" => 11098,
    "0105009" => 11099,
    "0110008" => 11108,
    "0104005" => 11112,
    "0105008" => 11119,
    "0105001" => 11129,
    "0101001" => 11131,
    "0110010" => 11132,
    "0105003" => 11134,
    "0105005" => 11138,
    "0104025" => 11149,
    "0110006" => 11151,
    "0105006" => 11190,
    "0104023" => 11065,
    "0104012" => 11066,
    "0105002" => 11071,
    "0104022" => 11083,
    "0110001" => 11093,
    "0104001" => 11094,
    "0104013" => 11095,
    "0104018" => 11096,
    "0104016" => 11100,
    "0104008" => 11101,
    "0104015" => 11105,
    "0104014" => 11106,
    "0104003" => 11111,
    "0104026" => 11113,
    "0104007" => 11114,
    "0104006" => 11115,
    "0104019" => 11116,
    "0104010" => 11117,
    "0105004" => 11123,
    "0104020" => 11126,
    "0104011" => 11130,
    "0104004" => 11135,
    "0104"    => 11141,
    "0104017" => 11147,
    "0105"    => 11192,
    "0110011" => 11201,
    "0101005" => 11079,
    "0107001" => 11110,
    "0101012" => 11137,
    "0107002" => 11172,
    "0110019" => 11179,
    "0107009" => 11185,
    "0107010" => 11188,
    "0107005" => 11189,
    "0110004" => 11191,
    "0402005" => 11200,
    "0106008" => 11060,
    "0106013" => 11062,
    "0106010" => 11073,
    "0106004" => 11075,
    "0106007" => 11077,
    "0106011" => 11092,
    "0104002" => 11118,
    "0106005" => 11120,
    "0106006" => 11124,
    "0106009" => 11127,
    "0106001" => 11145,
    "0106"    => 11176,
    "0303"    => 11074,
    "0302"    => 11091,
    "0205002" => 11144,
    "0205001" => 11166,
    "0402003" => 11171,
    "0401"    => 11097,
    "0205003" => 11107,
    "0110016" => 11109,
    "0106012" => 11122,
    "0108002" => 11125,
    "0110009" => 11150,
    "0110012" => 11155,
    "0201"    => 11156,
    "0110015" => 11162,
    "0108003" => 11163,
    "0110003" => 11164,
    "0105007" => 11167,
    "0110021" => 11178,
    "0110007" => 11182,
    "0204"    => 11184,
    "0107003" => 11193,
    "0403"    => 11121,
    "0304"    => 11142,
    "0402004" => 11169,
    "0402001" => 11170,
    "0402002" => 11199,
    "0109012" => 11080,
    "0104009" => 11081,
    "0104021" => 11082,
    "0109001" => 11084,
    "0109016" => 11152,
    "0110005" => 11154,
    "0110013" => 11159,
    "0109010" => 11160,
    "0110014" => 11168,
    "0109018" => 11175,
    "0110002" => 11177,
    "0110020" => 11180,
    "0109015" => 11181,
    "0110022" => 11183,
    "0101016" => 11061,
    "0101015" => 11064,
    "0101007" => 11078,
    "0101017" => 11088,
    "0103"    => 11090,
    "0101014" => 11102,
    "0101008" => 11136,
    "0101013" => 11140,
    "0101003" => 11143,
    "0101"    => 11148,
    "0101002" => 11157,
    "0101009" => 11161,
    "0101010" => 11173,
    "0109004" => 11085,
    "0109014" => 11086,
    "0109017" => 11087,
    "0109008" => 11103,
    "0109005" => 11104,
    "0109011" => 11128,
    "0109006" => 11133,
    "0109009" => 11139,
    "0110018" => 11153,
    "0109007" => 11158,
    "0109020" => 11165,
    "0109002" => 11174,
    "0107008" => 11186,
    "0109003" => 11187,
    "01"      => 11202,
    "0104024" => 11203,
    "0106002" => 11204,
    "0106003" => 11205,
    "0107"    => 11206,
    "0107004" => 11207,
    "0107006" => 11208,
    "0107007" => 11209,
    "0108"    => 11210,
    "0108001" => 11211,
    "0109"    => 11212,
    "0109013" => 11213,
    "0109019" => 11214,
    "0110"    => 11215,
    "0110017" => 11216,
    "02"      => 11217,
    "0202"    => 11218,
    "0202001" => 11219,
    "0203"    => 11220,
    "0203001" => 11221,
    "0203002" => 11222,
    "0203003" => 11223,
    "0203004" => 11224,
    "0203005" => 11225,
    "0203006" => 11226,
    "0203007" => 11227,
    "0203008" => 11228,
    "0203009" => 11229,
    "0205"    => 11230,
    "0205004" => 11231,
    "03"      => 11232,
    "04"      => 11233,
    "0402"    => 11234
  ];

  /**
   * Проверка запущена ли команда и блокировка повторного запускаы
   * 
   * @param text pid
   */
  protected function lockProcess($pid)
  {
    $path = Yii::getAlias('@app/runtime/logs/'.$pid.'.txt');
        
    if(!flock($this->lockHandle = fopen($path, 'w'), LOCK_EX | LOCK_NB)) {            
      echo "Команда уже запущена!\n";
      exit;
    }

    fwrite($this->lockHandle,'run');
  }

  /**
   * Разблокировка запуска команды
   */
  protected function unlockProcess()
  {
    flock($this->lockHandle, LOCK_UN);
    fclose($this->lockHandle);
  }

  /** 
   * Парсинг сообщении из ЕФРСБ
   * 
   * @param integer $step
   * @var integer $typeIds (в конфиге params.php)
   * 
   * Command: php yii efrsb/parse-message [Step count]
   */
  public function actionParseMessage($step = 100)
  {
    $this->lockProcess('parse-message');

    echo "=======================\n";
    echo "Начала парсинга!\n";
    $typeIds = Yii::$app->params['parserMessageIds'];
    
    $model = Messages::find()->where(['or', ['status' => 1],['status' => 2]]);

    $where = ['or'];
    foreach ($typeIds as $typeId) {
      $where[] = ['type' => $typeId];
    }

    $messages = $model->andFilterWhere($where)->limit($step)->orderBy(['status' => SORT_DESC, 'id' => SORT_DESC])->all();

    foreach ($messages as $message) {
      $this->dateNow = strtotime(new \DateTime());
      echo "-----------------------\n";
      try {
        switch ($message->type) {
          case 2:
              $result = $this->getAuction($message);
              $message->status = $result['status'];
              $message->update();
            break;
        }
        if ($message->status == 3) {
          $this->_log([
            'model'     => Messages::INT_CODE, 
            'parent_id' => $message->id, 
            'name'      => 'Успешный парсинг сообщении', 
            'message'   => 'Всё прошло без ошибок', 
            'json'      => [
              'messageType' => $message->type,
              'msgId'       => $this->msgId,
              'table'       => $result['table'],
              'messageData' => $this->_xmlToArray($message->message)['MessageData'],
            ]
          ]);
        } else {
          $this->_log([
            'model'     => Messages::INT_CODE, 
            'parent_id' => $message->id, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Не предвиденные ошибки при парсинге сообщения', 
            'message'   => 'Возникли ошибки при парсинге проверте оги по данному сообщению', 
            'json'      => [
              'messageType' => $message->type,
              'msgId'       => $this->msgId,
              'table'       => $result['table'],
              'messageData' => $this->_xmlToArray($message->message)['MessageData'],
            ]
          ]);
        }
      } catch (Exception $e) {
        $this->_log([
          'model'     => Messages::INT_CODE, 
          'parent_id' => $message->id, 
          'status'    => Log::STATUS_ERROR, 
          'name'      => 'Ошибка парсинг сообщении', 
          'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
          'json'      => [
            'error'       => $e->getMessage(),
            'messageType' => $message->type,
            'msgId'       => $this->msgId,
            'messageData' => $this->_xmlToArray($message->message)['MessageData'],
          ]
        ]);
        var_dump($message, $e->getMessage());
      }
    }
    echo "Конец парсинга!\n";
    echo "=======================\n";

    $this->unlockProcess();
  }

  public function actionAgain()
  {
    foreach (Messages::find()->where(['status' => 4])->all() as $message) {
      $message->status = 2;
      $message->update();
    }
    return true;
  }

  /** 
   * Получение сообщении из ЕФРСБ
   * 
   * @param integer $days
   * 
   * Command: php yii efrsb/get-message [Days count]
   */
  public function actionGetMessage($days = 10)
  {
    $this->lockProcess('get-message');

    echo "=======================\n";
    echo "Начала получения сообщении\n";
    try {
      $this->_client();
      $this->dateNow = strtotime(new \DateTime());
      $countSuccsess = 0;
      $countError = 0;

      if ($this->getMessageIds($days)->status) {
        $messages = [];
        echo "Получено: ".count($this->messageIds)."\n";

        foreach ($this->messageIds as $key => $msgId) {
          $message = $this->getMessageContent($msgId);
          
          if ($message->status) {
            $messageContent = $this->_xmlToArray($message->content)['MessageData'];
            $type = self::TYPE[$messageContent['MessageInfo']['MessageType']]['id'];
            if (!$type) {
              $type = 85;
            }
            
            if (!Messages::find()->where(['msg_id' => $msgId, 'msg_guid' => $messageContent['MessageGUID']])->one()) {

              $model = new Messages();
              
              $model->msg_id      = $msgId;
              $model->msg_guid    = $messageContent['MessageGUID'];
              $model->type        = $type;
              $model->message     = $message->content;
              $model->created_at  = $this->dateNow;
              $model->updated_at  = $this->dateNow;

              if (!$model->validate()) {
                $countError++;
                $this->_log([
                  'model'     => Messages::INT_CODE, 
                  'parent_id' => null, 
                  'name'      => 'Ошибка валидации сообщения', 
                  'message'   => 'Какие то данные не правильно вносятся в таблицу сообщения', 
                  'json'      => [
                    'modelErrors' => $model->errors,
                    'modelData'   => $model,
                    'messageType' => $type,
                    'msgId'       => $msgId,
                    'messageData' => $messageContent,
                  ]
                ]);
              } else {
                if ($model->save()) {
                  if($key % 100 == 0) {
                    echo "Обработано: $key\n";
                  }
                  $countSuccsess++;
                  $this->_log([
                    'model'     => Messages::INT_CODE, 
                    'parent_id' => $model->id, 
                    'name'      => 'Успешный добавлено сообщение', 
                    'message'   => 'Данные сообщения успешно внесены в таблицу', 
                    'json'      => [
                      'messageType' => $type,
                      'msgId'       => $msgId,
                      'messageData' => $messageContent,
                    ]
                  ]);
                }
              }
            }
          }
        }
      }
    } catch (Exception $e) {
      echo "Критическая ошибка";
      $this->_log([
        'model'     => Messages::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при добавлении сообщения', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
        ]
      ]);
    }
    echo "Успешный добавлены: $countSuccsess.\n";
    echo "Ошибки при добавлении: $countError.\n";
    echo "Конец получения сообщении\n";
    echo "=======================\n";

    $this->unlockProcess();
  }

  /** 
   * Получениия данных сообщения по ID сообщения
   * 
   * @param integer $msgId
   */
  private function getMessageContent($msgId)
  {
    try {
      return (object) ['status' => true, 'content' => $this->client->GetMessageContent(["id"=>$msgId])->GetMessageContentResult];
    } catch (\Throwable $th) {
      return (object) ['status' => false, 'message' => $th->getMessage()];
    }
  }

  /** 
   * Получение спискаов ID сообщения 
   * за период до сегодняшнего дня
   * 
   * @param integer $days
   */
  private function getMessageIds($days = 10)
  {
    $endDate = date("Y-m-d");
    $startDate = date("Y-m-d", strtotime("-$days days"));

    try {
      $this->messageIds = $this->client->GetMessageIds(["startDate"=>$startDate."T00:00:00","endDate"=>$endDate."T00:00:00"])->GetMessageIdsResult->int;
      return (object) ['status' => true];
    } catch (\Throwable $th) {
      return (object) ['status' => false, 'message' => $th->getMessage()];
    }
  }

  /** 
   * Подключение к клиенту API ЕФРСБ
   * 
   * Настройки подключения находятся в главном конфиге
   */
  private function _client()
  { 
    try {
      $this->client = Yii::$app->efrsbAPI;
      return (object) ['status' => true];
    } catch (\Throwable $th) {
      return (object) ['status' => false, 'message' => $th->getMessage()];
    }
  }

  /** 
   * Проверка данных на отцуствие
   * 
   * @param $value
   */
  private function _checkValue($value)
  {
    if (isset($value['xsi:nil'])) {
      if ($value['xsi:nil'] == 'true') {
        return null;
      }
    }
    if (is_array($value[0])) {
      return null;
    }
    return $value;
  }

  /** 
   * Конвертирование XML в JSON
   * 
   * @param text $xmlMess
   */
  private function _xmlToArray($xmlMess)
  {
    $xml = $this->convert(simplexml_load_string($xmlMess), ['attributePrefix' => '']);
    $json = json_encode($xml);
    return json_decode($json,TRUE);
  }
  /** 
   * Вывод атрибута XML в массиве
   * 
   * @param text $attr
   */
  private function _attributesType($attr)
  {
    return explode('.',$attr);
  }
  /** 
   * Перевод огкруга в ID
   * 
   * @param text $name
   */
  private function _districtConvertor($name)
  {
    return $name
      ? (isset(self::DISTRICT[$name]) ? self::DISTRICT[$name] : 0)
      : 0;
  }
  /** 
   * Перевод категории должников в ID
   * 
   * @param text $name
   */
  private function _bankruptCategoryConvertor($name)
  {
    return $name
      ? (isset(self::BANKRUPT_CATEGORY[$name]) ? self::BANKRUPT_CATEGORY[$name] : 0)
      : 16;
  }

  /**
   * Индексация описания лотов для поиска
   */
  private function _lotSearchIndex($addColumn = false)
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
      if ($addColumn) {
        $db->createCommand('create index fts_index on {{%lot}} using gin (fts);')->execute();
      }

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
      if ($addColumn) {
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
  }

  /**
   * Добавления Лог данных и отслеживании в таблицу log
   * 
   * @param array $data
   */
  private function _log($data)
  {
    $model = new Log();
    
    $model->model         = $data['model'];
    $model->parent_id     = $data['parent_id'];
    $model->status        = (($data['status'])? $data['status'] : Log::STATUS_SUCCESS);
    $model->name          = $data['name'];
    $model->message       = $data['message'];
    $model->message_json  = $data['json'];

    return $model->save();
  }

  /** 
   * Парсинг сообщения под типом Auction
   * 
   * @param text $message
   */
  private function getAuction($message)
  {
    $messageContent = $this->_xmlToArray($message->message)['MessageData'];
    $result['status'] = Messages::STATUS_SUCCESS;
    $this->msgId = $this->caseId = $this->sroId = $this->managerId = $this->bankruptId = $this->etpId = $this->torgId = $this->lotId = null;
    // var_dump($messageContent);
    // die;
    $this->msgId = $messageContent['Id'];
    echo "Номер сообщения: ".$this->msgId."\n";
    if ($this->setCasefile($messageContent)) {
      echo "setCasefile - Успешно!\n";
      
      if ($this->setManager($messageContent['Publisher'])) {
        echo "setManager - Успешно!\n";
      } else {
        $result['status'] = Messages::STATUS_ERROR;
        $result['table'] = 'Manager';
        var_dump($result);
      }
      
      if ($this->setBankrupt($messageContent)) {
        echo "setBankrupt - Успешно!\n";
      } else {
        $result['status'] = Messages::STATUS_ERROR;
        $result['table'] = 'Bankrupt';
        var_dump($result);
      }

      if (!$messageContent['MessageInfo']['Auction']['IdTradePlace']['xsi:nil']) {
        if ($this->getEtp($messageContent['MessageInfo']['Auction'])) {
          echo "getEtp - Успешно!\n";
        } else {
          $result['status'] = Messages::STATUS_ERROR;
          $result['table'] = 'Etp';
        }
      }

      if ($this->managerId && $this->bankruptId) {
        if ($this->setTorg($messageContent)) {
          echo "setTorg - Успешно!\n";

          if (isset($messageContent['MessageInfo']['Auction']['LotTable']['AuctionLot'][0])) {
            echo "Несколько лотов\n";
            foreach ($messageContent['MessageInfo']['Auction']['LotTable']['AuctionLot'] as $lot) {
              if ($this->setLot($lot, $messageContent['MessageInfo']['Bankrupt']['Address'])) {
                echo "setLot - Успешно!\n";
              } else {
                $result['status'] = Messages::STATUS_ERROR;
                $result['table'] = 'Lot';
                var_dump($result);
              }
            }
          } else {
            if ($this->setLot($messageContent['MessageInfo']['Auction']['LotTable']['AuctionLot'], $messageContent['MessageInfo']['Bankrupt']['Address'])) {
              echo "setLot - Успешно!\n";
            } else {
              $result['status'] = Messages::STATUS_ERROR;
              $result['table'] = 'Lot';
              var_dump($result);
            }
          }
        } else {
          $result['status'] = Messages::STATUS_ERROR;
          $result['table'] = 'Torg';
          var_dump($result);
        }
      }

      if (isset($messageContent['MessageURLList'])) {
        if (isset($messageContent['MessageURLList']['MessageURL'][0])) {
          echo "Несколько документов\n";
          foreach ($messageContent['MessageURLList']['MessageURL'] as $key => $document) {
            if ($this->setDocument($document, $messageContent['FileInfoList']['FileInfo'][$key]['Hash'])) {
              echo "setDocument - Успешно!\n";
            } else {
              $result['status'] = Messages::STATUS_ERROR;
              $result['table'] = 'Document';
              var_dump($result);
            }
          }
        } else {
          if ($this->setDocument($messageContent['MessageURLList']['MessageURL'], $messageContent['FileInfoList']['FileInfo']['Hash'])) {
            echo "setDocument - Успешно!\n";
          } else {
            $result['status'] = Messages::STATUS_ERROR;
            $result['table'] = 'Document';
            var_dump($result);
          }
        }
      }

      $this->_lotSearchIndex();
      echo "Индексация пройдена...\n";
    } else {
      $result['status'] = Messages::STATUS_ERROR;
      $result['table'] = 'Casefile';
      var_dump($result);
    }
    
    return $result;
  }

  /**
   * Добавление данных о СРО в таблицу
   * 
   * @param text $data
   * @param integer $parentId
   * @param integer $modelId
   */
  private function setOrganizationSro($data, $parentId, $modelId)
  {
    try {
      if (!$check = Organization::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
        $model = new Organization();
      
        $model->model       = $modelId;
        $model->parent_id   = $parentId;
        $model->activity    = Organization::ACTIVITY_SIMPLE;
        $model->title       = $data['Name'];
        $model->full_title  = '';
        $model->inn         = $data['Inn'];
        $model->ogrn        = $data['Ogrn'];
        $model->website     = '';
        $model->status      = Organization::STATUS_CHECKED;

        if (!$model->validate()) {
          $this->_log([
            'model'     => $modelId, 
            'parent_id' => $parentId, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации данных о СРО в таблице "'.Organization::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Organization::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => $modelId, 
              'parent_id' => $parentId, 
              'name'      => 'Успешное добавление данных о СРО в таблицу "'.Organization::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Organization::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'modelId'     => $model->id,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => $modelId, 
        'parent_id' => $parentId, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге данных о СРО', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!. Таблица "'.Organization::tableName().'"', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о публикаторе в таблицу
   * 
   * @param text $data
   * @param integer $parentId
   * @param integer $modelId
   */
  private function setOrganizationManager($data, $parentId, $modelId, $activity)
  {
    try {
      if (!$check = Organization::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
        $model = new Organization();
      
        $model->model       = $modelId;
        $model->parent_id   = $parentId;
        $model->activity    = $activity;
        $model->title       = $data['Name'];
        $model->full_title  = '';
        $model->inn         = $data['Inn'];
        $model->ogrn        = $data['Ogrn'];
        $model->website     = '';
        $model->status      = Organization::STATUS_CHECKED;

        if (!$model->validate()) {
          $this->_log([
            'model'     => $modelId, 
            'parent_id' => $parentId, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации данных о публикаторе в таблице "'.Organization::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Organization::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => $modelId, 
              'parent_id' => $parentId, 
              'name'      => 'Успешное добавление данных о публикаторе в таблицу "'.Organization::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Organization::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'modelId'     => $model->id,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => $modelId, 
        'parent_id' => $parentId, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге данных о публикаторе', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!. Таблица "'.Organization::tableName().'"', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о компании должника в таблицу
   * 
   * @param text $data
   * @param integer $parentId
   * @param integer $modelId
   */
  private function setOrganizationBankrupt($data, $parentId, $modelId)
  {
    try {
      if (!$check = Organization::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
        $model = new Organization();
        $category = $this->_bankruptCategoryConvertor($data['Category']['Code']);
      
        $model->model       = $modelId;
        $model->parent_id   = $parentId;
        $model->activity    = ($category == 16 ? 9 : $category);
        $model->title       = $data['Name'];
        $model->full_title  = '';
        $model->inn         = $data['Inn'];
        $model->ogrn        = $data['Ogrn'];
        $model->website     = '';
        $model->status      = Organization::STATUS_CHECKED;

        if (!$model->validate()) {
          $this->_log([
            'model'     => $modelId, 
            'parent_id' => $parentId, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации данных о компании должника в таблице "'.Organization::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Organization::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => $modelId, 
              'parent_id' => $parentId, 
              'name'      => 'Успешное добавление данных о компании должника в таблицу "'.Organization::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Organization::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'modelId'     => $model->id,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => $modelId, 
        'parent_id' => $parentId, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге данных о компании должника', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!. Таблица "'.Organization::tableName().'"', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о человеке в таблицу
   * 
   * @param text $data
   * @param integer $parentId
   * @param integer $modelId
   * @param integer $activity
   */
  private function setProfile($data, $parentId, $modelId, $activity)
  {
    try {
      if (!$check = Profile::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
        $model = new Profile();
      
        $model->model       = $modelId;
        $model->parent_id   = $parentId;
        $model->activity    = $activity;
        $model->inn         = $data['Inn'];
        $model->first_name  = $data['Fio']['FirstName'];
        $model->last_name   = $data['Fio']['LastName'];
        $model->middle_name = $data['Fio']['MiddleName'];
        $model->phone       = '';
        $model->snils       = $data['Snils'];
        $model->orgn_ip     = $data['Ogrnip'];

        if (!$model->validate()) {
          $this->_log([
            'model'     => $modelId, 
            'parent_id' => $parentId, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации данных о человеке в таблице "'.Profile::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Profile::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => $modelId, 
              'parent_id' => $parentId, 
              'name'      => 'Успешное добавление данных о человеке в таблицу "'.Profile::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Profile::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'modelId'     => $model->id,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => $modelId, 
        'parent_id' => $parentId, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге данных о человеке', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!. Таблица "'.Profile::tableName().'"', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }
  
  /**
   * Добавление данных о должнике в таблицу
   * 
   * @param text $data
   * @param integer $parentId
   * @param integer $modelId
   */
  private function setProfileBankrupt($data, $parentId, $modelId)
  {
    try {
      if (!$check = Profile::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
        $model = new Profile();
        $category = $this->_bankruptCategoryConvertor($data['Category']['Code']);

        $fio = $data['Fio'];
      
        $model->model       = $modelId;
        $model->parent_id   = $parentId;
        $model->activity    = $category;
        $model->birthday    = (isset($data['Birthdate']) ? strtotime($data['Birthdate']) : null);
        $model->birthplace  = (isset($data['Birthplace']) ? $data['Birthplace'] : null);
        $model->inn         = $data['Inn'];
        $model->first_name  = $fio['FirstName'];
        $model->last_name   = $fio['LastName'];
        $model->middle_name = $fio['MiddleName'];
        $model->phone       = '';
        $model->snils       = $data['Snils'];

        if (!$model->validate()) {
          $this->_log([
            'model'     => $modelId, 
            'parent_id' => $parentId, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации данных о должнике в таблице "'.Profile::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Profile::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => $modelId, 
              'parent_id' => $parentId, 
              'name'      => 'Успешное добавление данных о должнике в таблицу "'.Profile::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Profile::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'modelId'     => $model->id,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => $modelId, 
        'parent_id' => $parentId, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге данных о Должнике', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!. Таблица "'.Profile::tableName().'"', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление адреса в таблицу "place"
   * 
   * @param text $address
   * @param integer $parentId
   * @param integer $modelId
   */
  private function setPlace($address, $parentId, $modelId)
  {
    try {
      if (!$address) {
        return false;
      }
      if (!$check = Place::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
        $addressInfo = GetInfoFor::address($address);
        
        $city     = isset($addressInfo['address']['city']) && $addressInfo['address']['city'] ? $addressInfo['address']['city'] : '';
        $district = $this->_districtConvertor($addressInfo['address']['district']);
        $address  = isset($addressInfo['fullAddress']) && $addressInfo['fullAddress'] ? $addressInfo['fullAddress'] : '-';

        $model = new Place();
      
        $model->model       = $modelId;
        $model->parent_id   = $parentId;
        $model->city        = $city;
        $model->region_id   = $addressInfo['regionId'];
        $model->district_id = $district;
        $model->address     = $address;
        $model->geo_lat     = $addressInfo['address']['geo_lat'];
        $model->geo_lon     = $addressInfo['address']['geo_lon'];

        if (!$model->validate()) {
          $this->_log([
            'model'     => $modelId, 
            'parent_id' => $parentId, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации адрес в таблице "'.Place::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Place::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => $modelId, 
              'parent_id' => $parentId, 
              'name'      => 'Успешное добавление адреса в таблицу "'.Place::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Place::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'modelId'     => $model->id,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => $modelId, 
        'parent_id' => $parentId, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге Адреса', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!. Таблица "'.Place::tableName().'"', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о сведенни Дела должника в таблицу
   * 
   * @param text $data
   */
  private function setCasefile($data)
  {
    try {
      if (!$check = Casefile::find()->where(['reg_number' => $data['CaseNumber']])->one()) {
        $model = new Casefile();
      
        $year = (int)str_replace(' ', '', substr($data['CaseNumber'],strrpos($data['CaseNumber'],"/")+1));

        $model->reg_number  = $data['CaseNumber'];
        $model->year        = ((strlen(''.$year) == 4)? $year.'' : '0000' );
        // $model->judje       = null;
        
        if (!$model->validate()) {
          $this->_log([
            'model'     => Casefile::INT_CODE, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.Casefile::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Casefile::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->caseId = $model->id;
            $this->_log([
              'model'     => Casefile::INT_CODE, 
              'parent_id' => $model->id, 
              'name'      => 'Успешное добавление "'.Casefile::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Casefile::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        $this->caseId = $check->id;
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Casefile::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Casefile::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных СРО в таблицу
   * 
   * @param text $data
   */
  private function setSro($data)
  {
    try {
      if (!$check = Sro::find()->joinWith(['organizationRel'])->where(['or',[Organization::tableName().'.inn' => $data['Inn']], ['efrsb_id' => $this->caseId]])->one()) {
        $model = new Sro();
      
        $model->efrsb_id  = $this->caseId;

        if (!$model->validate()) {
          $this->_log([
            'model'     => Sro::INT_CODE, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.Sro::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Sro::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->sroId = $model->id;
            $this->_log([
              'model'     => Sro::INT_CODE, 
              'parent_id' => $model->id, 
              'name'      => 'Успешное добавление "'.Sro::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Sro::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            if ($this->setOrganizationSro($data, $this->sroId, Sro::INT_CODE)) {
              $this->setPlace($data['Address'], $this->sroId, Sro::INT_CODE);
              return true;
            }
          }
        }
      } else {
        $this->sroId = $check->id;
        if ($this->setOrganizationSro($data, $this->sroId, Sro::INT_CODE)) {
          $this->setPlace($data['Address'], $this->sroId, Sro::INT_CODE);
          return true;
        }
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Sro::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Sro::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных об Арбитражным управляющим в таблицу
   * 
   * @param text $data
   */
  private function setManager($data)
  {
    try {
      $attr = $this->_attributesType($data['xsi:type']);
      $type = $attr[1];
      $version = $attr[2];

      if (!$check = Manager::find()->joinWith(['profileRel'])->where([Profile::tableName().'.inn' => $data['Inn']])->one()) {
        $model = new Manager();
        if ($version == 'v2') {
          $model->agent  = 2;
          
          if (!$model->validate()) {
            $this->_log([
              'model'     => Manager::INT_CODE, 
              'parent_id' => null, 
              'status'    => Log::STATUS_WARNING, 
              'name'      => 'Ошибка валидации "'.Manager::tableName().'"', 
              'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Manager::tableName().'"', 
              'json'      => [
                'modelErrors' => $model->errors,
                'modelData'   => $model,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
          } else {
            if ($model->save()) {
              $this->managerId = $model->id;
              $this->_log([
                'model'     => Manager::INT_CODE, 
                'parent_id' => $model->id, 
                'name'      => 'Успешное добавление "'.Manager::tableName().'"', 
                'message'   => 'Данные успешно добавлены в таблицу "'.Manager::tableName().'"', 
                'json'      => [
                  'modelData'   => $model,
                  'msgId'       => $this->msgId,
                  'messageData' => $data,
                ]
              ]);
              switch ($type) {
                case 'ArbitrManager':
                    if ($this->setProfile($data, $this->managerId, Manager::INT_CODE, Profile::ACTIVITY_SIMPLE)) {
                      $this->setPlace($data['CorrespondenceAddress'], $this->managerId, Manager::INT_CODE);
                      if (isset($data['Sro'])) {
                        if ($this->setSro($data['Sro'])) {
                          echo "setSro - Успешно!\n";
                          if ($this->setManagerSro()) {
                            return true;
                          }
                        }
                      } else {
                        return true;
                      }
                    }
                  break;
                case 'ArbitrManagerSro':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 18)) {
                      $this->setPlace($data['Address'], $this->managerId, Manager::INT_CODE);
                      return true;
                    }
                  break;
                case 'FirmTradeOrganizer':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 19)) {
                      return true;
                    }
                  break;
                case 'PersonTradeOrganizer':
                    if ($this->setProfile(['Fio' => $data['Fio'], 'Inn' => $data['Inn'], 'Ogrnip' => $data['Ogrnip']], $this->managerId, Manager::INT_CODE, 13)) {
                      return true;
                    }
                  break;
                case 'Company':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 17)) {
                      return true;
                    }
                  break;
                case 'Person':
                    if ($this->setProfile(['Fio' => $data['Fio'], 'Inn' => $data['Inn']], $this->managerId, Manager::INT_CODE, 25)) {
                      return true;
                    }
                  break;
                case 'CentralBankRf':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 20)) {
                      return true;
                    }
                  break;
                case 'Asv':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 21)) {
                      return true;
                    }
                  break;
                case 'FnsDepartment':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 22)) {
                      return true;
                    }
                  break;
                case 'Efrsb':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => '', 'Ogrn' => ''], $this->managerId, Manager::INT_CODE, 23)) {
                      return true;
                    }
                  break;
                case 'Mfc':
                    if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 23)) {
                      return true;
                    }
                  break;
              }
            }
          }
        }

      } else {
        $this->managerId = $check->id;
        if ($version == 'v2') {
          switch ($type) {
            case 'ArbitrManager':
              if ($this->setProfile($data, $this->managerId, Manager::INT_CODE, Profile::ACTIVITY_SIMPLE)) {
                $this->setPlace($data['CorrespondenceAddress'], $this->managerId, Manager::INT_CODE);
                if (isset($data['Sro'])) {
                  if ($this->setSro($data['Sro'])) {
                    echo "setSro - Успешно!\n";
                    if ($this->setManagerSro()) {
                      return true;
                    }
                  }
                } else {
                  return true;
                }
              }
              break;
            case 'ArbitrManagerSro':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 18)) {
                $this->setPlace($data['Address'], $this->managerId, Manager::INT_CODE);
                return true;
              }
              break;
            case 'FirmTradeOrganizer':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 19)) {
                return true;
              }
              break;
            case 'PersonTradeOrganizer':
              if ($this->setProfile(['Fio' => $data['Fio'], 'Inn' => $data['Inn'], 'Ogrnip' => $data['Ogrnip']], $this->managerId, Manager::INT_CODE, 13)) {
                return true;
              }
              break;
            case 'Company':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 17)) {
                return true;
              }
              break;
            case 'Person':
              if ($this->setProfile(['Fio' => $data['Fio'], 'Inn' => $data['Inn']], $this->managerId, Manager::INT_CODE, 25)) {
                return true;
              }
              break;
            case 'CentralBankRf':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 20)) {
                return true;
              }
              break;
            case 'Asv':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 21)) {
                return true;
              }
              break;
            case 'FnsDepartment':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 22)) {
                return true;
              }
              break;
            case 'Efrsb':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => '', 'Ogrn' => ''], $this->managerId, Manager::INT_CODE, 23)) {
                return true;
              }
              break;
            case 'Mfc':
              if ($this->setOrganizationManager(['Name' => $data['Name'], 'Inn' => $data['Inn'], 'Ogrn' => $data['Ogrn']], $this->managerId, Manager::INT_CODE, 23)) {
                return true;
              }
              break;
          }
        }
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Manager::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Manager::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление связи между СРО и Арбитражным управляющим в таблицу
   * 
   * @param text $data
   */
  private function setManagerSro()
  {
    try {
      if (!$check = ManagerSro::find()->where(['manager_id' => $this->managerId, 'sro_id' => $this->sroId])->one()) {
        $model = new ManagerSro();
      
        $model->manager_id  = $this->managerId;
        $model->sro_id      = $this->sroId;

        if (!$model->validate()) {
          $this->_log([
            'model'     => 0, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.ManagerSro::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.ManagerSro::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => 0, 
              'parent_id' => null, 
              'name'      => 'Успешное добавление "'.ManagerSro::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.ManagerSro::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'msgId'       => $this->msgId,
              ]
            ]);
            echo "setManagerSro - Успешно!\n";
            return true;
          }
        }
      } else {
        return true;
        echo "setManagerSro - Успешно!\n";
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => 0, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.ManagerSro::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о должнике в таблицу
   * 
   * @param text $data
   */
  private function setBankrupt($data)
  {
    try {
      $attr = $this->_attributesType($data['Bankrupt']['xsi:type']);
      $agent = (($attr[1] === 'Company')? 2 : 1);
      $version = $attr[2];

      if (!$check = Bankrupt::find()->where(['bankrupt_id' => $data['BankruptId']])->one()) {
        $model = new Bankrupt();
      
        $model->agent       = $agent;
        $model->bankrupt_id = $data['BankruptId'];

        if (!$model->validate()) {
          $this->_log([
            'model'     => Bankrupt::INT_CODE, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.Bankrupt::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Bankrupt::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->bankruptId = $model->id;
            $this->_log([
              'model'     => Bankrupt::INT_CODE, 
              'parent_id' => $model->id, 
              'name'      => 'Успешное добавление "'.Bankrupt::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Bankrupt::tableName().'"', 
              'json'      => [
                'modelData'   => $model,
                'msgId'       => $this->msgId,
                'messageData' => $data,
              ]
            ]);
            if ($version == 'v2') {
              if ($agent == 2) {
                $this->setPlace($data['Bankrupt']['Address'], $this->bankruptId, Bankrupt::INT_CODE);
                if ($this->setOrganizationBankrupt($data['Bankrupt'], $this->bankruptId, Bankrupt::INT_CODE)) {
                  return true;
                }
              } else {
                $this->setPlace($data['Bankrupt']['Address'], $this->bankruptId, Bankrupt::INT_CODE);
                if ($this->setProfileBankrupt($data['Bankrupt'], $this->bankruptId, Bankrupt::INT_CODE)) {
                  return true;
                }
              }
            }
          }
        }
      } else {
        $this->bankruptId = $check->id;
        if ($version == 'v2') {
          if ($agent == 2) {
            $this->setPlace($data['Bankrupt']['Address'], $this->bankruptId, Bankrupt::INT_CODE);
            if ($this->setOrganizationBankrupt($data['Bankrupt'], $this->bankruptId, Bankrupt::INT_CODE)) {
              return true;
            }
          } else {
            $this->setPlace($data['Bankrupt']['Address'], $this->bankruptId, Bankrupt::INT_CODE);
            if ($this->setProfileBankrupt($data['Bankrupt'], $this->bankruptId, Bankrupt::INT_CODE)) {
              return true;
            }
          }
        }
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Bankrupt::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Bankrupt::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }
  
  /**
   * Получение ID Торговой площадки
   * 
   * @param text $data
   */
  private function getEtp($data)
  {
    try {
      if ($etp = Etp::find()->where(['efrsb_id' => $data['IdTradePlace']])->one()) {
        $this->etpId = $etp->id;
        $this->_log([
          'model'     => Etp::INT_CODE, 
          'parent_id' => $etp->id, 
          'name'      => 'Успешное получены данные "'.Etp::tableName().'"', 
          'message'   => 'Данные успешно получены из таблицы "'.Etp::tableName().'"', 
          'json'      => [
            'modelData'   => $etp,
            'msgId'       => $this->msgId,
            'messageData' => $data,
          ]
        ]);
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Etp::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Etp::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о торгах в таблицу
   * 
   * @param text $data
   */
  private function setTorg($data)
  {
    try {
      if (!$check = Torg::find()->where(['msg_id' => $this->msgId])->one()) {
        $model = new Torg();
        $auction = $data['MessageInfo']['Auction'];
      
        $model->msg_id          = $this->msgId;
        $model->property        = Torg::PROPERTY_BANKRUPT;
        $model->description     = $this->_checkValue($auction['Text']);
        $model->started_at      = ((isset($auction['Application']['TimeBegin']) && $this->_checkValue($auction['Application']['TimeBegin']) && GetInfoFor::date_check($auction['Application']['TimeEnd']))? strtotime($auction['Application']['TimeBegin']) : null);
        $model->end_at          = ((isset($auction['Application']['TimeEnd']) && $this->_checkValue($auction['Application']['TimeEnd']) && GetInfoFor::date_check($auction['Application']['TimeEnd']))? strtotime($auction['Application']['TimeEnd']) : null);
        $model->completed_at    = ((isset($auction['Date']) && $this->_checkValue($auction['Date']) && GetInfoFor::date_check($auction['Date']))? strtotime($auction['Date']) : null);
        $model->published_at    = ((isset($data['PublishDate']) && $this->_checkValue($auction['PublishDate']) && GetInfoFor::date_check($data['PublishDate']))? strtotime($data['PublishDate']) : null);
        $model->offer           = (isset(self::OFFER[$auction['TradeType']]) ? self::OFFER[$auction['TradeType']] : Torg::OFFER_PUBLIC);
        $model->price_type      = (isset(self::PRICE_TYPE[$auction['PriceType']]) ? self::PRICE_TYPE[$auction['PriceType']] : null);
        $model->is_repeat       = ($auction['IsRepeat'] === 'false'? 0 : 1 );
        $model->additional_text = $auction['AdditionalText'];
        $model->rules           = $auction['Application']['Rules'];

        if (!$model->validate()) {
          $this->_log([
            'model'     => Torg::INT_CODE, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.Torg::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Torg::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->torgId = $model->id;
            $this->_log([
              'model'     => Torg::INT_CODE, 
              'parent_id' => $model->id, 
              'name'      => 'Успешное добавление "'.Torg::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Torg::tableName().'"', 
              'json'      => [
                'msgId'       => $this->msgId,
                'modelData'   => $model,
                'messageData' => $data,
              ]
            ]);
            if ($this->setTorgDebtor()) {
              return true;
            }
          }
        }
      } else {
        $this->torgId = $check->id;
        if ($this->setTorgDebtor()) {
          return true;
        }
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Torg::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Torg::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление связи между основными таблицами
   */
  private function setTorgDebtor()
  {
    try {
      if (!$check = TorgDebtor::find()->where(['torg_id' => $this->torgId])->one()) {
        $model = new TorgDebtor();
      
        $model->torg_id     = $this->torgId;
        $model->etp_id      = $this->etpId;
        $model->bankrupt_id = $this->bankruptId;
        $model->manager_id  = $this->managerId;
        $model->case_id     = $this->caseId;

        if (!$model->validate()) {
          $this->_log([
            'model'     => 0, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.TorgDebtor::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.TorgDebtor::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => 0, 
              'parent_id' => $this->torgId, 
              'name'      => 'Успешное добавление "'.TorgDebtor::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.TorgDebtor::tableName().'"', 
              'json'      => [
                'msgId'       => $this->msgId,
                'modelData'   => $model,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => 0, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.TorgDebtor::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление данных о лоте в таблицу
   * 
   * @param text $data
   * @param text $address
   */
  private function setLot($data, $address)
  {
    try {
      $info = [];

      if ($vin = GetInfoFor::vin($this->_checkValue($data['Description']))) {
        $info = ['vin' => $vin];
      } else if ($cadastr = GetInfoFor::cadastr($this->_checkValue($data['Description']))) {
        $info = ['cadastr' => $cadastr];
        $address = (GetInfoFor::cadastr_address($cadastr))['address'];
      }

      if (!$check = Lot::find()->where(['torg_id' => $this->torgId, 'ordinal_number' => $data['Order']])->one()) {
        $model = new Lot();
        
        $model->torg_id         = $this->torgId;
        $model->ordinal_number  = $this->_checkValue($data['Order']);
        $model->title           = GetInfoFor::mb_ucfirst(GetInfoFor::title($data['Description']));
        $model->description     = $this->_checkValue($data['Description']);
        $model->start_price     = $this->_checkValue($data['StartPrice']);
        $model->step            = round(($this->_checkValue($data['Step']) ? : 0), 4);
        $model->step_measure    = (isset(self::MEASURE[$this->_checkValue($data['AuctionStepUnit'])])? : null);
        $model->deposit         = round(($this->_checkValue($data['Advance']) ? : 0), 4);
        $model->deposit_measure = (isset(self::MEASURE[$this->_checkValue($data['AdvanceStepUnit'])])? : null);
        $model->info            = json_encode($info);
        
        if (!$model->validate()) {
          $this->_log([
            'model'     => Lot::INT_CODE, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.Lot::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.Lot::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->lotId = $model->id;
            $this->_log([
              'model'     => 0, 
              'parent_id' => $model->id, 
              'name'      => 'Успешное добавление "'.Lot::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Lot::tableName().'"', 
              'json'      => [
                'msgId'       => $this->msgId,
                'modelData'   => $model,
                'messageData' => $data,
              ]
            ]);
            if (isset($address)) {
              $this->setPlace($address, $this->lotId, Lot::INT_CODE);
            }
            if (isset($data['ClassifierCollection']['AuctionLotClassifier'][0])) {
              foreach ($data['ClassifierCollection']['AuctionLotClassifier'] as $category) {
                if ($this->setLotCategory($category)) {
                  return true;
                }
              }
            } else {
              if ($this->setLotCategory($data['ClassifierCollection']['AuctionLotClassifier'])) {
                return true;
              }
            }
          }
        }
      } else {
        $this->lotId = $check->id;
        if (isset($address)) {
          $this->setPlace($address, $this->lotId, Lot::INT_CODE);
        }
        if (isset($data['ClassifierCollection']['AuctionLotClassifier'][0])) {
          foreach ($data['ClassifierCollection']['AuctionLotClassifier'] as $category) {
            if ($this->setLotCategory($category)) {
              return true;
            }
          }
        } else {
          if ($this->setLotCategory($data['ClassifierCollection']['AuctionLotClassifier'])) {
            return true;
          }
        }
        
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => Lot::INT_CODE, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Lot::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление категория лота в таблицу
   * 
   * @param text $data
   */
  private function setLotCategory($data)
  {
    try {
      if (!LotCategory::find()->where(['lot_id' => $this->lotId, 'category_id' => self::CATEGORY_CODE[$data['Code']]])->one()) {
        $model = new LotCategory();


        $model->lot_id      = $this->lotId;
        $model->category_id = self::CATEGORY_CODE[$this->_checkValue($data['Code'])];

        if (!$model->validate()) {
          $this->_log([
            'model'     => 0, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.LotCategory::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.LotCategory::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => 0, 
              'parent_id' => $this->lotId, 
              'name'      => 'Успешное добавление "'.LotCategory::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.LotCategory::tableName().'"', 
              'json'      => [
                'msgId'       => $this->msgId,
                'modelData'   => $model,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => 0, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.LotCategory::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }

  /**
   * Добавление документов по делу в таблицу
   * 
   * @param text $data
   */
  private function setDocument($data, $hash)
  {
    try {
      if (!Document::find()->where(['hash' => $hash])->one()) {
        $model = new Document();

        $model->model     = Casefile::INT_CODE;
        $model->parent_id = $this->caseId;
        $model->name      = $data['URLName'];
        $model->ext       = GetInfoFor::format($data['URLName']);
        $model->url       = str_replace('&amp;', '&', $data['URL']);
        $model->hash      = $hash;

        if (!$model->validate()) {
          $this->_log([
            'model'     => 0, 
            'parent_id' => null, 
            'status'    => Log::STATUS_WARNING, 
            'name'      => 'Ошибка валидации "'.Document::tableName().'"', 
            'message'   => 'Данные не прошли валидацию после при попытке добавить данные в таблицу "'.LotCategory::tableName().'"', 
            'json'      => [
              'modelErrors' => $model->errors,
              'modelData'   => $model,
              'msgId'       => $this->msgId,
              'messageData' => $data,
            ]
          ]);
        } else {
          if ($model->save()) {
            $this->_log([
              'model'     => 0, 
              'parent_id' => $model->id, 
              'name'      => 'Успешное добавление "'.Document::tableName().'"', 
              'message'   => 'Данные успешно добавлены в таблицу "'.Document::tableName().'"', 
              'json'      => [
                'msgId'       => $this->msgId,
                'modelData'   => $model,
                'messageData' => $data,
              ]
            ]);
            return true;
          }
        }
      } else {
        return true;
      }
    } catch (Exception $e) {
      $this->_log([
        'model'     => 0, 
        'parent_id' => null, 
        'status'    => Log::STATUS_ERROR, 
        'name'      => 'Ошибка при парсинге "'.Document::tableName().'"', 
        'message'   => 'Критическая ошибка на сервере или в коде нужно проверить данные и код!', 
        'json'      => [
          'error'       => $e->getMessage(),
          'msgId'       => $this->msgId,
          'messageData' => $data,
        ]
      ]);
    }
    return false;
  }
}
