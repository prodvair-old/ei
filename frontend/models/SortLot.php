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
    public function sortBy($lots, $type = null)
    {
        if (!$this->validate()) {
            return null;
        }
        
        switch ($type) {
            case 'bankrupt':
                    $sort .= 'lot_image DESC, ';
                    switch ($this->sortBy) {
                        case 'nameASC':
                                $sort .= 'lot_description ASC';
                            break;
                        case 'nameDESC':
                                $sort .= 'lot_description DESC';
                            break;
                        case 'dateASC':
                                $sort .= 'lot_timepublication ASC';
                            break;
                        case 'dateDESC':
                                $sort .= 'lot_timepublication DESC';
                            break;
                        case 'priceASC':
                                $sort .= 'lot_startprice ASC';
                            break;
                        case 'priceDESC':
                                $sort .= 'lot_startprice DESC';
                            break;
                        default:
                                $sort .= 'lot_timepublication DESC';
                            break;
                    }
                break;
            case 'arrest':
                    $sort .= 'lot_image DESC, ';
                    switch ($this->sortBy) {
                        case 'nameASC':
                                $sort .= 'lot_description ASC';
                            break;
                        case 'nameDESC':
                                $sort .= 'lot_description DESC';
                            break;
                        case 'dateASC':
                                $sort .= 'lot_timepublication ASC';
                            break;
                        case 'dateDESC':
                                $sort .= 'lot_timepublication DESC';
                            break;
                        case 'priceASC':
                                $sort .= 'lot_startprice ASC';
                            break;
                        case 'priceDESC':
                                $sort .= 'lot_startprice DESC';
                            break;
                        default:
                                $sort .= 'lot_timepublication DESC';
                            break;
                    }
                break;
            default:
                return ['error' => 'Что то пошло не так :('];
                break;
        }
        return $lots->orderBy($sort);
    }
}
 