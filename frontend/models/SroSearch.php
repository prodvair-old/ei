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
            $search = explode(' ',$this->search);
            $whereSearch = ['or'];
            foreach ($search as $value) {
                $whereSearch[] = ['like', 'LOWER("title")', mb_strtolower($value, 'UTF-8')];
                $whereSearch[] = ['like', 'LOWER("inn")', mb_strtolower($value, 'UTF-8')];
            }
            $where[] = $whereSearch;
        }

        return $sro->andWhere($where);
    }
}
 