<?

use common\models\db\Bankrupt;
use yii\helpers\Url;

/* @var $bankrupt Bankrupt */

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['/bankrupt'])?>/<?=$bankrupt->id?>">
                   
            <figcaption class="content">
                <h5><?=$bankrupt->fullName?></h5>
                <ul class="item-meta">
                    <li>
                        <span class="font500">Тип:</span> <?=($bankrupt->agent == Bankrupt::AGENT_ORGANIZATION)? 'Юр. лицо' : 'Физ. лицо'?>
                    </li>
                    <li>
                        <span class="font500">ИНН</span> <?=$bankrupt->inn?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>