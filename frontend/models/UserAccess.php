<?php
namespace frontend\models;

use common\models\db\Arbitrator;
use common\models\db\Lot;
use common\models\db\User;
use Yii;
use \yii\base\Module;

class UserAccess extends Module
{
    public function getRole($role = null)
    {
        if (!$role) {
            $role = Yii::$app->user->identity->role;
        }
        
        switch ($role) {
            case 'agent':
                return 'Агент';
                break;
            case 'arbitr':
                return 'Арбитражный управляющи';
                break;
            case 'sro':
                return 'СРО';
                break;
            case 'etp':
                return 'Торговая площадка';
                break;
            case 'manager':
                return 'Менеджер';
                break;
            case 'admin':
                return 'Администратор';
                break;
            case 'superAdmin':
                return 'Главный администратор';
                break;
        }
    }
    public function forSuperAdmin($access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            if ($access != null) {
                return Yii::$app->user->identity->access[$access];
            } else {
                return true;
            }
        }
        
        return false;
    }
    public function forAdmin($page = null, $access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'admin') {

            if ($page != null) {
                if ($access != null) {
                    return Yii::$app->user->identity->access[$page][$access];
                }
                return Yii::$app->user->identity->access[$page]['status'];
            } else {
                return true;
            }

        }

        return false;
    }
    
    public static function forManager()
    {
        if (Yii::$app->user->identity->role == User::ROLE_MANAGER) {
            return true;
        }

        return false;
    }
    
    public static function forAgent(Lot $lot)
    {
        if (
            Yii::$app->user->identity->role == User::ROLE_AGENT
            && $lot->torg->torgPledge->user_id === Yii::$app->user->identity->getId()
        ) {
            return true;
        }
        
        return false;
    }
    
    public static function forArbitr(Lot $lot)
    {
        if($lot->torg->debtor->manager_id) {
            return ($lot->torg->debtor->manager_id == Arbitrator::getManagerIdBy(Yii::$app->user->identity->getId()));
        }

        return false;
    }
    
    public function forSro($page = null, $access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'sro') {

            if ($page != null) {
                if ($access != null) {
                    return Yii::$app->user->identity->access[$page][$access];
                }
                return Yii::$app->user->identity->access[$page]['status'];
            } else {
                return true;
            }

        }
        
        return false;
    }
    
    public function forEtp($page = null, $access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'etp') {

            if ($page != null) {
                if ($access != null) {
                    return Yii::$app->user->identity->access[$page][$access];
                }
                return Yii::$app->user->identity->access[$page]['status'];
            } else {
                return true;
            }

        }
        
        return false;
	}
}