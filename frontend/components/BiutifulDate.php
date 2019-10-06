<?php

namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;

class BiutifulDate extends Widget
{
    public $date;

    public function init(){
        parent::init();
        
            mb_http_input('UTF-8');
            mb_http_output('UTF-8');
            mb_internal_encoding("UTF-8");
            
            $this->text = (string)$this->text; // преобразуем в строковое значение
            $this->text = strip_tags($this->text); // убираем HTML-теги
            $this->text = str_replace(["\n", "\r"], " ", $this->text); // убираем перевод каретки
            $this->text = preg_replace("/\s+/", ' ', $this->text); // удаляем повторяющие пробелы
            $this->text = trim($this->text); // убираем пробелы в начале и конце строки
                        
            $this->text = function_exists('mb_strtolower') ? mb_strtolower($this->text) : strtolower($this->text); // переводим строку в нижний регистр (иногда надо задать локаль)
            $this->text = strtr($this->text, ['а' => 'a','б' => 'b','в' => 'v','г' => 'g','д' => 'd','е' => 'e','ё' => 'e','ж' => 'j','з' => 'z','и' => 'i','й' => 'y','к' => 'k',
                'л' => 'l','м' => 'm','н' => 'n','о' => 'o','п' => 'p','р' => 'r','с' => 's','т' => 't','у' => 'u','ф' => 'f','х' => 'h','ц' => 'c','ч' => 'ch','ш' => 'sh','щ' => 'shch',
                'ы' => 'y','э' => 'e','ю' => 'yu','я' => 'ya','ъ' => '','ь' => '']);
            $this->text = preg_replace("/[^0-9a-z-_ ]/i", "", $this->text); // очищаем строку от недопустимых символов
            $this->text = str_replace(" ", "-", $this->text); // заменяем пробелы знаком минус
    }

    public function run(){
        return Html::encode($this->text);
    }
}