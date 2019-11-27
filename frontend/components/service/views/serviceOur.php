<?php

use yii\helpers\Url;

?>
<div class="row">

	<!-- <div class="col-lg-4 mt-40"> 
		<div class="featured-image-item-08">
			<div class="image">
				<a href="services/specialist" class="image-inner">
					<img src="\img\services\1.svg" class="service-img__item"/>
				</a>
			</div>

			<div class="content mt-20">
				<h5>Консультация специалиста по лоту</h5>
				<p>
					Участие в торгах через Агента — максимум преимуществ, безопасность от приобретения мусорных лотов, отклонения заявки, отказа в регистрации на бирже, потери денег из-за неверно выбранной тактики и других «подводных камней».
				</p>
				<a href="<?=Url::to(['services/specialist'])?>" class="h6 text-primary">Показать полностью<i class="elegent-icon-arrow_right"></i></a>
			</div>
		</div>
	</div> -->

	<div class="col-lg-6 mt-40">
		<div class="featured-image-item-08">
			<a href="services/agent" class="image-inner">
				<img src="\img\services\2.svg" class="service-img__item"/>
			</a>
		</div>
		
		<div class="content mt-20">
			<!-- <div class="icon-font text-primary"></div> -->
			<h5>Услуги агента по торгам</h5>
			<p>
				Вам не нужно покупать электронную подпись — Агент использует собственную, которую принимают на всех торговых площадках;
			</p>
			<a href="<?=Url::to(['services/agent'])?>" class="h6 text-primary">Показать полностью<i class="elegent-icon-arrow_right"></i></a>
		</div>
	</div>

	<div class="col-lg-6 mt-40">
		<div class="featured-image-item-08">
			<div class="image">
				<a href="services/ecp" class="image-inner">
					<img src="\img\services\3.svg" class="service-img__item"/>
				</a>
			</div>
			<div class="content mt-20">
				<!-- <div class="icon-font text-primary"></div> -->
				<h5>Покупка ЭЦП</h5>
				<p>
					Решили участвовать в торгах самостоятельно? Тогда Вам понадобится цифровая подпись. Мы поможем Вам ее приобрести
				</p>
				<a href="<?=Url::to(['services/ecp'])?>" class="h6 text-primary">Показать полностью<i class="elegent-icon-arrow_right"></i></a>
			</div>
		</div>
	</div>

</div>