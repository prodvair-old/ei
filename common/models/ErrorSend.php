<?php
namespace common\models;

use Yii;
use yii\base\Module;

class ErrorSend extends Module
{
    public function parser($id)
    {
        return Yii::$app
            ->mailer_support
            ->compose(
                ['html' => 'parserError-html'],
                ['id'=>$id]
            )
            ->setFrom(['support@ei.ru' => Yii::$app->name . ' robot'])
            ->setTo(Yii::$app->params['developmenEmail'])
            ->setSubject('Ошибка парсинга на сайте ei.ru')
            ->send();
    }
}