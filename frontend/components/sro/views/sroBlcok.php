<?
use yii\helpers\Url;

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['sro/list'])?>/<?=$sro->id?>">
                   
            <figcaption class="content">
                <h5><?=$sro->title?></h5>
                <ul class="item-meta">
                    <li>
                        ИНН <?=$sro->inn?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>