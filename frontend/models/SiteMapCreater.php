<?php
namespace frontend\models;
 
use Yii;
use yii\base\Model;
use yii\helpers\Url;
use app\models\Func;

use common\models\Query\Bankrupt\Lots;
use common\models\Query\Bankrupt\Bankrupts;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Sro;
use common\models\Query\Arrest\LotsArrest;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;

use common\models\Query\MetaDate;

class SiteMapCreater extends Model
{
 
    public function getUrl($param)
    {
        
        $urls = array();

        if (!empty($param['limit'])) {
            $offset = 0;

            if ($param['limit'] > 1000) {
                $limit = 1000;
                $offset = $param['limit'] - 1000;
            } else {
                $limit = $param['limit'];
            }
        }
        
        switch ($param['type']) {
            case 'pages':

                    $metaData = MetaDate::find()->all();

                    $arr_stat_page = [];

                    foreach ($metaData as $key => $value) {
                        switch ($value->mdName) {
                            case 'index':
                                $arr_stat_page[] = [
                                    'link'  => Url::to(['site/index']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'about':
                                $arr_stat_page[] = [
                                    'link'  => Url::to(['pages/about']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'licens':
                                $arr_stat_page[] = [
                                    'link'  => Url::to(['pages/license']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'politic':
                                $arr_stat_page[] = [
                                    'link'  => Url::to(['pages/policy']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'faq':
                                $arr_stat_page[] = [
                                    'link'  => Url::to(['pages/faq']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'sitemap':
                                $arr_stat_page[] = [
                                    'link'  => Url::to(['pages/sitemap']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'arbitr-list':
                                $arr_stat_page[] = [
                                    'link'  => Url::to('arbitrazhnye-upravlyayushchie'),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'doljnik-list':
                                $arr_stat_page[] = [
                                    'link'  => Url::to('dolzhniki'),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'sro-list':
                                $arr_stat_page[] = [
                                    'link'  => Url::to('sro'),
                                    'title' => $value->mdTitle
                                ];
                                break;
                        }
                    }

                    foreach ($arr_stat_page as $url_stat){
                        $urls[] = [
                            Yii::$app->urlManager->createUrl($url_stat['link']),
                            'monthly',
                            'title' => $url_stat['title'],
                            'type' => 'pages'
                        ];
                    }
                break;
            case 'service':

                    $metaData = MetaDate::find()->all();

                    $arr_service = [];

                    foreach ($metaData as $key => $value) {
                        switch ($value) {
                            case 'service':
                                $arr_service[] = [
                                    'link'  => Url::to(['services/index']),
                                    'title' => $value->mdTitle
                                ];
                                break;
                            case 'service/agent':
                                $arr_service[] = [
                                    'link'  => Url::to(['services/agent']),
                                    'title' => ($value->mdTitle)? $value->mdTitle : 'Услуги агента'
                                ];
                                break;
                            case 'service/ecp':
                                $arr_service[] = [
                                    'link'  => Url::to(['services/ecp']),
                                    'title' => ($value->mdTitle)? $value->mdTitle : 'Покупка ЭЦП'
                                ];
                                break;
                            case 'service/specialist':
                                $arr_service[] = [
                                    'link'  => Url::to(['services/specialist']),
                                    'title' => ($value->mdTitle)? $value->mdTitle : 'Консультация специалиста'
                                ];
                                break;
                        }
                    }

                    foreach ($arr_service as $url_stat){
                        $urls[] = [
                            Yii::$app->urlManager->createUrl($url_stat['link']),
                            'weekly',
                            'title' => $url_stat['title'],
                            'type' => 'services'
                        ];
                    }
                break;
            case 'lots_filter':

                $metaData = MetaDate::find()->where(['mdName' => 'bankrupt'])->one();
                $urls[] = [
                    Yii::$app->urlManager->createUrl('bankrupt'), 
                    'weekly', 
                    'title' => ($metaData->mdTitle)? $metaData->mdTitle : 'Банкротное имущество',
                    'type' => 'lots-type'
                ];
                
                $metaData = MetaDate::find()->where(['mdName' => 'arrest'])->one();
                $urls[] = [
                    Yii::$app->urlManager->createUrl('arrest'), 
                    'weekly', 
                    'title' => ($metaData->mdTitle)? $metaData->mdTitle : 'Арестованное имущество',
                    'type' => 'lots-type'
                ];

                // Категории лотов
                $lotsCategory = LotsCategory::find()->all();
                $regions = Regions::find()->all();

                foreach ($lotsCategory as $category) {
                    
                    // Категории банкротки
                    $metaData = MetaDate::find()->where(['mdName' => 'bankrupt/'.$category->translit_name])->one();

                    $urls[] = [
                        Yii::$app->urlManager->createUrl('bankrupt/'.$category->translit_name), 
                        'weekly', 
                        'title' => ($metaData->mdTitle)? $metaData->mdTitle : $category->name,
                        'type' => 'lots-bankrupt-category'
                    ];

                    // Подкатегории банкротки
                    if (!empty($category->bankrupt_categorys_translit)) {
                        foreach ($category->bankrupt_categorys_translit as $key => $value) {
                            $metaData = MetaDate::find()->where(['mdName' => 'bankrupt/'.$category->translit_name.'/'.$key])->one();

                            $urls[] = [
                                Yii::$app->urlManager->createUrl('bankrupt/'.$category->translit_name.'/'.$key), 
                                'weekly', 
                                'title' => ($metaData->mdTitle)? $metaData->mdTitle : $value['name'],
                                'type' => 'lots-bankrupt-subcategory'
                            ];

                            // Регионы банкротки
                            foreach ($regions as $region) {
                                $metaData = MetaDate::find()->where(['mdName' => 'bankrupt/'.$category->translit_name.'/'.$key.'/'.$region->name_translit])->one();

                                $urls[] = [
                                    Yii::$app->urlManager->createUrl('bankrupt/'.$category->translit_name.'/'.$key.'/'.$region->name_translit), 
                                    'weekly', 
                                    'title' => ($metaData->mdTitle)? $metaData->mdTitle : $region->name,
                                    'type' => 'lots-bankrupt-region'
                                ];
                            }
                        }
                    }

                    // Категории арестовки
                    $metaData = MetaDate::find()->where(['mdName' => 'arrest/'.$category->translit_name])->one();

                    $urls[] = [
                        Yii::$app->urlManager->createUrl('arrest/'.$category->translit_name), 
                        'weekly', 
                        'title' => ($metaData->mdTitle)? $metaData->mdTitle : $category->name,
                        'type' => 'lots-arrest-category'
                    ];

                    // Подкатегории арестовки
                    if (!empty($category->arrest_categorys_translit)) {
                        foreach ($category->arrest_categorys_translit as $key => $value) {
                            $metaData = MetaDate::find()->where(['mdName' => 'arrest/'.$category->translit_name.'/'.$key])->one();

                            $urls[] = [
                                Yii::$app->urlManager->createUrl('arrest/'.$category->translit_name.'/'.$key), 
                                'weekly', 
                                'title' => ($metaData->mdTitle)? $metaData->mdTitle : $value['name'],
                                'type' => 'lots-arrest-subcategory'
                            ];

                            // Регионы арестовки
                            foreach ($regions as $region) {
                                $metaData = MetaDate::find()->where(['mdName' => 'arrest/'.$category->translit_name.'/'.$key.'/'.$region->name_translit])->one();

                                $urls[] = [
                                    Yii::$app->urlManager->createUrl('arrest/'.$category->translit_name.'/'.$key.'/'.$region->name_translit), 
                                    'weekly', 
                                    'title' => ($metaData->mdTitle)? $metaData->mdTitle : $region->name,
                                    'type' => 'lots-arrest-region'
                                ];
                            }
                        }
                    }
                }
                break;
            case 'lots_bankrupt':
                    // Лоты банкротки
                    if (!$param['limit']) {
                        return ['error' => 'не задан limit'];
                    }
                    
                    $metaData = MetaDate::find()->where(['mdName' => 'lot-page'])->one();

                    $lots = Lots::find()->joinWith('torgy')->where('torgy.timeend >= NOW()')->limit($limit)->offset($offset)->all();
                
                    $search  = [
                        '${lotTitle}', 
                        '${lotAddress}', 
                        '${lotStatus}', 
                        '${bnkrName}',
                        '${arbitrName}',
                        '${sroName}',
                        '${etp}',
                        '${tradeType}',
                        '${caseId}', 
                        '${category}',
                        '${subCategory}',
                        '${startPrice}',
                        '${lotPrice}',
                        '${stepPrice}',
                        '${advance}',
                        '${priceType}',
                        '${timeEnd}',
                        '${timeBegin}'
                    ];
                    
                    foreach ($lots as $lot){
                        $replace = [
                            str_replace('"',"'",$lot->lotTitle),
                            str_replace('"',"'",$lot->lotAddress),
                            str_replace('"',"'",$lot->lotStatus),
                            str_replace('"',"'",$lot->lotBnkrName),
                            str_replace('"',"'",$lot->lotArbtrName),
                            str_replace('"',"'",$lot->lotSroTitle),
                            str_replace('"',"'",$lot->lotEtp),
                            (($lot->lotTradeType != 'PublicOffer')? 'публичное предложение': 'открытый аукцион'),
                            $lot->torgy->case->caseid, 
                            $category_title,
                            $subCategory,
                            Yii::$app->formatter->asCurrency($lot->startprice),
                            Yii::$app->formatter->asCurrency($lot->lotPrice),
                            (($lot->auctionstepunit == 'Percent')? $lot->stepprice.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->stepprice)).')' : Yii::$app->formatter->asCurrency($lot->stepprice)),
                            (($lot->advancestepunit == 'Percent')? $lot->advance.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->advance)).')' : Yii::$app->formatter->asCurrency($lot->advance)),
                            (($lot->torgy->pricetype == 'Public')? 'Открытая' : 'Закрытая'),
                            Yii::$app->formatter->asDate($lot->torgy->timeend, 'long'),
                            Yii::$app->formatter->asDate($lot->torgy->timebegin, 'long')
                        ];

                        $urls[] = [
                            Yii::$app->urlManager->createUrl($lot->lotUrl), 
                            'daily', 
                            'title' => str_replace($search, $replace, $metaData->mdTitle),
                            'type' => 'lots-bankrupt'
                        ];
                    }
                break;
            case 'lots_arrest':
                // Лоты арестовки
                if (!$param['limit']) {
                    return ['error' => 'не задан limit'];
                }
                
                $metaData = MetaDate::find()->where(['mdName' => 'lot-page'])->one();

                $lots = LotsArrest::find()->joinWith('torgs')->where('torgs."trgExpireDate" >= NOW()')->limit($limit)->offset($offset)->all();
            
                $search = [
                    '${lotTitle}', 
                    '${lotAddress}', 
                    '${lotStatus}', 
                    '${trgFullName}',
                    '${trgHeadOrg}',
                    '${trgEtpName}',
                    '${trgBidFormName}',
                    '${trgLotCount}', 
                    '${lotCategory}',
                    '${lotStartPrice}',
                    '${lotPriceStep}',
                    '${lotMinPrice}',
                    '${lotDepositSize}',
                    '${trgPublished}',
                    '${trgExpireDate}',
                    '${trgStartDateRequest}',
                    '${trgOpeningDate}'
                ];
                
                foreach ($lots as $lot){
                    $replace = [
                        str_replace('"',"'",$lot->lotTitle),
                        str_replace('"',"'",$lot->lotKladrLocationName),
                        str_replace('"',"'",$lotStatus),
                        str_replace('"',"'",$lot->torgs->trgFullName),
                        str_replace('"',"'",$lot->torgs->trgHeadOrg),
                        str_replace('"',"'",$lot->torgs->trgEtpName),
                        $lot->torgs->trgBidFormName,
                        $lot->torgs->trgLotCount, 
                        $lot->lotPropKindName,
                        Yii::$app->formatter->asCurrency($lot->lotStartPrice),
                        Yii::$app->formatter->asCurrency($lot->lotPriceStep),
                        Yii::$app->formatter->asCurrency($lot->lotMinPrice),
                        Yii::$app->formatter->asCurrency($lot->lotDepositSize),
                        Yii::$app->formatter->asDate($lot->torgs->trgPublished, 'long'),
                        Yii::$app->formatter->asDate($lot->torgs->trgExpireDate, 'long'),
                        Yii::$app->formatter->asDate($lot->torgs->trgStartDateRequest, 'long'),
                        Yii::$app->formatter->asDate($lot->torgs->trgOpeningDate, 'long')
                    ];

                    $urls[] = [
                        Yii::$app->urlManager->createUrl($lot->lotUrl), 
                        'daily', 
                        'title' => str_replace($search, $replace, $metaData->mdTitle),
                        'type' => 'lots-arrest'
                    ];
                }
                break;
            case 'arbtr':
                    // Арбитражники
                    if (!$param['limit']) {
                        return ['error' => 'не задан limit'];
                    }

                    $arbitrs = Arbitrs::find()->limit($limit)->offset($offset)->all();

                    $metaData = MetaDate::find()->where(['mdName' => "arbitr-page"])->one();

                    $search  = [
                        '${arbitrName}',
                        '${arbitrAddress}',
                        '${sroName}',
                        '${regNumber}',
                        '${arbitrInn}',
                        '${arbitrOgrn}',
                        '${countCase}',
                        '${countLot}'
                    ];
                    
                    foreach ($arbitrs as $arbitr){

                        $replace = [
                            $arbitr->person->lname.' '.$arbitr->person->fname.' '.$arbitr->person->mname,
                            $arbitr->postaddress,
                            str_replace('"',"'",$arbitr->sro->title),
                            $arbitr->regnum,
                            $arbitr->person->inn,
                            $countCases,
                            count($lots_bankrupt)
                        ];
                        
                        $urls[] = [
                            Yii::$app->urlManager->createUrl('arbitrazhnye-upravlyayushchie/'.$arbitr->id),
                            'weekly',
                            'title' => str_replace($search, $replace, $metaData->mdTitle),
                            'type' => 'arbitr'
                        ];
                    }
                break;
            case 'bnkr':
                    // Должники
                    if (!$param['limit']) {
                        return ['error' => 'не задан limit'];
                    }

                    $bankrupts = Bankrupts::find()->limit($limit)->offset($offset)->all();

                    $metaData = MetaDate::find()->where(['mdName' => "doljnik-page"])->one();

                    $search  = [
                        '${bnkrName}',
                        '${bnkrAddress}',
                        '${bnkrInn}'
                    ];
                    
                    foreach ($bankrupts as $bankrupt){

                        switch ($bankrupt->bankrupttype) {
                            case 'Organization':
                                    $name = $bankrupt->company->shortname;
                                    $address = $bankrupt->company->legaladdress;
                                    $inn = $bankrupt->company->inn;
                                break;
                            case 'Person':
                                    $name = $bankrupt->person->lname.' '.$bankrupt->person->fname.' '.$bankrupt->person->mname;
                                    $address = $bankrupt->person->address;
                                    $inn = $bankrupt->person->inn;
                                break;
                        }

                        $replace = [
                            $name,
                            $address,
                            $inn
                        ];
                        
                        $urls[] = [
                            Yii::$app->urlManager->createUrl('dolzhniki/'.$bankrupt->id),
                            'weekly',
                            'title' => str_replace($search, $replace, $metaData->mdTitle),
                            'type' => 'doljnik'
                        ];
                    }
                break;
            case 'sro':
                    // СРО
                    if (!$param['limit']) {
                        return ['error' => 'не задан limit'];
                    }

                    $sros = Sro::find()->limit($limit)->offset($offset)->all();

                    $metaData = MetaDate::find()->where(['mdName' => "sro-page"])->one();

                    $search  = [
                        '${sroName}',
                        '${sroAddress}',
                        '${regNumber}',
                        '${sroInn}',
                        '${sroOgrn}',
                        '${arbitrCount}',
                    ];
                    
                    foreach ($sros as $sro){

                        $arbitrCount = Arbitrs::find()->joinWith(['sro','person'])->where(['sro.id'=>$sro->id])->count();

                        $replace = [
                            $sro->title,
                            $sro->address,
                            $sro->regnum,
                            $sro->inn,
                            $sro->ogrn,
                            $arbitrCount
                        ];
                        
                        $urls[] = [
                            Yii::$app->urlManager->createUrl('sro/'.$sro->id),
                            'weekly',
                            'title' => ($metaData->mdTitle)? str_replace($search, $replace, $metaData->mdTitle) : $sro->title,
                            'type' => 'sro'
                        ];
                    }
                break;
            default:
                    return ['error' => 'нет параметра type'];
                break;
        }
        
        return $urls;
    }

    //Формирует XML файл, возвращает в виде переменной
    public function getXml($urls)
    {
        $host = Yii::$app->request->hostInfo; // домен сайта    
        ob_start();  

        $xmlFile = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>'.$host.'></loc><changefreq>daily</changefreq><priority>1</priority></url>'; 

        foreach ($urls as $url) {
            $xmlFile .= '<url><loc>'.$host.$url[0].'</loc><changefreq>'.$url[1].'</changefreq></url>';
        }    
        
        return $xmlFile.'</urlset>';
    }

    //Возвращает XML файл
    public function showXml($xml_sitemap)
    {
        // устанавливаем формат отдачи контента        
        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;  
        //повторно т.к. может не сработать
        header("Content-type: text/xml");
        echo $xml_sitemap;
        Yii::$app->end(); 
    }    
}
