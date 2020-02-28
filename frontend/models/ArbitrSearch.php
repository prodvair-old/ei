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
        $sort = '';

        $where[] = ['typeId' => 1];

        $where[] = '"fullName" is not null and "fullName" != \'-\'';

        if (!empty($this->search)) {
            $where[] = [
                'or',
                'to_tsvector("fullName") @@ plainto_tsquery(\''.$this->search.'\')',
                ['like', 'LOWER("inn")', mb_strtolower($this->search, 'UTF-8')],
            ];
            $sort = 'ts_rank(to_tsvector("fullName"), plainto_tsquery(\''.$this->search.'\')) ASC,';
        } else {
            $sort = 'fullName ASC';
        }
        

        return $arbitrs->where($where)->orderBy($sort);
    }
}
 