<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * SRO Search
 */
class SroSearch extends Model
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
    public function search($sro)
    {
        $where = ['and'];
        $whereAnd = ['and'];

        if (!empty($this->search)) {
            $where[] = [
                'or',
                'to_tsvector("title") @@ plainto_tsquery(\''.$this->search.'\')',
                ['like', 'LOWER("inn")', mb_strtolower($this->search, 'UTF-8')],
            ];
            $sort = 'ts_rank(to_tsvector("title"), plainto_tsquery(\''.$this->search.'\')) ASC,';
        } else {
            $sort = 'title ASC';
        }

        return $sro->andWhere($where)->orderBy($sort);
    }
}
 