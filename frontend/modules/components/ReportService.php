<?php


namespace frontend\modules\components;

use common\models\db\Invoice;
use common\models\db\Purchase;
use Yii;
use yii\base\Component;

class ReportService extends Component
{

    private $_formUrl = '';

    /**
     * @param $userId
     * @param $cost
     * @param $reportId
     * @param $returnUrl
     * @return bool
     * @throws \Exception
     */
    public function invoiceCreate($userId, $cost, $reportId, $returnUrl)
    {

        $orderInnerId = strtoupper(substr(sha1(microtime(true)), 0, 16)); //TODO
        $orderExternalId = '';
        $formUrl = '';

        $data = [
            'userName'    => Yii::$app->params[ 'paymentUserName' ],
            'password'    => Yii::$app->params[ 'paymentPassword' ],
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
        $response = json_decode($this->request('register.do', $data, $headers), true);

        if (isset($response[ 'orderId' ])) {
            $orderExternalId = $response[ 'orderId' ];
            $formUrl = $response[ 'formUrl' ]; //TODO save ???

            $invoice = new Invoice();
            $invoice->product = Invoice::PRODUCT_REPORT;
            $invoice->parent_id = $reportId;
            $invoice->user_id = $userId;
            $invoice->sum = (int)$cost;
            $invoice->paid = false;
            $invoice->orderExternalId = $orderExternalId;
            $invoice->orderInnerId = $orderInnerId;

            if ($invoice->save()) {
                $this->_formUrl = $formUrl;
                return true;
            }
        }

        return false;
    }

    public function buyConfirm($orderId)
    {
        //TODO getStatus func??
        $data = [
            'userName' => Yii::$app->params[ 'paymentUserName' ],
            'password' => Yii::$app->params[ 'paymentPassword' ],
            'orderId'  => $orderId,
            'language' => 'ru',
        ];

        $headers = [
            'Accept: application/json',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $data = http_build_query($data);
        $response = json_decode($this->request('getOrderStatusExtended.do', $data, $headers), true);


        if ($response[ 'orderStatus' ] == 2) { //TODO magic
            $invoice = Invoice::findOne(['orderExternalId' => $orderId]);
            $invoice->paid = true;

            $purchase = new Purchase();
            $purchase->user_id = $invoice->user_id;
            $purchase->report_id = $invoice->parent_id;
            $purchase->invoice_id = $invoice->id;

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($invoice->save() && $purchase->save()) {
                    $transaction->commit();
                    return true;
                }

                $transaction->rollBack();
                return false;

            } catch (\Throwable $e) {
                $transaction->rollBack();
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPaymentUrl()
    { //TODO
        return $this->_formUrl;
    }

    /**
     * @param string $requestUri
     * @param string $body
     * @param array $headers
     * @param string $method
     * @return false|string
     * @throws \Exception
     */
    protected function request(string $requestUri, string $body, array $headers, $method = 'POST')
    { //TODO move to a separate service

        $ch = curl_init();
        $baseUrl = 'https://3dsec.sberbank.ru/payment/rest/'; //TODO


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
            throw new \Exception(sprintf('curl error: %s, http_code_response : %d', $error, $httpCode));
        }

        $responseBody = substr($response, $headerSize);

        unset($response);

        return $responseBody;
    }

}