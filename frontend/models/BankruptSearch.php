<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Bankrupt Search
 */
class BankruptSearch extends Model
{
    public $search;
    public $type;
    


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search', 'type'], 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function search($bankrupt)
    {
        $where = ['and'];
        $whereAnd = [];

        if (!empty($this->type)) {
            switch ($this->type) {
                case 'company':
                        $whereAnd = ['typeId' => 1];
                    break;
                case 'person':
                        $whereAnd = ['typeId' => 2];
                    break;
            }
        }

        $where[] = 'name is not null and name != \'-\'';

        if (!empty($this->search)) {
            $where[] = [
                'or',
                'to_tsvector("name") @@ plainto_tsquery(\''.$this->search.'\')',
                ['like', 'LOWER("inn")', mb_strtolower($this->search, 'UTF-8')],
            ];
            $sort = 'ts_rank(to_tsvector("name"), plainto_tsquery(\''.$this->search.'\')) ASC,';
        } else {
            $sort = 'name ASC';
        }

        return $bankrupt->where($where)->andWhere($whereAnd)->orderBy($sort);
    }
}
 