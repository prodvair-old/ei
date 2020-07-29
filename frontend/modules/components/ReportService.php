<?php


namespace frontend\modules\components;


use common\models\db\Invoice;
use common\models\db\Place;
use common\models\db\Profile;
use common\models\db\Purchase;
use common\models\db\User;
use frontend\modules\profile\forms\ProfileForm;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

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
    public function invoiceCreate($userId, $cost, $reportId, $returnUrl) {

        $orderInnerId = strtoupper(substr(sha1(microtime(true)),0,16)); //TODO
        $orderExternalId = '';
        $formUrl = '';

        $data = [
          'userName' => Yii::$app->params['paymentUserName'],
          'password' => Yii::$app->params['paymentPassword'],
          'amount' => $cost * 100,
          'currency' => 643,
          'language' => 'ru',
          'orderNumber' => $orderInnerId,
          'returnUrl' => $returnUrl,
          'failUrl' => '', //TODO
        ];

        $headers = [
            'Accept: application/json',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $data = http_build_query($data);
        $response = json_decode($this->sendPayment('register.do', $data, $headers), true);

        if(isset($response['orderId'])) {
            $orderExternalId = $response['orderId'];
            $formUrl = $response['formUrl']; //TODO save ???

            $invoice = new Invoice();
            $invoice->product = Invoice::PRODUCT_REPORT;
            $invoice->parent_id = $reportId;
            $invoice->user_id = $userId;
            $invoice->sum = (int)$cost;
            $invoice->paid = false;
            $invoice->orderExternalId = $orderExternalId;
            $invoice->orderInnerId = $orderInnerId;

            if($invoice->save()) {
                $this->_formUrl = $formUrl;
                return true;
            }
            else {
                echo "<pre>";
                var_dump($invoice->getErrorSummary(true));
                echo "</pre>";
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPaymentUrl() { //TODO
        return $this->_formUrl;
    }

    public function buy($invoiceId, $userId, $reportId) { //TODO
        $purchase = new Purchase();

        $purchase->user_id = $userId;
        $purchase->report_id = $reportId;
        $purchase->invoice_id = $invoiceId;

        return $purchase->save();

    }

        protected function sendPayment(string $requestUri, string $body, array $headers) { //TODO move to a separate service

        $ch = curl_init();
        $baseUrl = 'https://3dsec.sberbank.ru/payment/rest/'; //TODO


        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_URL           => $baseUrl . $requestUri,
            CURLOPT_POSTFIELDS    => $body ?: null,
            CURLOPT_HTTPHEADER    => $headers,

//            CURLOPT_SSL_VERIFYHOST => 0,
//            CURLOPT_SSL_VERIFYPEER => 0,
//            CURLOPT_FOLLOWLOCATION => true,

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
//            throw new ConnectorException(sprintf('curl error: %s, http_code_response : %d',  $error, $httpCode));
            throw new \Exception(sprintf('curl error: %s, http_code_response : %d',  $error, $httpCode));
        }

        $responseBody = substr($response, $headerSize);

        unset($response);

        return $responseBody;
    }

}