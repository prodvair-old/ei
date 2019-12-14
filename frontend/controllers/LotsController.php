<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use moonland\phpexcel\Excel;

use common\models\Query\Arrest\LotsArrest;

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
                $limit = 1000;
                $offset = $limit - 1000;
            } else {
                $limit = $limit % 1000;
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
                'lotPropName:text',
                'torgs.trgPublished:datetime',
                'torgs.trgStartDateRequest:datetime',
                'torgs.trgExpireDate:datetime',
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
                'lotPropName' => 'Описание',
                'torgs.trgPublished' => 'Дата публикации',
                'torgs.trgStartDateRequest' => 'Дата начала торгов',
                'torgs.trgExpireDate' => 'Дата окончания торгов',
                'lotStartPrice' => 'Начальная цена',
                'lotCadastre' => 'Кадастровый номер',
                'lotVin' => 'VIN номер',
                'lotKladrLocationName' => 'Адрес',
                'lot_archive' => 'В архиве',
            ],
        ]);
    }
}

