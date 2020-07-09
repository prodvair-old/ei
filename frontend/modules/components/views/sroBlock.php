<?

use common\models\db\Sro;
use yii\helpers\Url;

/* @var $sro Sro */

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['/sro'])?>/<?= $sro->organizationRel->parent_id?>">
                   
            <figcaption class="content">
                <h5><?=$sro->organizationRel->title?></h5>
                <ul class="item-meta">
                    <li>
                        ИНН <?=$sro->organizationRel->inn?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>