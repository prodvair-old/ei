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
                        $bankrupt->joinWith('company')->orderBy('bnkr_cmpn.shortname ASC');
                        $whereAnd = ['bankrupttype' => 'Organization'];
                    break;
                case 'person':
                        $bankrupt->joinWith('person')->orderBy('bnkr_prsn.lname ASC, bnkr_prsn.fname ASC, bnkr_prsn.mname ASC');
                        $whereAnd = ['bankrupttype' => 'Person'];
                    break;
            }
        }

        if (!empty($this->search)) {
            $search = explode(' ',$this->search);
            $whereSearch = ['or'];
            if (!empty($this->type)) {
                switch ($this->type) {
                    case 'company':
                            foreach ($search as $value) {
                                $whereSearch[] = ['like', 'LOWER("bnkr_cmpn"."shortname")', mb_strtolower($value, 'UTF-8')];
                                $whereSearch[] = ['like', 'LOWER("bnkr_cmpn"."fullname")', mb_strtolower($value, 'UTF-8')];
                            }
                        break;
                    case 'person':
                            foreach ($search as $value) {
                                $whereSearch[] = ['like', 'LOWER("bnkr_prsn"."lname")', mb_strtolower($value, 'UTF-8')];
                                $whereSearch[] = ['like', 'LOWER("bnkr_prsn"."fname")', mb_strtolower($value, 'UTF-8')];
                                $whereSearch[] = ['like', 'LOWER("bnkr_prsn"."mname")', mb_strtolower($value, 'UTF-8')];
                            }
                        break;
                }
            }
            $where[] = $whereSearch;
        }

        return $bankrupt->where($where)->andWhere($whereAnd);
    }
}
 