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

    public $agentPrice = 0;
    
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
            [['lotId', 'lotType', 'checkPolicy'], 'required'],
            ['agentPrice', 'number'],
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

        if ($this->lot->lotPrice < 500000) {
            $this->agentPrice = 8000;
        } else if ($this->lot->lotPrice < 1000000) {
            $this->agentPrice = 12000;
        } else if ($this->lot->lotPrice < 2000000) {
            $this->agentPrice = 15000;
        } else if ($this->lot->lotPrice < 4000000) {
            $this->agentPrice = 20000;
        } else if ($this->lot->lotPrice < 6000000) {
            $this->agentPrice = 25000;
        } else if ($this->lot->lotPrice < 8000000) {
            $this->agentPrice = 30000;
        } else if ($this->lot->lotPrice < 10000000) {
            $this->agentPrice = 35000;
        } else if ($this->lot->lotPrice < 15000000) {
            $this->agentPrice = 40000;
        } else if ($this->lot->lotPrice < 30000000) {
            $this->agentPrice = 50000;
        } else {
            $this->agentPrice = 60000;
        }
        
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
