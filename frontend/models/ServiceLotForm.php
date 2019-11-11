<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

/**
 * Service Lot form
 */
class ServiceLotForm extends Model
{
    public $ecp;

    public $serviceAgent;
    public $serviceKonsultEcp;
    public $serviceRegEcp;
    public $serviceSendZ;
    public $serviceSendLastZ;
    public $serviceTorg;
    
    public $servicePrice;
    
    public $lotId;
    public $lotType;
    public $lot;

    public $checkPolicy;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'ecp', 'serviceAgent', 'serviceKonsultEcp', 'serviceRegEcp', 'serviceSendZ', 'serviceSendLastZ', 'serviceTorg',
            ], 
            'boolean'],
            [['lotId', 'lotType', 'servicePrice', 'checkPolicy'], 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function send()
    {
        if (!$this->validate()) {
            return null;
        }

        switch ($this->lotType) {
            case 'arrest':
                    $this->lot = LotsArrest::findOne($this->lotId);
                break;
            case 'bankrupt':
                    $this->lot = LotsBankrupt::findOne($this->lotId);
                break;
        }

        $servicePrice = 0;

        if ($this->serviceAgent) {
            $servicePrice += 0;
        }
        if ($this->serviceKonsultEcp) {
            $servicePrice += $this->lot->lotPrice;
        }
        if ($this->serviceRegEcp) {
            $servicePrice += 2500;
        }
        if ($this->serviceSendZ) {
            $servicePrice += 5000;
        }
        if ($this->serviceTorg) {
            $servicePrice += 5000;
        }
        if ($this->serviceSendLastZ) {
            $servicePrice += 7000;
        }

        $this->servicePrice = $servicePrice;
        
        $user = Yii::$app->user->identity;

        return $this->sendEmail($user, 'serviceBackLot-html', 'agent@ei.ru') && $this->sendEmail($user, 'serviceFrontLot-html', $user->info['contacts']['emails'][0]);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user, $html, $email)
    {
        return Yii::$app
            ->mailer_agent
            ->compose(
                ['html' => $html],
                ['user' => $user, 'params'=>$this]
            )
            ->setFrom(['agent@ei.ru' => Yii::$app->name . ' robot'])
            ->setTo($email)
            ->setSubject('Услуга агента по тограм на сайте ei.ru')
            ->send();
    }
}
