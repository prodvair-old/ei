<?php
namespace backend\models;

use Yii;
use \yii\base\Module;

class UserAccess extends Module
{
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
    public function forAdmin($access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'admin') {

            if ($access != null) {
                return Yii::$app->user->identity->access[$access];
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
    
    public function forAgent($access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'agent') {

            if ($access != null) {
                return Yii::$app->user->identity->access[$access];
            } else {
                return true;
            }

        }
        
        return false;
    }
    
    public function forArbitr($access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'arbitr') {

            if ($access != null) {
                return Yii::$app->user->identity->access[$access];
            } else {
                return true;
            }

        }
        
        return false;
    }
    
    public function forSro($access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'sro') {

            if ($access != null) {
                return Yii::$app->user->identity->access[$access];
            } else {
                return true;
            }

        }
        
        return false;
    }
    
    public function forEtp($access = null)
    {
        if (Yii::$app->user->identity->role == 'superAdmin') {
            return true;
        } else if (Yii::$app->user->identity->role == 'etp') {

            if ($access != null) {
                return Yii::$app->user->identity->access[$access];
            } else {
                return true;
            }

        }
        
        return false;
	}
}