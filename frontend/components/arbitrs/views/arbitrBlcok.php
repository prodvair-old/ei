<?
use yii\helpers\Url;

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['arbitr/list'])?>/<?=$arbitr->id?>">
                   
            <figcaption class="content">
                <h5><?=$arbitr->fullName?></h5>
                <ul class="item-meta">
                    <li>
                        <i class="elegent-icon-pin_alt text-warning"></i> <?=$arbitr->address?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>