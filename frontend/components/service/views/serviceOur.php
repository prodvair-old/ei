<?php

use yii\helpers\Url;

?>
<div class="row">

	<div class="col-lg-4"> 
		<div class="featured-image-item-08">
			<div class="image">
				<div class="image-inner service-img">
					<img src="\img\services\1.svg"/>
				</div>
			</div>

			<div class="content mt-50">
				<!-- <div class="icon-font text-primary"></div> -->
				<h5>Консультация специалиста по лоту</h5>
				<p>
					Участие в торгах через Агента — максимум преимуществ, безопасность от приобретения мусорных лотов, отклонения заявки, отказа в регистрации на бирже, потери денег из-за неверно выбранной тактики и других «подводных камней».
				</p>
				<a href="<?=Url::to(['services/specialist'])?>" class="h6 text-primary">Показать полностью<i class="elegent-icon-arrow_right"></i></a>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="featured-image-item-08">
			<div class="image-inner service-img">
				<img src="\img\services\2.svg"/>
			</div>
		</div>
		
		<div class="content mt-50">
			<!-- <div class="icon-font text-primary"></div> -->
			<h5>Услуги агента по торгам</h5>
			<p>
				Вам не нужно покупать электронную подпись — Агент использует собственную, которую принимают на всех торговых площадках;
			</p>
			<a href="<?=Url::to(['services/agent'])?>" class="h6 text-primary">Показать полностью<i class="elegent-icon-arrow_right"></i></a>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="featured-image-item-08">
			<div class="image">
				<div class="image-inner service-img">
					<img src="\img\services\3.svg"/>
				</div>
			</div>
			<div class="content mt-50">
				<!-- <div class="icon-font text-primary"></div> -->
				<h5>Покупка ЭЦП для данной площадки</h5>
				<p>
					Для самостоятельного участия потребуется собрать полный пакет документов, основные из них конкретного аукциона.
				</p>
				<a href="<?=Url::to(['services/ecp'])?>" class="h6 text-primary">Показать полностью<i class="elegent-icon-arrow_right"></i></a>
			</div>
		</div>
	</div>

</div>