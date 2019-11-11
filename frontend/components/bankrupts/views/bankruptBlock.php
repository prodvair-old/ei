<?
use yii\helpers\Url;

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['doljnik/list'])?>/<?=$bankrupt->id?>">
                   
            <figcaption class="content">
                <h5><?=($bankrupt->bankrupttype == 'Organization')? $bankrupt->company->shortname : $bankrupt->person->lname.' '.$bankrupt->person->fname.' '.$bankrupt->person->mname?></h5>
                <ul class="item-meta">
                    <li>
                        <span class="font500">ИНН</span> <?=($bankrupt->bankrupttype == 'Organization')? $bankrupt->company->inn : $bankrupt->person->inn?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>