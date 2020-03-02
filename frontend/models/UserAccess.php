<?php
namespace frontend\models;

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
    
    public function forManager($page = null, $access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'admin' || Yii::$app->user->identity->role == 'manager') {

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
    
    public function forAgent($page = null, $access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'agent') {

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
    
    public function forArbitr($page = null, $access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'arbitr') {

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