<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

use common\models\Query\Zalog\lotCategorys;
use common\models\Query\LotsCategory;


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
            foreach (lotCategorys::find()->where(['lotId'=>$this->lotId])->all() as $lotCategory) {
                $lotCategory->delete();
            }

            $category = LotsCategory::findOne((int)$this->categorys);

            foreach ($category->zalog_categorys as $key => $value) {
                foreach ($this->subCategorys as $id) {
                    if ($key == $id) {

                        $lotCategorys = new lotCategorys();

                        $lotCategorys->lotId                = $this->lotId;
                        $lotCategorys->categoryId           = $key;
                        $lotCategorys->categoryName         = $value['name'];
                        $lotCategorys->categoryTranslitName = $value['translit'];

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