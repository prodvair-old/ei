<?php
namespace common\models;

use Yii;
use yii\base\Model;

class SendSMS extends Model
{ 
  const TOKEN = '8EEEBF95-6CCB-5936-F951-ED0AA3E73C7A';

  private $totalCost;
  private $totalSMS;
  private $balance;
  
  public $phone;
  public $message;

  public function rules()
  {
      return [
        [
          ['phone'], 'number'
        ],
        [
          ['message'], 'string'
        ],
        [
          ['phone', 'message'], 'required'
        ],
      ];
  }

  public function send()
  {
    if (!$this->validate()) {
      return ['status' => false, 'code' => 1, 'text' => 'Не прошла валидацию'];
    }
    
    $ch = curl_init("https://sms.ru/sms/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        "api_id" => self::TOKEN,
        "to" => $this->phone, // До 100 штук до раз
        "msg" => $this->message, // Если приходят крякозябры, то уберите iconv и оставьте только "Привет!",
        /*
        // Если вы хотите отправлять разные тексты на разные номера, воспользуйтесь этим кодом. В этом случае to и msg нужно убрать.
        "multi" => array( // до 100 штук за раз
            "79093094384"=> iconv("windows-1251", "utf-8", "Привет 1"), // Если приходят крякозябры, то уберите iconv и оставьте только "Привет!",
            "74993221627"=> iconv("windows-1251", "utf-8", "Привет 2") 
        ),
        */
        "json" => 1 // Для получения более развернутого ответа от сервера
    )));
    $body = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($body);
    if ($json) { // Получен ответ от сервера
        if ($json->status == "OK") { // Запрос выполнился
            // foreach ($json->sms as $phone => $data) { // Перебираем массив СМС сообщений
            //     if ($data->status == "OK") { // Сообщение отправлено
            //         // echo "Сообщение на номер $phone успешно отправлено. ";
            //         // echo "ID сообщения: $data->sms_id. ";
            //         // echo "";
            //     } else { // Ошибка в отправке
            //         // echo "Сообщение на номер $phone не отправлено. ";
            //         // echo "Код ошибки: $data->status_code. ";
            //         // echo "Текст ошибки: $data->status_text. ";
            //         // echo "";
            //     }
            // }
            // echo "Баланс после отправки: $json->balance руб.";
            // echo "";
            return ['status' => true, 'code' => $json->status_code, 'text' => 'Успешно', 'totalCost' => $this->totalCost, 'totalSMS' => $this->totalSMS];
        } else { // Запрос не выполнился (возможно ошибка авторизации, параметрах, итд...)
            // echo "Запрос не выполнился. ";      
            // echo "Код ошибки: $json->status_code. ";
            // echo "Текст ошибки: $json->status_text. ";
          return ['status' => false, 'code' => $json->status_code, 'text' => $json->status_text];
        }
    } else { 
        // echo "Запрос не выполнился. Не удалось установить связь с сервером. ";
        return ['status' => false, 'code' => 0, 'text' => 'Нет связи'];
    }
  }

  public function check()
  {
    if (!$this->totalCost) {
      $totalCost = $this->totalCost();
      if (!$totalCost['status']) {
        return false;
      }
    }
    if (!$this->balance) {
      $balance = $this->balance();
      if (!$balance['status']) {
        return false;
      }
    }

    if ($this->totalCost <= $this->balance) {
      return true;
    }
    return false;
  }

  public function balance()
  {
    $ch = curl_init("https://sms.ru/my/balance");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
      "api_id" => self::TOKEN,
      "json" => 1 // Для получения более развернутого ответа от сервера
    ));
    $body = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($body);
    if ($json) { // Получен ответ от сервера
      if ($json->status == "OK") { // Запрос выполнился
          $this->balance = $json->balance;
          // Ваш баланс: $json->balance 
          return ['status' => true, 'code' => $json->status_code, 'text' => 'Успешно', 'balance' => $this->balance];
        } else { // Запрос не выполнился (возможно ошибка авторизации, параметрах, итд...)
        // Код ошибки: $json->status_code Текст ошибки: $json->status_text
        return ['status' => false, 'code' => $json->status_code, 'text' => $json->status_text];
      }
    } else { // Запрос не выполнился Не удалось установить связь с сервером
      return ['status' => false, 'code' => 0, 'text' => 'Нет связи'];

    }
  }

  public function totalCost()
  {
    if (!$this->validate()) {
      return ['status' => false, 'code' => 1, 'text' => 'Не прошла валидацию'];
    }

    $ch = curl_init("https://sms.ru/sms/cost");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        "api_id" => self::TOKEN,
        "to" => $this->phone,
        "msg" => $this->message, // Если приходят крякозябры, то уберите iconv и оставьте только "Привет!",
        "json" => 1 // Для получения более развернутого ответа от сервера
    ));
    $body = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($body);
    if ($json) { // Получен ответ от сервера
        if ($json->status == "OK") { // Запрос выполнился
            // foreach ($json->sms as $phone => $data) { // Перебираем массив СМС сообщений
            //     if ($data->status == "OK") { // Сообщение обработано
            //         // Номер: $phone
            //         // Стоимость: $data->cost
            //         // Длина в СМС: $data->sms
            //     } else { // Ошибка в отправке
            //         // Номер: $phone
            //         // Код ошибки: $data->status_code
            //         // Текст ошибки: $data->status_text 
            //     }
            // }

            $this->totalCost = $json->total_cost;
            $this->totalSMS  = $json->total_sms;
            // Общая стоимость: $json->total_cost
            // Общая длина СМС: $json->total_sms
          return ['status' => true, 'code' => $json->status_code, 'text' => 'Успешно', 'totalCost' => $this->totalCost, 'totalSMS' => $this->totalSMS];
        } else { // Запрос не выполнился (возможно ошибка авторизации, параметрах, итд...)
            // Код ошибки: $json->status_code
            // Текст ошибки: $json->status_text
          return ['status' => false, 'code' => $json->status_code, 'text' => $json->status_text];
        }
    } else { // Запрос не выполнился Не удалось установить связь с сервером
          return ['status' => false, 'code' => 0, 'text' => 'Нет связи'];
    }
  }
}
