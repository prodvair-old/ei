<?

use common\models\db\Manager;
use yii\helpers\Url;

/* @var $arbitr Manager */

?>
<div class="col">

    <figure class="tour-grid-item-01 box-shadow borr-10 <?= ($arbitr->arbitrator)? 'bg-green' : ''?>">

        <a href="<?= Url::to(['/arbitr']) ?>/<?= $arbitr->id ?>">

            <figcaption class="content">
                <div class="lot__block__info__content__offer"><?=$arbitr->place->region->name?></div>
                <h5>
                    <?php if ($arbitr->arbitrator) : ?>
                        <span class="elegent-icon-check_alt2 text-green" data-toggle="tooltip"
                              title="Арбитражный управляющий верифицирован"></span>
                    <?php endif; ?>
                    <?= $arbitr->profileRel->fullName ?>
                </h5>

                <ul class="item-meta mt-10">
                    <li>
                        <i class="elegent-icon-pin_alt text-warning"></i> <?= $arbitr->placeRel->address ?>
                    </li>
                </ul>
            </figcaption>

        </a>

    </figure>

</div>