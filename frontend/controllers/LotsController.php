<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use moonland\phpexcel\Excel;

use common\models\Query\Arrest\LotsArrest;
use common\models\Query\Bankrupt\Arbitrs;

/**
 * Lots controller
 */
class LotsController extends Controller
{
    // php yii lots/arrest
    public function actionArrest($limit = 1000)
    {
        $offset = 0;

        if ($limit > 1000) {
            if ($limit % 1000 == 0) {
                $offset = $limit - 1000;
                $limit = 1000;
            } else {
                $limitThis = $limit;
                $limit = $limit % 1000;
                $offset = $limitThis - $limit;
            }
        } else {
            $limit = 1000;
        }

        $lots = LotsArrest::find()->limit($limit)->offset($offset)->all();

        foreach ($lots as $key => $lot) {
            if ($lot->lotCadastre || $lot->lotVin) {
                $result[] = $lot;
            }
        }
        
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        Excel::export([
            'models' => $result,
            'columns' => [
                'lotId:text',
                [
                    'attribute' => 'lotUrl',
                    'header' => 'Ссылка на лот',
                    'format' => 'text',
                    'value' => function($model) {
                        return 'https://ei.ru/'.$model->lotUrl;
                    },
                ],
                'torgs.trgNotificationUrl:text',
                'lotPropName:text',
                'lotTorgReason:text',
                'torgs.trgBidAuctionDate:datetime',
                'torgs.trgBidFormName:text',
                'torgs.trgPublished:datetime',
                'lotWinnerName:text',
                'lotContractPrice:text',
                'lotStartPrice:text',
                'lotCadastre:text',
                'lotVin:text',
                'lotKladrLocationName:text',
                [
                    'attribute' => 'lotCategory',
                    'header' => 'Категория лота',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->lotCategory[0];
                    },
                ],
                'lot_archive:boolean',
            ],
            'headers' => [
                'lotId' => 'ID лота',
                'torgs.trgNotificationUrl' => 'Ссылка на извещение',
                'lotPropName' => 'Описание',
                'lotTorgReason' => 'Основания реализации торгов',
                'torgs.trgBidAuctionDate' => 'Дата и время проведения торгов',
                'torgs.trgBidFormName' => 'Форма торгов',
                'torgs.trgPublished' => 'Дата публикации',
                'lotWinnerName' => 'Победитель',
                'lotContractPrice' => 'Цена предложенное победителем',
                'lotCadastre' => 'Кадастровый номер',
                'lotVin' => 'VIN номер',
                'lotKladrLocationName' => 'Адрес',
                'lot_archive' => 'В архиве',
            ],
        ]);
    }

    public function actionArbitrs($limit = 20)
    {
        if ($limit > 20) {
            if ($limit % 20 == 0) {
                $offset = $limit - 20;
                $limit = 20;
            } else {
                $limitThis = $limit;
                $limit = $limit % 20;
                $offset = $limitThis - $limit;
            }
        } else {
            $limit = 20;
        }

        $arbitrs = Arbitrs::find()->limit($limit)->offset($offset)->orderBy('id ASC')->all();

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        Excel::export([
            'models' => $arbitrs,
            'columns' => [
                'id:text',
                [
                    'attribute' => 'url',
                    'header' => 'Ссылка на ei.ru',
                    'format' => 'text',
                    'value' => function($model) {
                        return 'https://ei.ru/arbitrazhnye-upravlyayushchie/'.$model->id;
                    },
                ],
                'person.lname:text',
                'person.fname:text',
                'person.mname:text',
                'person.inn:integer',
                'regnum:text',
                'sro.title:text',
                [
                    'attribute' => 'urlSro',
                    'header' => 'Ссылка на СРО ei.ru',
                    'format' => 'text',
                    'value' => function($model) {
                        return 'https://ei.ru/sro/'.$model->sro->id;
                    },
                ],
                'caseCount:text',
                'lotsCount:text',
            ],
            'headers' => [
                'id' => 'ID арбитражного управляющего',
                'person.lname' => 'Фамилия',
                'person.fname' => 'Имя',
                'person.mname' => 'Отчество',
                'person.inn' => 'ИНН',
                'regnum' => 'Регистрационный номер',
                'sro.title' => 'СРО',
                'caseCount' => 'Количество дел',
                'lotsCount' => 'Количество опубликованных лотов',
            ],
        ]);
    }
}

