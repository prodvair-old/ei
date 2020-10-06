<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\db\Messages;
use common\models\db\Log;
use console\traits\Keeper;

/**
 * Получение и парсинг данных из ЕФРСБ
 * EFRSB Controller
 */
class EfrsbController extends Controller
{
  private $client;
  private $messageIds;

  
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

  public function actionGetMessage()
  {
    $this->_client();
    $dateNow = strtotime(new \DateTime());

    if ($this->getMessageIds(20)->status) {
      $messages = [];

      foreach ($this->messageIds as $msgId) {
        $message = $this->getMessageContent($msgId);
        
        if ($message->status) {
          $messageContent = $this->_xmlToArray($message->content);
          $type = self::TYPE[$messageContent['MessageInfo']['@attributes']['MessageType']]['id'];

          if (!Messages::find()->where(['msg_id' => $msgId, 'msg_guid' => $messageContent['MessageGUID']])->one()) {
            $m = [
              'msg_id'      => $msgId,
              'msg_guid'    => $messageContent['MessageGUID'],
              'type'        => $type,
              'message'     => $message->content,
              'created_at'  => $dateNow,
              'updated_at'  => $dateNow,
            ];
            $model = new Messages($m);

            Keeper::validateAndKeep($model, $messages, $m);
          }
        }
      }

      Yii::$app->db->createCommand()->batchInsert(Messages::tableName(), ['id', 'agent', 'created_at', 'updated_at'], $messages)->execute();
    }
      
  }

  /** 
   * Получениия данных сообщения по ID сообщения
   * 
   * @var integer $msgId
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
   * @var integer $days
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

  private function _xmlToArray($xmlMess)
  {
    $xml = simplexml_load_string($xmlMess);
    $json = json_encode($xml);
    return json_decode($json,TRUE);
  }
}

