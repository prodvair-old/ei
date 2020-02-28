<?
use yii\helpers\Url;

?>
<div class="col">
										
    <figure class="tour-grid-item-01">

        <a href="<?=Url::to(['doljnik/list'])?>/<?=$bankrupt->id?>">
                   
            <figcaption class="content">
                <h5><?=$bankrupt->name?></h5>
                <ul class="item-meta">
                    <li>
                        <span class="font500">Тип</span> <?=($bankrupt->typeId == 1)? 'Юр. лицо' : 'Физ. лицо'?>
                    </li>
                    <li>
                        <span class="font500">ИНН</span> <?=$bankrupt->inn?>
                    </li>
                </ul>
            </figcaption>
        
        </a>
        
    </figure>
    
</div>