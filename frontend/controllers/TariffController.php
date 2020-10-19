<?php

namespace frontend\controllers;

use common\models\db\Invoice;
use common\models\db\Purchase;
use common\models\db\Subscription;
use common\models\db\Tariff;
use Exception;
use frontend\modules\forms\SubscribeForm;
use frontend\modules\payment\components\PaymentConnectorInterface;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * Class TariffController
 * @package frontend\controllers
 */
class TariffController extends Controller
{

    private $paymentConnector;

    public function __construct($id, $module, PaymentConnectorInterface $paymentConnector, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->paymentConnector = $paymentConnector;
    }

    public function actionIndex()
    {
        $paymentStatus = [
            'status' => false,
            'msg'    => null
        ];
        $tariffs = Tariff::find()->all();
        $subForm = new SubscribeForm();

        if (Yii::$app->request->isPost) {
            $subForm->load(Yii::$app->request->post());

            $returnUrl = Url::toRoute(['/tariff/index'], []);

            try {
                if ($this->paymentConnector->registerPayment($subForm->fee, $returnUrl)) {
                    $orderInnerId = strtoupper(substr(sha1(microtime(true)), 0, 16));

                    $invoice = new Invoice(); //TODO сервис, удалять ненужные инвойсы
                    $invoice->product = Invoice::PRODUCT_TARIFF;
                    $invoice->parent_id = $subForm->tariffId;
                    $invoice->user_id = $subForm->userId;
                    $invoice->sum = (int)$subForm->fee;
                    $invoice->paid = false;
                    $invoice->orderExternalId = $this->paymentConnector->getOrderId();
                    $invoice->orderInnerId = $orderInnerId;

                    if ($invoice->save()) {
                        $sub = new Subscription();
                        $sub->user_id = $invoice->user_id;
                        $sub->tariff_id = $invoice->parent_id;
                        $sub->invoice_id = $invoice->id;
                        $sub->from_at = time();
                        $sub->created_at = time();
                        $sub->till_at = time() + ($subForm->term * 3600 * 24);

                        if ($sub->save()) {
                            $this->redirect($this->paymentConnector->getPaymentGate());
                        }
                    }

                    $paymentStatus['msg'] = 'Оплата не удалась, повторите попытку11';
                }
            } catch (Exception $e) {
                $paymentStatus['msg'] = 'Оплата не удалась, повторите попытку';
            }
        }

        $getParams = Yii::$app->request->get();

        if ($getParams['orderId']) {

            if ($this->paymentConnector->isPaid($getParams['orderId'])) {

                $invoice = Invoice::findOne(['orderExternalId' => $getParams['orderId']]);
                $invoice->paid = true;

                try {
                    if ($invoice->save()) {
                        $paymentStatus['status'] = true;
                        $paymentStatus['msg'] = 'Подписка успешно оплачена!';
                    } else {
                        //TODO добавить функционал проверки оплаты
                        $paymentStatus['msg'] = 'Не можем проверить оплату, номер вашего заказа ' . $invoice->orderInnerId;
                    }
                } catch (\Throwable $e) {
                    //TODO добавить функционал проверки оплаты
                    $paymentStatus['msg'] = 'Не можем проверить оплату, номер вашего заказа ' . $invoice->orderInnerId;
                }
            }
        }

        return $this->render('index', [
            'tariffs'       => $tariffs,
            'paymentStatus' => $paymentStatus,
            'subForm'       => $subForm,
        ]);
    }

//    public function actionBuy() {
//        $subForm = new SubscribeForm();
//    }
}
