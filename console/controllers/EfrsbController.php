<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Получение и парсинг данных из ЕФРСБ
 * EFRSB Controller
 */
class EfrsbController extends Controller
{
  private $client;
  private $messageIds;

  public function actionGetMessage()
  {
    $this->_client();

    if ($this->getMessageIds(5)->status) {
      foreach ($this->messageIds as $msgId) {
        var_dump($this->getMessageContent($msgId));
      }
    }
      
  }

  /** 
   * Получениия данных сообщения по ID сообщения
   * 
   * @var integer $msgId
   */
  private function getMessageContent($msgId)
  {
    try {
      return (object) ['status' => true, 'content' => $this->client->GetMessageContent(["id"=>$msgId])];
    } catch (\Throwable $th) {
      return (object) ['status' => false, 'message' => $th->getMessage()];
    }
  }

  /** 
   * Получение спискаов ID сообщения 
   * за период до сегодняшнего дня
   * 
   * @var integer $days
   */
  private function getMessageIds($days = 10)
  {
    $endDate = date("Y-m-d");
    $startDate = date("Y-m-d", strtotime("-$days days"));

    try {
      $this->messageIds = $this->client->GetMessageIds(["startDate"=>$startDate."T00:00:00","endDate"=>$endDate."T00:00:00"])->GetMessageIdsResult->int;
      return (object) ['status' => true];
    } catch (\Throwable $th) {
      return (object) ['status' => false, 'message' => $th->getMessage()];
    }
  }

  /** 
   * Подключение к клиенту API ЕФРСБ
   * 
   * Настройки подключения находятся в главном конфиге
   */
  private function _client()
  { 
    try {
      $this->client = Yii::$app->efrsbAPI;
      return (object) ['status' => true];
    } catch (\Throwable $th) {
      return (object) ['status' => false, 'message' => $th->getMessage()];
    }
  }
}

