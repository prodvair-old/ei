<?

use common\models\db\Manager;
use yii\helpers\Url;

/* @var $arbitr Manager */

?>
<div class="col">

    <figure class="tour-grid-item-01 box-shadow borr-10">

        <a href="<?= Url::to(['/arbitr']) ?>/<?= $arbitr->id ?>">

            <figcaption class="content">
                <h5><?= $arbitr->profileRel->fullName ?>
                    <?php if ($arbitr->arbitrator) : ?>
                        <span class="elegent-icon-check_alt2" data-toggle="tooltip"
                              title="Арбитражный управляющий верифицирован"></span>
                    <?php endif; ?>
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