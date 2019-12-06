<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

use frontend\components\about\AboutCounter;
use frontend\components\about\AboutDescription;
use frontend\components\site\OurFeatures;
use frontend\components\about\AboutNavigation;
use frontend\components\about\AboutTeams;
use frontend\components\contact\ContactFindSociety;



$this->title = 'О нас';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="container pt-100">
        <div class="section-title text-center w-100">
	    	<!-- <h2 class="font-weight-7">
                О <span class="text-lowercase">нас</span>
            </h2> -->
            <h1 class="h2 text-center pb-10 pt-50">Единый информатор агрегирует всю информацию по имуществу организаций</h1>
	    		<!-- <p>Бесплатный доступ к информации о торгах в любом уголке России</p> -->
	    </div>
        <?=AboutDescription::widget()?>
        
        <?=AboutCounter::widget()?>

        <div class="section-title text-center w-100">
		    <h2>
                Делаем <span class="text-lowercase">всё чтобы</span> Вам <span class="text-lowercase">было удобно</span>
            </h2>
	    </div>
        <?=OurFeatures::widget()?>

        <div class="mt-10 mb-50 text-center">
            <a href="#loginFormTabInModal-register" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false" class="btn-link">
                Регистрируйтесь на ei.ru
            </a>
        </div>

        <!-- <div class="section-title text-center w-100">
		    <h2>
            Делаем<span class="text-lowercase"> всё чтобы </span>Вам <span class="text-lowercase">было удобно!</span>
            </h2>
	    </div>
        <=AboutNavigation::widget()?>

        <div class="section-title text-center w-100">
		    <h2>
                Наша<span class="text-lowercase"> команда</span>
            </h2>
			<p>Люди, которые делают мир лучше</p>
	    </div> -->
        <!-- <=AboutTeams::widget()?> -->
        
        <!-- <=ContactFindSociety::widget()?> -->

    </div>

    

</div>
