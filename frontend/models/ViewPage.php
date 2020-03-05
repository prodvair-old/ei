<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Query\PageViews;
/**
 * Sort Lot
 */
class ViewPage extends Model
{
    public $page_type;
    public $page_id;
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_type', 'page_id'], 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function check()
    {
        if (!$this->validate()) {
            return ['error', 'нет параметров'];
        }

        $viewer = new PageViews();

        $viewer->page_type = $this->page_type;
        $viewer->page_id = $this->page_id;

        if (!Yii::$app->user->isGuest) {
            $viewer->user_id = Yii::$app->user->id;
            $viewer->ip_address = $this->getIp();
            $viewer->save();
        } else {
            $viewer->ip_address = $this->getIp();
            $viewer->save();
        }

        return PageViews::find()->where(['page_type'=> $this->page_type, 'page_id' => $this->page_id])->count();
        
    }
    public function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
 