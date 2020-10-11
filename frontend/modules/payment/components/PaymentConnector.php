<?php

namespace frontend\modules\payment\components;

use Yii;
use Exception;

/**
 * Class PaymentConnector
 */
class PaymentConnector implements PaymentConnectorInterface
{
    const PAYMENT_STATUS_SUCCESS = 2;

    private $orderId = null;

    private $paymentGate = null;

    /**
     * @return null
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param null $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return null
     */
    public function getPaymentGate()
    {
        return $this->paymentGate;
    }

    /**
     * @param null $paymentGate
     */
    public function setPaymentGate($paymentGate)
    {
        $this->paymentGate = $paymentGate;
    }

    /**
     * @param $cost
     * @param $returnUrl
     * @return bool
     * @throws Exception
     */
    public function registerPayment($cost, $returnUrl): bool
    {
        $orderInnerId = strtoupper(substr(sha1(microtime(true)), 0, 16));

        $data = [
            'userName'    => Yii::$app->params['paymentUserName'],
            'password'    => Yii::$app->params['paymentPassword'],
            'amount'      => $cost * 100,
            'currency'    => 643,
            'language'    => 'ru',
            'orderNumber' => $orderInnerId,
            'returnUrl'   => $returnUrl,
            'failUrl'     => '', //TODO
        ];

        $headers = [
            'Accept: application/json',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $data = http_build_query($data);

        try {
            $response = json_decode($this->request('register.do', $data, $headers), true);
        } catch (Exception $e) {
            throw new Exception(sprintf('payment error: %s', $e->getMessage()));
        }

        if ($response['orderId']) {
            $this->setOrderId($response['orderId']);
            $this->setPaymentGate($response['formUrl']);
            return true;
        }

        return false;
    }

    /**
     * @param $orderId
     * @return bool
     * @throws Exception
     */
    public function isPaid($orderId): bool
    {
        $data = [
            'userName' => Yii::$app->params['paymentUserName'],
            'password' => Yii::$app->params['paymentPassword'],
            'orderId'  => $orderId,
            'language' => 'ru',
        ];

        $headers = [
            'Accept: application/json',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $data = http_build_query($data);

        try {
            $response = json_decode($this->request('getOrderStatusExtended.do', $data, $headers), true);
        } catch (Exception $e) {
            throw new Exception(sprintf('payment error: %s', $e->getMessage()));
        }

        if ($response['orderStatus'] == self::PAYMENT_STATUS_SUCCESS) {
            return true;
        }

        return false;
    }

    protected function request(string $requestUri, string $body, array $headers, $method = 'POST'): string
    {
        $ch = curl_init();
        $baseUrl = Yii::$app->params['paymentConnectorBaseUrl'];

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL           => $baseUrl . $requestUri,
            CURLOPT_POSTFIELDS    => $body ?: null,
            CURLOPT_HTTPHEADER    => $headers,

            CURLOPT_HEADER         => true,
            CURLINFO_HEADER_OUT    => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 120,
        ]);

        $response = curl_exec($ch);

        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        curl_close($ch);

        if ($error || $httpCode !== 200) {
            throw new Exception(sprintf('curl error: %s, http_code_response : %d', $error, $httpCode));
        }

        $responseBody = substr($response, $headerSize);

        unset($response);

        return $responseBody;
    }
}