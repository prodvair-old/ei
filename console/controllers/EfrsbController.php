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
 * Bankrupt - "bankrupt_id"  bigint null
 * Torg - "is_repeat"  smallint null = 1,0
 * Torg - "price_type"  smallint null = 1,0
 * Torg - "additional_text"  text null
 * Torg - "rules"  text null
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
    '0103' => 123,
  ];

  /** 
   * Парсинг сообщении из ЕФРСБ
   * 
   * @param integer $step
   * @var integer $typeIds (в конфиге params.php)
   */
  public function actionParseMessage($step = 100)
  {
    $typeIds = Yii::$app->params['parserMessageIds'];
    
    $model = Messages::find()->where(['or', ['status' => 1],['status' => 2]]);

    $where = ['or'];
    foreach ($typeIds as $typeId) {
      $where[] = ['type' => $typeId];
    }

    $messages = $model->andFilterWhere($where)->limit($step)->all();

    foreach ($messages as $message) {
      $this->dateNow = strtotime(new \DateTime());
      switch ($message->type) {
        case 2:
          $this->getAuction($message);
          break;
        
        default:
          $this->_messageReady($message);
          break;
      }
    }
    // $messages = Message::find()->where(['']);
  }
  public function actionTest()
  {
    $model = Messages::find()
      ->where(['type' => 5])
      ->one();
    var_dump($model->message);
    // var_dump($this->_xmlToArray($model->message)['MessageData']);
  }

  public function actionGetMessage()
  {
    $this->_client();
    $this->dateNow = strtotime(new \DateTime());

    if ($this->getMessageIds(50)->status) {
      $messages = [];

      foreach ($this->messageIds as $msgId) {
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
            $model->msg_guid     = $messageContent['MessageGUID'];
            $model->type        = $type;
            $model->message     = $message->content;
            $model->created_at  = $this->dateNow;
            $model->updated_at  = $this->dateNow;

            if ($model->validate()) {
              echo $model->save();
            }
          }
        }
      }
    }
      
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
  public function _districtConvertor($name)
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
  public function _bankruptCategoryConvertor($name)
  {
    return $name
      ? (isset(self::BANKRUPT_CATEGORY[$name]) ? self::BANKRUPT_CATEGORY[$name] : 0)
      : 16;
  }
  private function _messageReady($message)
  {
    $message->status = Messages::STATUS_SUCCESS;

    $message->update();
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


  /** 
   * Парсинг сообщения под типом Auction
   * 
   * @param text $message
   */
  private function getAuction($message)
  {
    $messageContent = $this->_xmlToArray($message->message)['MessageData'];
    var_dump($messageContent['MessageInfo']['Auction']);
    $this->msgId = $messageContent['Id'];
    if ($this->setCasefile($messageContent)) {
      echo "setCasefile - Успешно!\n";
      if ($this->setSro($messageContent['Publisher']['Sro'])) {
        echo "setSro - Успешно!\n";
        if ($this->setManager($messageContent['Publisher'])) {
          echo "setManager - Успешно!\n";
          $this->setManagerSro();
        }
      }
      if ($this->setBankrupt($messageContent)) {
        echo "setBankrupt - Успешно!\n";
      }
      if ($this->getEtp($messageContent['MessageInfo']['Auction'])) {
        echo "getEtp - Успешно!\n";
      }
      if ($this->managerId && $this->bankruptId && $this->etpId) {
        if ($this->setTorg($messageContent))  {
          echo "setTorg - Успешно!\n";

          if (isset($messageContent['MessageInfo']['Auction']['LotTable']['AuctionLot'][0])) {
            echo "Несколько лотов\n";
            foreach ($messageContent['MessageInfo']['Auction']['LotTable']['AuctionLot'] as $lot) {
              if ($this->setLot($lot, $messageContent['MessageInfo']['Bankrupt']['Address'])) {
                echo "setLot - Успешно!\n";
              }
            }
          } else {
            if ($this->setLot($messageContent['MessageInfo']['Auction']['LotTable']['AuctionLot'], $messageContent['MessageInfo']['Bankrupt']['Address'])) {
              echo "setLot - Успешно!\n";
            }
          }
        }
      }

      if (isset($messageContent['MessageURLList'])) {
        if ($this->setDocument($messageContent)) {
          echo "setDocument - Успешно!\n";
        }
      }
    }

    $this->_lotSearchIndex();
    echo "Индексация пройдена";
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

      if ($model->save()) {
        return true;
      }
    } else {
      return true;
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

      if ($model->save()) {
        return true;
      }
    } else {
      return true;
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

      if ($model->save()) {
        return true;
      }
    } else {
      return true;
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
    if (!$check = Profile::find()->where(['model' => $modelId, 'parent_id' => $parentId])->one()) {
      $model = new Profile();
      $category = $this->_bankruptCategoryConvertor($data['Category']['Code']);

      if (isset($data['FioHistory'])) {
        $fio = $data['FioHistory']['Fio'];
      } else {
        $fio = $data['Fio'];
      }
    
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

      if ($model->save()) {
        return true;
      }
    } else {
      return true;
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

      if ($model->save()) {
        return true;
      }
    } else {
      return true;
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
    if (!$check = Casefile::find()->where(['reg_number' => $data['CaseNumber']])->one()) {
      $model = new Casefile();
    
      $model->reg_number  = $data['CaseNumber'];
      $model->year        = str_replace(' ', '', substr($data['CaseNumber'],strrpos($data['CaseNumber'],"/")+1));
      // $model->judje       = null;

      if ($model->save()) {
        $this->caseId = $model->id;
        return true;
      }
    } else {
      $this->caseId = $check->id;
      return true;
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
    if (!$check = Sro::find()->joinWith(['organizationRel'])->where(['or',[Organization::tableName().'.inn' => $data['Inn']], ['efrsb_id' => $this->caseId]])->one()) {
      $model = new Sro();
    
      $model->efrsb_id  = $this->caseId;

      if ($model->save()) {
        $this->sroId = $model->id;
        if ($this->setOrganizationSro($data, $this->sroId, Sro::INT_CODE)) {
          $this->setPlace($data['Address'], $this->sroId, Sro::INT_CODE);
          return true;
        }
      }
    } else {
      $this->sroId = $check->id;
      if ($this->setOrganizationSro($data, $this->sroId, Sro::INT_CODE)) {
        $this->setPlace($data['Address'], $this->sroId, Sro::INT_CODE);
        return true;
      }
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
    if (!$check = Manager::find()->joinWith(['profileRel'])->where([Profile::tableName().'.inn' => $data['Inn']])->one()) {
      $model = new Manager();

      $attr = $this->_attributesType($data['xsi:type']);
      $agent = $attr[1];
      $version = $attr[2];

      if ($agent == 'ArbitrManager' && $version == 'v2') {
        $model->agent  = 2;

        if ($model->save()) {
          $this->managerId = $model->id;
          if ($this->setProfile($data, $this->managerId, Manager::INT_CODE, Profile::ACTIVITY_SIMPLE)) {
            $this->setPlace($data['CorrespondenceAddress'], $this->managerId, Manager::INT_CODE);
            return true;
          }
        }
      }
    } else {
      $this->managerId = $check->id;
      if ($this->setProfile($data, $this->managerId, Manager::INT_CODE, Profile::ACTIVITY_SIMPLE)) {
        $this->setPlace($data['Address'], $this->managerId, Manager::INT_CODE);
        return true;
      }
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
    if (!$check = ManagerSro::find()->where(['manager_id' => $this->managerId, 'sro_id' => $this->sroId])->one()) {
      $model = new ManagerSro();
    
      $model->manager_id  = $this->managerId;
      $model->sro_id      = $this->sroId;

      if ($model->save()) {
        echo 'setManagerSro - Успешно!';
        return true;
      }
    } else {
      return true;
      echo 'setManagerSro - Успешно!';
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
    if (!$check = Bankrupt::find()->where(['bankrupt_id' => $data['BankruptId']])->one()) {
      $model = new Bankrupt();
      
      $attr = $this->_attributesType($data['Bankrupt']['xsi:type']);
      $agent = (($attr[1] === 'Company')? 2 : 1);
      $version = $attr[2];
    
      $model->agent       = $agent;
      $model->bankrupt_id = $data['BankruptId'];

      if ($model->save()) {
        $this->bankruptId = $model->id;
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
    return false;
  }
  
  /**
   * Получение ID Торговой площадки
   * 
   * @param text $data
   */
  private function getEtp($data)
  {
    if ($etp = Etp::find()->where(['efrsb_id' => $data['IdTradePlace']])->one()) {
      $this->etpId = $etp->id;
      return true;
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
    if (!$check = Torg::find()->where(['msg_id' => $this->msgId])->one()) {
      $model = new Torg();
      $auction = $data['MessageInfo']['Auction'];
    
      $model->msg_id          = $this->msgId;
      $model->property        = Torg::PROPERTY_BANKRUPT;
      $model->description     = $auction['Text'];
      $model->started_at      = ((isset($auction['Application']['TimeBegin']) && GetInfoFor::date_check($auction['Application']['TimeEnd']))? strtotime($auction['Application']['TimeBegin']) : null);
      $model->end_at          = ((isset($auction['Application']['TimeEnd']) && GetInfoFor::date_check($auction['Application']['TimeEnd']))? strtotime($auction['Application']['TimeEnd']) : null);
      $model->completed_at    = ((isset($auction['Application']['TimeEnd']) && GetInfoFor::date_check($auction['Application']['TimeEnd']))? strtotime($auction['Application']['TimeEnd']) : null);
      $model->published_at    = ((isset($data['PublishDate']) && GetInfoFor::date_check($data['PublishDate']))? strtotime($auction['Application']['TimeEnd']) : null);
      $model->offer           = (isset(self::OFFER[$auction['TradeType']]) ? self::OFFER[$auction['TradeType']] : Torg::OFFER_PUBLIC);
      $model->price_type      = (isset(self::PRICE_TYPE[$auction['PriceType']]) ? self::PRICE_TYPE[$auction['PriceType']] : null);
      $model->is_repeat       = ($auction['IsRepeat'] === 'false'? 0 : 1 );
      $model->additional_text = $auction['AdditionalText'];
      $model->rules           = $auction['Application']['Rules'];

      if ($model->save()) {
        $this->torgId = $model->id;
        if ($this->setTorgDebtor()) {
          return true;
        }
      }
    } else {
      $this->torgId = $check->id;
      if ($this->setTorgDebtor()) {
        return true;
      }
    }
    return false;
  }

  /**
   * Добавление связи между основными таблицами
   */
  private function setTorgDebtor()
  {
    if (!$check = TorgDebtor::find()->where(['torg_id' => $this->torgId])->one()) {
      $model = new TorgDebtor();
    
      $model->torg_id     = $this->torgId;
      $model->etp_id      = $this->etpId;
      $model->bankrupt_id = $this->bankruptId;
      $model->manager_id  = $this->managerId;
      $model->case_id     = $this->caseId;

      if ($model->save()) {
        return true;
      }
    } else {
      return true;
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
    if (!$check = Lot::find()->where(['torg_id' => $this->torgId, 'ordinal_number' => $data['Order']])->one()) {
      $model = new Lot();

      $info = [];

      if ($vin = GetInfoFor::vin($data['Description'])) {
        $info = json_encode(['vin' => $vin]);
      } else if ($cadastr = GetInfoFor::cadastr($data['Description'])) {
        $info = json_encode(['cadastr' => $cadastr]);
        $address = (GetInfoFor::cadastr_address($cadastr))['address'];
      }
    
      $model->torg_id         = $this->torgId;
      $model->ordinal_number  = $data['Order'];
      $model->title           = GetInfoFor::mb_ucfirst(GetInfoFor::title($data['Description']));
      $model->description     = $data['Description'];
      $model->start_price     = $data['StartPrice'];
      $model->step            = round(($data['Step'] ? : 0), 4);
      $model->step_measure    = (isset(self::MEASURE[$data['AuctionStepUnit']])? : null);
      $model->deposit         = round(($data['Advance'] ? : 0), 4);
      $model->deposit_measure = (isset(self::MEASURE[$data['AdvanceStepUnit']])? : null);
      $model->info            = json_encode(isset($obj->vin) ? ['vin' => $obj->vin] : []);
      
      if ($model->save()) {
        $this->lotId = $model->id;
        $this->setPlace($address, $this->lotId, Lot::INT_CODE);
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
    } else {
      $this->lotId = $check->id;
      $this->setPlace($address, $this->lotId, Lot::INT_CODE);
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
    return false;
  }

  /**
   * Добавление категория лота в таблицу
   * 
   * @param text $data
   */
  private function setLotCategory($data)
  {
    
  }

  /**
   * Добавление документов по делу в таблицу
   * 
   * @param text $data
   */
  private function setDocument($data)
  {
    if (isset($data['MessageURLList']['MessageURL'][0])) {
      $check = true;
      foreach ($data['MessageURLList']['MessageURL'] as $key => $document) {
        $model = new Document();

        $model->model     = Casefile::INT_CODE;
        $model->parent_id = $this->caseId;
        $model->name      = $document['URLName'];
        $model->ext       = GetInfoFor::format($document['URLName']);
        $model->url       = str_replace('&amp;', '&', $document['URL']);
        $model->hash      = $data['FileInfoList']['FileInfo'][$key]['Hash'];

        if (!$model->save()) {
          $check = false;
        }
      }
      if ($check) {
        return true;
      }
    } else {
      $model = new Document();

      $model->model     = Casefile::INT_CODE;
      $model->parent_id = $this->caseId;
      $model->name      = $data['MessageURLList']['MessageURL']['URLName'];
      $model->ext       = GetInfoFor::format($data['MessageURLList']['MessageURL']['URLName']);
      $model->url       = str_replace('&amp;', '&', $data['MessageURLList']['MessageURL']['URL']);
      $model->hash      = $data['FileInfoList']['FileInfo']['Hash'];

      if ($model->save()) {
        return true;
      }
    }
    return false;
  }
}

