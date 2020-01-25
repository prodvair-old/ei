<?php

use yii\helpers\Html;

use common\models\Query\Lot\Parser;

$parser = Parser::findOne($id);
?>
<p>
    <h3>Ошибка парсинга №<?=$parser->id?></h3>
    <p>
        Статус: <b><?=$parser->status?>.</b><br>
        Из таблицы: <b><?=$parser->tableNameFrom?></b> с ID <b><?=$parser->tableIdFrom?>.</b><br>
        В таблицу: <b><?=$parser->tableNameTo?>.</b><br>
        Дата и время ошибки: <b><?=Yii::$app->formatter->asDatetime($parser->createdAt, 'short')?></b>
    </p>
    <br>
    
    <h4>Текст ошибки:</h4>
    <p>
        ========================<br>
        <?= Html::encode($parser->message)?><br>
        ========================
    </p>

    <br>
    <h4>JSON ошибки:</h4>
    ========================<br>
    <pre>
        <?print_r($parser->messageJson);?>
    </pre>
    ========================

    <p>© <?=Yii::$app->name?></p>
</p>