<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\about\AboutCounter;
use frontend\components\about\AboutDescription;
use frontend\components\site\OurFeatures;
use frontend\components\about\AboutTeams;
use frontend\components\contact\ContactFindSociety;



$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>
    <div class="container pt-100">
        <div class="section-title text-center w-100">
	    	<h2>
                <span><span>About</span> Us</span>
            </h2>
	    		<p>We are on the tour operator since since 2001</p>
	    </div>
        <?=AboutDescription::widget()?>
        
        <?=AboutCounter::widget()?>
        <div class="section-title text-center w-100">
		    <h2>
                <span><span>Our</span> features</span>
            </h2>
			<p>He doors quick child an point</p>
	    </div>
        <?=OurFeatures::widget()?>
        <div class="section-title text-center w-100">
		    <h2>
                <span><span>Our</span> Teams</span>
            </h2>
			<p>People who control making Tourperator run</p>
	    </div>
        <?=AboutTeams::widget()?>
        
        <?=ContactFindSociety::widget()?>

    </div>

    

</div>
