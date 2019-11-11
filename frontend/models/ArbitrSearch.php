<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Arbitr Search
 */
class ArbitrSearch extends Model
{
    public $search;
    


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['search', 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function search($arbitrs)
    {
        $where = ['and'];
        $whereAnd = ['and'];

        if (!empty($this->search)) {
            $search = explode(' ',$this->search);
            $whereSearch = ['or'];
            foreach ($search as $value) {
                $whereSearch[] = ['like', 'LOWER("arb_prsn"."lname")', mb_strtolower($value, 'UTF-8')];
                $whereSearch[] = ['like', 'LOWER("arb_prsn"."fname")', mb_strtolower($value, 'UTF-8')];
                $whereSearch[] = ['like', 'LOWER("arb_prsn"."mname")', mb_strtolower($value, 'UTF-8')];
            }
            $where[] = $whereSearch;
        }

        return $arbitrs->where($where);
    }
}
 