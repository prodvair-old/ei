<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

use common\models\Query\WishList;

/**
 * WishList Lot
 */
class WishListEdit extends Model
{
    public $type;
    public $lotId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lotId', 'type'], 'required'],
        ];
    }

    /**
     * wishEdit
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function wishEdit()
    {
        if ($wishListSearch = WishList::find()->where(['userId' => Yii::$app->user->id, 'lotId' => $this->lotId, 'type' => $this->type])->one()) {

            return ['del' =>$wishListSearch->delete()];

        } else {

            $wishList = new WishList();

            $wishList->type = $this->type;
            $wishList->lotId = $this->lotId;
            $wishList->userId = Yii::$app->user->id;

            return ['add' =>$wishList->save()];

        }
    }
}
 