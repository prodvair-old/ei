<?
use yii\helpers\Url;

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['arbitr/list'])?>/<?=$arbitr->id?>">
                   
            <figcaption class="content">
                <h5><?=$arbitr->person->lname.' '.$arbitr->person->fname.' '.$arbitr->person->mname?></h5>
                <ul class="item-meta">
                    <li>
                        <i class="elegent-icon-pin_alt text-warning"></i> <?=$arbitr->postaddress?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>