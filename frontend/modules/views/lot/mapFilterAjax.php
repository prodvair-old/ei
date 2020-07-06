<?php
use common\models\db\Lot;
use sergmoro1\lookup\models\Lookup;
use yii\widgets\Breadcrumbs;
use frontend\modules\models\Category;
use common\models\db\Torg;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\db\Owner;
use common\models\db\Etp;

$lotsSubcategory[ 0 ] = 'Все подкатегории';
$subcategoryCheck = true;


if ($model->mainCategory) {
    $subCategories = Category::findOne(['id' => $model->mainCategory]);
    $leaves = $subCategories->leaves()->all();
    $leaves = ArrayHelper::map($leaves, 'id', 'name');
    $lotsSubcategory += $leaves;
    $subcategoryCheck = false;
}
$traderList = [];

?>

