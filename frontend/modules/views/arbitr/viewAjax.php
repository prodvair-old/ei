<?php

use common\models\db\Manager;
use yii\helpers\Url;

/* @var $model Manager */


if (count($model) > 0) : ?>
    <?php foreach ($model as $arbitr) : ?>
        <div class="col">

            <figure class="tour-grid-item-01">

                <a href="<?= Url::to(['/arbitr']) ?>/<?= $arbitr->id ?>">

                    <figcaption class="content">
                        <h5><?= $arbitr->fullName ?></h5>
                        <ul class="item-meta">
                            <li>
                                <i class="elegent-icon-pin_alt text-warning"></i> <?= $arbitr->address ?>
                            </li>
                        </ul>
                    </figcaption>

                </a>

            </figure>

        </div>
    <?php endforeach; ?>

<?php endif; ?>