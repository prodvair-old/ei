<?php
namespace frontend\models;

use yii\base\Model;
/**
 * Sort Lot
 */
class SortLot extends Model
{
    public $sortBy;
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['sortBy', 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function sortBy()
    {
        switch ($this->sortBy) {
            case 'nameASC':
                    return 'lot.title ASC';
                break;
            case 'nameDESC':
                    return 'lot.title DESC';
                break;
            case 'dateASC':
                    return 'torg."publishedDate" ASC';
                break;
            case 'dateDESC':
                    return 'torg."publishedDate" DESC';
                break;
            case 'priceASC':
                    return 'lot.startPrice ASC';
                break;
            case 'priceDESC':
                    return 'lot.startPrice DESC';
                break;
            default:
                    return 'lot.images DESC, torg."publishedDate" DESC';
                break;
        }
    }
}
 