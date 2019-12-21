<?$userProfile = Auth::instance()->logged_in() ? Model::factory('Users')->getUsersProfile(Auth::instance()->get_user()->id) : [];?>
<?$userDiscount = !empty($userProfile) ? $userProfile[0]['discount'] : 0;?>
<table class="table table-hover table-bordered table-striped catalog-table">
	<thead>
		<tr>
			<th class="col-sm-1 col-xs-1 col-md-1 col-code">Код</th>
			<th class="col-sm-1 col-xs-1 col-md-1 text-center col-img"></th>
			<th>Наименование</th>
			<th class="col-sm-1 col-xs-1 col-md-1 text-center col-price">Цена</th>
			<th class="col-sm-1 col-xs-1 col-md-1 col-cart"></th>
		</tr>
	</thead>
	<tbody>
	<?
	$brand_name = '';
	$group_1_name = '';
	$group_2_name = '';
	foreach($servicesArr as $service_data){
		if($service_data['check_status'] == 0){
			if($group_2_name != $service_data['group_2_name']){
		?>
	<tr>
		<td class="group-name" colspan="5">
			<?=$service_data['group_2_name'];?>
		</tr>
	</tr>
		<?
		$group_2_name = $service_data['group_2_name'];
	}
	if($brand_name != $service_data['brand_name'] && !empty($service_data['brand_name'])){
		?>
	<tr>
		<td class="brand-name" colspan="5">
			<?=$service_data['brand_name'];?>
		</tr>
	</tr>
		<?
		$brand_name = $service_data['brand_name'];
	}
	?>
	<tr>
		<td onclick="document.location='/item/service/<?=$service_data['id'];?>';"><?=$service_data['code'];?></td>
		<td>
			<?$service_data['service_img'] = $service_data['service_img'] != '' ? $service_data['service_img'] : 'nopic.jpg';?>
			<div class="img-link pull-left" data-toggle="tooltip" data-placement="left" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$service_data['service_img'];?>' style='width:200px;'>">
				<img class="img-thumbnail" src="/public/img/thumb/<?=$service_data['service_img'];?>">
			</div>
		</td>
		<td onclick="document.location='/item/service/<?=$service_data['id'];?>';" class="catalog-name-col">
			<div>
				<a href="/item/service/<?=$service_data['id'];?>"><?=$service_data['name'];?></a>
			</div>
			<div><?=$service_data['short_description'];?></div>
		</td>
		<td class="product-cell-price"><?=round(($service_data['price'] * (1 - $userDiscount / 100)), 0);?>р.</td>
		<td class="product-cell-cart">
			<button type="button" id="addCartButton_<?=$service_data['id'];?>" class="btn btn-default cart-add" value="<?=$service_data['id'];?>">Заказать <span class="glyphicon glyphicon-shopping-cart"></span></button>
			<a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=$service_data['id'];?>" class="btn btn-success cart-in" value="<?=$service_data['id'];?>">Корзина <span class="glyphicon glyphicon-log-out"></span></a>
		</td>
	</tr>
		<?
		}
	}?>
</tbody>
</table>