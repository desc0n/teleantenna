<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shop-content">
	<?foreach($shopArr as $shopData){?>
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 main-page-shop cursor-pointer" data-id="<?=$shopData['id'];?>">
			<h3><?=$shopData['name'];?></h3>
            <div class="row">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <?=(!empty($shopData['img']) ? '<img class="shop-img img-thumbnail" src="/public/img/shopes/'.$shopData['id'].'_'.$shopData['img'].'" alt="'.$shopData['name'].'">' : '');?>
                </div>
                <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
                    <div class="row">
                        <?=(!empty($shopData['address']) ? '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12"><p><strong>Адрес: </strong>'.$shopData['address'].'</p></div>' : '');?>
                        <?=(!empty($shopData['info']) ? '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12"><p><strong>Информация: </strong>'.$shopData['info'].'</p></div>' : '');?>
                    </div>
                </div>
		    </div>
		</div>
	</div>
	<?}?>
</div>