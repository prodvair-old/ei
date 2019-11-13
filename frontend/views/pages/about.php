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
            <h1 class="text-center pb-10 pt-50"><?= Html::encode($this->title) ?></h1>
	    		<p>Бесплатный доступ к информации о торгах в любом уголке России</p>
	    </div>
        <?=AboutDescription::widget()?>
        
        <?=AboutCounter::widget()?>

        <div class="section-title text-center w-100">
		    <h2>
                Почему<span class="text-lowercase"> выбирают нас</span>
            </h2>
			<p>Вот что отличает нас от остальных</p>
	    </div>
        <?=OurFeatures::widget()?>

        <div class="section-title text-center w-100">
		    <h2>
                Поиск<span class="text-lowercase"> лотов</span>
            </h2>
			<!-- <p>Вот что отличает нас от остальных</p> -->
	    </div>
        <?=AboutNavigation::widget()?>

        <div class="section-title text-center w-100">
		    <h2>
                Наша<span class="text-lowercase"> команда</span>
            </h2>
			<p>Люди, которые делают мир лучше</p>
	    </div>
        <?=AboutTeams::widget()?>
        
        <?=ContactFindSociety::widget()?>

    </div>

    

</div>
