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
                    return ['lot.title' => SORT_ASC];
                break;
            case 'nameDESC':
                    return ['lot.title' => SORT_DESC];
                break;
            case 'dateASC':
                    return ['torg.publishedDate' => SORT_ASC];
                break;
            case 'dateDESC':
                    return ['torg.publishedDate' => SORT_DESC];
                break;
            case 'priceASC':
                    return ['lot.startPrice' => SORT_ASC];
                break;
            case 'priceDESC':
                    return ['lot.startPrice' => SORT_DESC];
                break;
            default:
                    return ['lot.images' => SORT_DESC, 'torg.publishedDate' => SORT_DESC];
                break;
        }
    }
}
 