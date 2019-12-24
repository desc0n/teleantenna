<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shop-content">
	<?foreach($shopArr as $shopData){?>
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 main-page-shop">
			<h3><?=$shopData['name'];?></h3>
            <div class="row">
                <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
                    <div class="row">
                        <?=(!empty($shopData['address']) ? '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12"><strong>'.$shopData['address'].'</strong></div>' : '');?>
                        <?=(!empty($shopData['info']) ? '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">'.$shopData['info'].'</div>' : '');?>
                    </div>
                </div>
		    </div>
		</div>
	</div>
	<?}?>
    <div class="shops-map">
        <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A4a231ba0b46b32a48cb274da147b3c057b7fb3e8599a3e84db5bf6cc1d64db93&width=100%&height=450&lang=ru_RU&scroll=true"></script>
    </div>
</div>