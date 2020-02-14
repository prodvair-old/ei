<?

use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

use common\models\Query\Zalog\OwnerProperty;

use frontend\components\LotBlockZalog;
use frontend\components\ProfileMenu;
use frontend\components\SearchForm;

$name = (\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname']) ? \Yii::$app->user->identity->info['firstname'] . ' ' . \Yii::$app->user->identity->info['lastname'] : \Yii::$app->user->identity->info['contacts']['emails'][0];
$this->title = "Получить банкротов – $name";
$this->params['breadcrumbs'][] = [
  'label' => 'Профиль',
  'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
  'url' => ['user/index']
];
$this->params['breadcrumbs'][] = [
  'label' => 'Получить банкротов',
  'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
  'url' => ['user/lots']
];
$this->registerJsVar('lotType', 'zalog', $position = yii\web\View::POS_HEAD);

$owner = OwnerProperty::findOne(Yii::$app->user->identity->ownerId);
?>

<section class="page-wrapper page-detail">

  <div class="page-title border-bottom pt-25 mb-0 border-bottom-0">

    <div class="container">

      <div class="row gap-15 align-items-center">

        <div class="col-12 col-md-7">

          <nav aria-label="breadcrumb">
            <?= Breadcrumbs::widget([
              'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
              'encodeLabels' => false,
              'tag' => 'ol',
              'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
              'homeLink' => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
          </nav>

        </div>

      </div>

    </div>

  </div>

  <div class="container pt-30">

    <div class="row gap-20 gap-lg-40">


      <div class="col-12 col-lg-3">

        <aside class="-kit sidebar-wrapper">

          <div class="bashboard-nav-box">

            <div class="box-heading">
              <h3 class="h6 text-white text-uppercase">Профиль</h3>
            </div>
            <div class="box-content">

              <div class="dashboard-avatar mb-10">

                <div class="image">
                  <img class="setting-image-tag" src="<?= (Yii::$app->user->identity->avatar) ? Yii::$app->user->identity->avatar : 'img/image-man/01.jpg' ?>" alt="Image" />
                </div>

                <div class="content">
                  <h6><?= $name ?></h6>
                  <p class="mb-15"><?= (\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname']) ? \Yii::$app->user->identity->info['contacts']['emails'][0] : '' ?></p>
                </div>

              </div>

              <?= ProfileMenu::widget(['page' => 'getbankrupt']) ?>

              <!-- <p class="font-sm mt-20">Your last logged-in: <span class="text-primary font700">4 hours ago</span></p> -->

            </div>

          </div>

        </aside>

      </div>

      <div class="col-12 col-lg-9">

        <div class="content-wrapper">

          <div class="form-draft-payment">

            <h3 class="heading-title"><span>Расширенный <span class="font200"> поиск имущества</span></span></h3>
                        
            <div class="clear"></div>

            <?php $form = ActiveForm::begin([ 'options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="row">
              <div class="custom-file col-lg-10">
                  <?= $form->field($modelImport, 'fileImport',['template' => '<div class="custom-file">{label}{hint}{input}{error}</div>'])->fileInput(['class' => 'custom-file-input'])->label('Загрузить файл',['class'=>'custom-file-label']) ?>
              </div>

              <div class="col-lg-2">
                <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']); ?>
              </div>
            </div>

            <script>
              <?=(Yii::$app->params['exelParseResult']['status'])? 'toastr.warning("'.Yii::$app->params['exelParseResult']['status'].'");' : ''?>
            </script>
            <div class="mb-30"></div>

            <div class="import-info d-flex">
              <img src="img/excel.png" alt="">
              <p>
                Скачайте и заполните файл примера своими данными. Загрузите в соответствующую форму.
                <br>После загрузки файла, система начнет поиск по заданным параметрам
              </p>
            </div>

            <style>
              .import-info img {
                width: 60px;
                height: 55px;
                margin-right: 1rem
              }
            </style>

            <?php ActiveForm::end(); ?>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</section>