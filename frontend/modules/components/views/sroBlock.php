<?

use common\models\db\Sro;
use yii\helpers\Url;

/* @var $sro Sro */

?>
<div class="col">
										
    <figure class="tour-grid-item-01 box-shadow borr-10">

        <a href="<?=Url::to(['/sro'])?>/<?= $sro->organizationRel->parent_id?>">
                   
            <figcaption class="content">
                <div class="lot__block__info__content__offer"><?=$sro->place->region->name?></div>

                <h5><?=$sro->organizationRel->title?></h5>
                <ul class="item-meta mt-10">
                    <li>
                        ИНН <?=$sro->organizationRel->inn?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>