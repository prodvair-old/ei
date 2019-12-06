<?php
namespace frontend\models\zalog;

use Yii;
use yii\base\Model;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;
/**
 * Filter Lots
 */
class FilterLots extends Model
{
    public $status;
    public $category;
    public $sortBy;
    public $search;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'category', 'sortBy', 'search'], 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function search($lots)
    {
        // if (!$this->validate()) {
        //     return null;
        // }

        $where = ['and'];
        $where[] = ['contactPersonId' => Yii::$app->user->id];
        $whereAnd = ['and'];
        
        if (!empty($this->category) && $this->category != 'all' && $this->category != '0') {
            
            $category = LotsCategory::findOne($this->category);

            $orWhere = ['or'];
            foreach ($category->zalog_categorys as $key => $value) {
                $orWhere[] = ['categorys.categoryId'=>$key];
            }
            $where[] = $orWhere;

        }

        if (!empty($this->search)) {
            $where[] = [
                'or',
                ['like', 'LOWER(description)', mb_strtolower($this->search, 'UTF-8')],
                ['like', 'LOWER(title)', mb_strtolower($this->search, 'UTF-8')],
                ['like', 'LOWER(address)', mb_strtolower($this->search, 'UTF-8')],
            ];
        }
        if (!empty($this->status)) {
            if ($this->status != 'all' && $this->status) {
                $where[] = ['status' => true];
            } else if ($this->status != 'all' && !$this->status) {
                $where[] = ['status' => false];
            }
        }

        if (!empty($this->sortBy)) {
            switch ($this->sortBy) {
                case 'new':
                        $sort = '"createdAt" DESC';
                    break;
                case 'old':
                        $sort = '"createdAt" ASC';
                    break;
                case 'images':
                        $sort = '"images" ASC';
                    break;
                default:
                        $sort = '"createdAt" DESC';
                    break;
            }
        }

        return $lots->where($where)->orderBy($sort);
    }
}