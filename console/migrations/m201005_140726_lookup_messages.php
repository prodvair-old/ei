<?php

use yii\db\Migration;

/**
 * Class m201005_140726_lookup_messages
 */
class m201005_140726_lookup_messages extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const MESSAGES_STATUS = 17;
    const MESSAGES_TYPE = 18;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::MESSAGES_STATUS, 'name' => 'messagesStatus']);

        $this->insert(self::TABLE_LOOKUP, ['name' => 'Добавлено', 'code' => 1, 'property_id' => self::MESSAGES_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'В очереди', 'code' => 2, 'property_id' => self::MESSAGES_STATUS, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Успешно', 'code' => 3, 'property_id' => self::MESSAGES_STATUS, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ошибка', 'code' => 4, 'property_id' => self::MESSAGES_STATUS, 'position' => 4]);


        $this->insert(self::TABLE_PROPERTY, ['id' => self::MESSAGES_TYPE, 'name' => 'messagesType']);
        // ArbitralDecree
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о судебном акте', 'code' => 1, 'property_id' => self::MESSAGES_TYPE, 'position' => 1]);
        // Auction
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявление о проведении торгов', 'code' => 2, 'property_id' => self::MESSAGES_TYPE, 'position' => 2]);
        // Meeting 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о собрании кредиторов', 'code' => 3, 'property_id' => self::MESSAGES_TYPE, 'position' => 3]);
        // MeetingResult 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о результатах проведения собрания кредиторов', 'code' => 4, 'property_id' => self::MESSAGES_TYPE, 'position' => 4]);
        // TradeResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о результатах торгов', 'code' => 5, 'property_id' => self::MESSAGES_TYPE, 'position' => 5]);
        // Other
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Иные сведения', 'code' => 6, 'property_id' => self::MESSAGES_TYPE, 'position' => 6]);
        // AppointAdministration
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о решении о назначении временной администрации', 'code' => 7, 'property_id' => self::MESSAGES_TYPE, 'position' => 7]);
        // ChangeAdministration
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения об изменении состава временной администрации', 'code' => 8, 'property_id' => self::MESSAGES_TYPE, 'position' => 8]);
        // TerminationAdministration
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о прекращении деятельности временной администрации', 'code' => 9, 'property_id' => self::MESSAGES_TYPE, 'position' => 9]);
        // BeginExecutoryProcess
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о начале исполнительного производства', 'code' => 10, 'property_id' => self::MESSAGES_TYPE, 'position' => 10]);
        // TransferAssertsForImplementation
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о передаче имущества на реализацию', 'code' => 11, 'property_id' => self::MESSAGES_TYPE, 'position' => 11]);
        // Annul 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения об аннулировании ранее опубликованных сообщений', 'code' => 12, 'property_id' => self::MESSAGES_TYPE, 'position' => 12]);
        // PropertyInventoryResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о результатах инвентаризации имущества должника', 'code' => 13, 'property_id' => self::MESSAGES_TYPE, 'position' => 13]);
        // PropertyEvaluationReport
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения об отчете оценщика, об оценке имущества должника', 'code' => 14, 'property_id' => self::MESSAGES_TYPE, 'position' => 14]);
        // AssessmentReport
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения об отчете оценщика, об оценке имущества должника (версия 2)', 'code' => 15, 'property_id' => self::MESSAGES_TYPE, 'position' => 15]);
        // SaleContractResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о заключении договора купли-продажи', 'code' => 16, 'property_id' => self::MESSAGES_TYPE, 'position' => 16]);
        // SaleContractResult2
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о заключении договора купли-продажи (версия 2)', 'code' => 17, 'property_id' => self::MESSAGES_TYPE, 'position' => 17]);
        // Committee 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о проведении комитета кредиторов', 'code' => 18, 'property_id' => self::MESSAGES_TYPE, 'position' => 18]);
        // CommitteeResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о результатах проведения комитета кредиторов', 'code' => 19, 'property_id' => self::MESSAGES_TYPE, 'position' => 19]);
        // SaleOrderPledgedProperty
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Об определении начальной продажной цены, утверждении порядка и условий проведения торгов по реализации предмета залога, порядка и условий обеспечения сохранности предмета залога', 'code' => 20, 'property_id' => self::MESSAGES_TYPE, 'position' => 20]);
        // ReceivingCreditorDemand
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о получении требования кредитора', 'code' => 21, 'property_id' => self::MESSAGES_TYPE, 'position' => 21]);
        // DemandAnnouncement
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Извещение о возможности предъявления требований', 'code' => 22, 'property_id' => self::MESSAGES_TYPE, 'position' => 22]);
        // CourtAssertAcceptance
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявление о принятии арбитражным судом заявления', 'code' => 23, 'property_id' => self::MESSAGES_TYPE, 'position' => 23]);
        // FinancialStateInformation
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Информация о финансовом состоянии', 'code' => 24, 'property_id' => self::MESSAGES_TYPE, 'position' => 24]);
        // BankPayment
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявление о выплатах Банка России', 'code' => 25, 'property_id' => self::MESSAGES_TYPE, 'position' => 25]);
        // AssetsReturning
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявление о возврате ценных бумаг и иного имущества', 'code' => 26, 'property_id' => self::MESSAGES_TYPE, 'position' => 26]);
        // CourtAcceptanceStatement
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о принятии заявления о признании должника банкротом', 'code' => 27, 'property_id' => self::MESSAGES_TYPE, 'position' => 27]);
        // DeliberateBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о наличии или об отсутствии признаков преднамеренного или фиктивного банкротства', 'code' => 28, 'property_id' => self::MESSAGES_TYPE, 'position' => 28]);
        // IntentionCreditOrg
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о намерении исполнить обязательства кредитной организации', 'code' => 29, 'property_id' => self::MESSAGES_TYPE, 'position' => 29]);
        // LiabilitiesCreditOrg
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о признании исполнения заявителем обязательств кредитной организации несостоявшимся', 'code' => 30, 'property_id' => self::MESSAGES_TYPE, 'position' => 30]);
        // PerformanceCreditOrg
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение об исполнении обязательств кредитной организации', 'code' => 31, 'property_id' => self::MESSAGES_TYPE, 'position' => 31]);
        // BuyingProperty
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о преимущественном праве выкупа имущества', 'code' => 32, 'property_id' => self::MESSAGES_TYPE, 'position' => 32]);
        // DeclarationPersonDamages
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Заявление о привлечении контролирующих должника лиц, а также иных лиц, к ответственности в виде возмещения убытков', 'code' => 33, 'property_id' => self::MESSAGES_TYPE, 'position' => 33]);
        // ActPersonDamages 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам рассмотрения заявления о привлечении контролирующих должника лиц, а также иных лиц, к ответственности в виде возмещения убытков', 'code' => 34, 'property_id' => self::MESSAGES_TYPE, 'position' => 34]);
        // ActReviewPersonDamages
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам пересмотра рассмотрения заявления о привлечении контролирующих должника лиц, а также иных лиц, к ответственности в виде возмещения убытков', 'code' => 35, 'property_id' => self::MESSAGES_TYPE, 'position' => 35]);
        // DealInvalid
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Заявление о признании сделки должника недействительной', 'code' => 36, 'property_id' => self::MESSAGES_TYPE, 'position' => 36]);
        // ActDealInvalid 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам рассмотрения заявления об оспаривании сделки должника', 'code' => 37, 'property_id' => self::MESSAGES_TYPE, 'position' => 37]);
        // ActDealInvalid2 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам рассмотрения заявления об оспаривании сделки должника (версия 2)', 'code' => 38, 'property_id' => self::MESSAGES_TYPE, 'position' => 38]);
        // ActReviewDealInvalid
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам пересмотра рассмотрения заявления об оспаривании сделки должника', 'code' => 39, 'property_id' => self::MESSAGES_TYPE, 'position' => 39]);
        // ActReviewDealInvalid2
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам пересмотра рассмотрения заявления об оспаривании сделки должника (версия 2)', 'code' => 40, 'property_id' => self::MESSAGES_TYPE, 'position' => 40]);
        // DeclarationPersonSubsidiary
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Заявление о привлечении контролирующих должника лиц к субсидиарной ответственности', 'code' => 41, 'property_id' => self::MESSAGES_TYPE, 'position' => 41]);
        // ActPersonSubsidiary 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам рассмотрения заявления о привлечении контролирующих должника лиц к субсидиарной ответственности', 'code' => 42, 'property_id' => self::MESSAGES_TYPE, 'position' => 42]);
        // ActPersonSubsidiary2
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам рассмотрения заявления о привлечении контролирующих должника лиц к субсидиарной ответственности (верия 2)', 'code' => 43, 'property_id' => self::MESSAGES_TYPE, 'position' => 43]);
        // ActReviewPersonSubsidiary
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам пересмотра рассмотрения заявления о привлечении контролирующих должника лиц к субсидиарной ответственности', 'code' => 44, 'property_id' => self::MESSAGES_TYPE, 'position' => 44]);
        // ActReviewPersonSubsidiary
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Судебный акт по результатам пересмотра рассмотрения заявления о привлечении контролирующих должника лиц к субсидиарной ответственности (версия 2)', 'code' => 45, 'property_id' => self::MESSAGES_TYPE, 'position' => 45]);
        // MeetingWorker
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Уведомление о проведении собрания работников, бывших работников должника', 'code' => 46, 'property_id' => self::MESSAGES_TYPE, 'position' => 46]);
        // MeetingWorkerResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о решениях, принятых собранием работников, бывших работников должника', 'code' => 47, 'property_id' => self::MESSAGES_TYPE, 'position' => 47]);
        // ViewDraftRestructuringPlan
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о порядке и месте ознакомления с проектом плана реструктуризации', 'code' => 48, 'property_id' => self::MESSAGES_TYPE, 'position' => 48]);
        // ViewExecRestructuringPlan
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о порядке и месте ознакомления с отчетом о результатах исполнения плана реструктуризации', 'code' => 49, 'property_id' => self::MESSAGES_TYPE, 'position' => 49]);
        // TransferOwnershipRealEstate
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о переходе права собственности на объект незавершенного строительства и прав на земельный участок', 'code' => 50, 'property_id' => self::MESSAGES_TYPE, 'position' => 50]);
        // CancelAuctionTradeResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение об отмене сообщения об объявлении торгов или сообщения о результатах торгов', 'code' => 51, 'property_id' => self::MESSAGES_TYPE, 'position' => 51]);
        // CancelDeliberateBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение об отмене сообщения о наличии или об отсутствии признаков преднамеренного или фиктивного банкротства', 'code' => 52, 'property_id' => self::MESSAGES_TYPE, 'position' => 52]);
        // ChangeAuction
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение об изменении объявления о проведении торгов', 'code' => 53, 'property_id' => self::MESSAGES_TYPE, 'position' => 53]);
        // ChangeDeliberateBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение об изменении сообщения о наличии или об отсутствии признаков преднамеренного или фиктивного банкротства', 'code' => 54, 'property_id' => self::MESSAGES_TYPE, 'position' => 54]);
        // ReducingSizeShareCapital
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение об уменьшении размера уставного капитала банка', 'code' => 55, 'property_id' => self::MESSAGES_TYPE, 'position' => 55]);
        // SelectionPurchaserAssets
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о проведении отбора приобретателей имущества (активов) и обязательств кредитной организации', 'code' => 56, 'property_id' => self::MESSAGES_TYPE, 'position' => 56]);
        // EstimatesCurrentExpenses
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о смете текущих расходов кредитной организации', 'code' => 57, 'property_id' => self::MESSAGES_TYPE, 'position' => 57]);
        // OrderAndTimingCalculations
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о порядке и сроках расчетов с кредиторами', 'code' => 58, 'property_id' => self::MESSAGES_TYPE, 'position' => 58]);
        // InformationAboutBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Информация о ходе конкурсного производства', 'code' => 59, 'property_id' => self::MESSAGES_TYPE, 'position' => 59]);
        // EstimatesAndUnsoldAssets
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения об исполнении сметы текущих расходов и стоимости нереализованного имущества кредитной организации', 'code' => 60, 'property_id' => self::MESSAGES_TYPE, 'position' => 60]);
        // RemainingAssetsAndRight
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявление о наличии у кредитной организации оставшегося имущества и праве ее учредителей (участников) получить указанное имущество', 'code' => 61, 'property_id' => self::MESSAGES_TYPE, 'position' => 61]);
        // ImpendingTransferAssets
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о предстоящей передаче приобретателю имущества (активов) и обязательств кредитной организации или их части', 'code' => 62, 'property_id' => self::MESSAGES_TYPE, 'position' => 62]);
        // TransferAssets 
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о передаче приобретателю имущества и обязательств кредитной организации', 'code' => 63, 'property_id' => self::MESSAGES_TYPE, 'position' => 63]);
        // TransferInsurancePortfolio
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Уведомление о передаче страхового портфеля страховой организации', 'code' => 64, 'property_id' => self::MESSAGES_TYPE, 'position' => 64]);
        // BankOpenAccountDebtor
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о кредитной организации, в которой открыт специальный банковский счет должника', 'code' => 65, 'property_id' => self::MESSAGES_TYPE, 'position' => 65]);
        // ProcedureGrantingIndemnity
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Предложение о погашении требований кредиторов путем предоставления отступного', 'code' => 66, 'property_id' => self::MESSAGES_TYPE, 'position' => 66]);
        // RightUnsoldAsset
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявление о наличии непроданного имущества и праве собственника имущества должника – унитарного предприятия, учредителей (участников) должника получить такое имущество', 'code' => 67, 'property_id' => self::MESSAGES_TYPE, 'position' => 67]);
        // TransferResponsibilitiesFund
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Решение о передаче обязанности по выплате пожизненных негосударственных пенсий и средств пенсионных резервов другому негосударственному пенсионному фонду', 'code' => 68, 'property_id' => self::MESSAGES_TYPE, 'position' => 68]);
        // ExtensionAdministration
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Продление срока деятельности временной администрации', 'code' => 69, 'property_id' => self::MESSAGES_TYPE, 'position' => 69]);
        // MeetingParticipantsBuilding
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Уведомление о проведении собрания участников строительства', 'code' => 70, 'property_id' => self::MESSAGES_TYPE, 'position' => 70]);
        // MeetingPartBuildResult
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о результатах проведения собрания участников строительства', 'code' => 71, 'property_id' => self::MESSAGES_TYPE, 'position' => 71]);
        // PartBuildMonetaryClaim
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Извещение участникам строительства о возможности предъявления денежного требования', 'code' => 72, 'property_id' => self::MESSAGES_TYPE, 'position' => 72]);
        // StartSettlement
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщения о начале расчетов', 'code' => 73, 'property_id' => self::MESSAGES_TYPE, 'position' => 73]);
        // ProcessInventoryDebtor
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о ходе инвентаризации имущества должника', 'code' => 74, 'property_id' => self::MESSAGES_TYPE, 'position' => 74]);
        // Rebuttal
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Опровержение по решению суда опубликованных ранее сведений', 'code' => 75, 'property_id' => self::MESSAGES_TYPE, 'position' => 75]);
        // CreditorChoiceRightSubsidiary
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о праве кредитора выбрать способ распоряжения правом требования о привлечении к субсидиарной ответственности', 'code' => 76, 'property_id' => self::MESSAGES_TYPE, 'position' => 76]);
        // AccessionDeclarationSubsidiary
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Предложение о присоединении к заявлению о привлечении контролирующих лиц должника к субсидиарной ответственности', 'code' => 77, 'property_id' => self::MESSAGES_TYPE, 'position' => 77]);
        // DisqualificationArbitrationManager
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о дисквалификации арбитражного управляющего', 'code' => 78, 'property_id' => self::MESSAGES_TYPE, 'position' => 78]);
        // DisqualificationArbitrationManager2
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о дисквалификации арбитражного управляющего (верися 2)', 'code' => 79, 'property_id' => self::MESSAGES_TYPE, 'position' => 79]);
        // ChangeEstimatesCurrentExpenses
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сведения о скорректированной смете текущих расходов кредитной организации или иной финансовой организации', 'code' => 80, 'property_id' => self::MESSAGES_TYPE, 'position' => 80]);
        // ReturnOfApplicationOnExtrajudicialBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о возврате гражданину поданного им заявления о признании гражданина банкротом во внесудебном порядке', 'code' => 81, 'property_id' => self::MESSAGES_TYPE, 'position' => 81]);
        // StartOfExtrajudicialBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о возбуждении процедуры внесудебного банкротства гражданина', 'code' => 82, 'property_id' => self::MESSAGES_TYPE, 'position' => 82]);
        // TerminationOfExtrajudicialBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о прекращении процедуры внесудебного банкротства гражданина', 'code' => 83, 'property_id' => self::MESSAGES_TYPE, 'position' => 83]);
        // CompletionOfExtrajudicialBankruptcy
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сообщение о завершении процедуры внесудебного банкротства гражданина', 'code' => 84, 'property_id' => self::MESSAGES_TYPE, 'position' => 84]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Без типа', 'code' => 84, 'property_id' => self::MESSAGES_TYPE, 'position' => 85]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::MESSAGES_TYPE);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::MESSAGES_TYPE);
    }
}
