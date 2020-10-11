<?php

namespace frontend\modules\payment\components;

/**
 * Interface PaymentConnectorInterface
 */
interface PaymentConnectorInterface
{
    /**
     * @param $cost
     * @param $returnUrl
     * @return mixed
     * @throws Exception
     */
    public function registerPayment($cost, $returnUrl);

    /**
     * @param $orderId
     * @return bool
     */
    public function isPaid($orderId): bool;

    public function getOrderId();

    public function getPaymentGate();

}