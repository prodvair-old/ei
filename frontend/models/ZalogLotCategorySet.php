<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

use common\models\Query\LotsCategory;
use common\models\Query\Lot\LotCategorys;


class ZalogLotCategorySet extends Model
{ 
    public $categorys;
    public $subCategorys;
    public $lotId;

    public function rules()
    {
        return [
            [['categorys', 'subCategorys', 'lotId'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'categorys' => 'Категория',
            'subCategorys' => 'Под категория',
            'lotId' => 'ID лота',
        ];
    }

    public function setCategory()
    {
        if ($this->validate()) { 
            foreach (LotCategorys::find()->where(['lotId'=>$this->lotId])->all() as $lotCategory) {
                $lotCategory->delete();
            }

            $category = LotsCategory::findOne((int)$this->categorys);

            foreach ($category->zalog_categorys as $key => $value) {
                foreach ($this->subCategorys as $id) {
                    if ($key == $id) {

                        $lotCategorys = new LotCategorys();

                        $lotCategorys->lotId         = $this->lotId;
                        $lotCategorys->categoryId    = $key;
                        $lotCategorys->name          = $value['name'];
                        $lotCategorys->nameTranslit  = $value['translit'];

                        $lotCategorys->save();

                    }
                }
            }
            return true;
        } else {
            return false;
        }

    }
}