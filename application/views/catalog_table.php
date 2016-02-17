<?
/** @var Model_Shop $shopModel */
$shopModel = Model::factory('Shop');

/** @var Model_Users $userModel */
$userModel = Model::factory('Users');

/** @var Model_Product $productModel */
$productModel = Model::factory('Product');

$countShop = count($shopModel->getShop());
$userProfile = Auth::instance()->logged_in() ? $userModel->getUsersProfile(Auth::instance()->get_user()->id) : [];
$userDiscount = !empty($userProfile) ? ($userProfile[0]['contractor'] == 1 ? $userProfile[0]['discount'] : 0) : 0;
?>
<table class="table table-hover table-bordered table-striped catalog-table">
	<thead>
		<tr>
			<th class="col-sm-1 col-xs-1 col-md-1 col-code">Код</th>
			<th class="col-sm-1 col-xs-1 col-md-1 text-center col-img"></th>
			<th>Наименование</th>
			<th class="col-sm-1 col-xs-1 col-md-1 text-center col-price">Цена</th>
			<th class="col-sm-2 col-xs-2 col-md-2 text-center col-num" colspan="<?=$countShop;?>">Наличие в магазинах (шт.)</th>
			<th class="col-sm-1 col-xs-1 col-md-1 col-cart"></th>
		</tr>
	</thead>
	<tbody>
	<?
	$emptyNumProducts = [];
	$brand_name = '';
	$group_1_name = '';
	$group_2_name = '';

	foreach($productsArr as $product_data){
		if($product_data['check_status'] == 0){
			$shop_info = $productModel->getProductNum($product_data['id'], 0, true);

			$checkNum = false;

			foreach($shop_info as $shop_data) {
				$num = Arr::get($shop_data, 'num', 0);

				if ($num > 0) {
					$checkNum = true;
				}
			}

			if (!$checkNum) {
				$emptyNumProducts[] = $product_data;

				continue;
			}

			if($group_2_name != $product_data['group_2_name']){
		?>
	<tr>
		<td class="group-name" colspan="<?=($countShop + 5);?>">
			<?=$product_data['group_2_name'];?>
		</td>
	</tr>
		<?
				$group_2_name = $product_data['group_2_name'];
			}

			if($brand_name != $product_data['brand_name'] && !empty($product_data['brand_name'])){
		?>
	<tr>
		<td class="brand-name" colspan="<?=($countShop + 5);?>">
			<?=$product_data['brand_name'];?>
		</td>
	</tr>
		<?
				$brand_name = $product_data['brand_name'];
			}
	?>
	<tr>
		<td onclick="document.location='/item/product/<?=$product_data['id'];?>';"><?=$product_data['code'];?></td>
		<td>
			<?$product_data['product_img'] = $product_data['product_img'] != '' ? $product_data['product_img'] : 'nopic.jpg';?>
			<div class="img-link pull-left" data-toggle="tooltip" data-placement="left" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$product_data['product_img'];?>' style='width:200px;'>">
				<img class="img-thumbnail" src="/public/img/thumb/<?=$product_data['product_img'];?>">
			</div>
		</td>
		<td onclick="document.location='/item/product/<?=$product_data['id'];?>';" class="catalog-name-col">
			<div>
				<a href="/item/product/<?=$product_data['id'];?>"><?=$product_data['name'];?></a>
			</div>
			<div><?=$product_data['short_description'];?></div>
			<div class="t-rating">
				<span class="glyphicon glyphicon-star"></span>
				<span class="glyphicon glyphicon-star"></span>
				<span class="glyphicon glyphicon-star"></span>
				<span class="glyphicon glyphicon-star-empty"></span>
				<span class="glyphicon glyphicon-star-empty"></span>
			</div>
		</td>
		<td class="t-price"><?=round(($product_data['price'] * (1 - $userDiscount / 100)), 0);?>р.</td>
		<?
		if(count($shop_info)>0) {
			foreach($shop_info as $shop_data){
				$num = Arr::get($shop_data, 'num', 0);
				?>
		<td class="t-price t-num">
			<a class="shop-link" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?=Arr::get($shop_data, 'address', '');?> (<?=$num;?> шт.)">
				<?=Arr::get($shop_data, 'short_name', '');?>
				<p><?=($num < 0 ? 0 : $num);?></p>
			</a>
		</td>
		<?}}else{?>
		<td class="t-price t-num"></td>
		<?}?>
		<td class="t-cart">
			<button type="button" id="addCartButton_<?=$product_data['id'];?>" class="btn btn-default cart-add" value="<?=$product_data['id'];?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
			<a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=$product_data['id'];?>" class="btn btn-success cart-in" value="<?=$product_data['id'];?>">Корзина <span class="glyphicon glyphicon-log-out"></span></a>
		</td>
	</tr>
		<?
		}
	}

	if (0 !== count($emptyNumProducts)) {
		?>
	<tr>
		<td class="empty-num-title" colspan="<?=($countShop + 5);?>">
			Товары под заказ
		</td>
	</tr>
		<?
	}

	$brand_name = '';
	$group_1_name = '';
	$group_2_name = '';

	foreach($emptyNumProducts as $product_data){
		if($product_data['check_status'] == 0){
			if($group_2_name != $product_data['group_2_name']){
				?>
				<tr>
					<td class="empty-group-name" colspan="<?=($countShop + 5);?>">
						<?=$product_data['group_2_name'];?>
					</td>
				</tr>
				<?
				$group_2_name = $product_data['group_2_name'];
			}

			if($brand_name != $product_data['brand_name'] && !empty($product_data['brand_name'])){
				?>
				<tr>
					<td class="brand-name" colspan="<?=($countShop + 5);?>">
						<?=$product_data['brand_name'];?>
					</td>
				</tr>
				<?
				$brand_name = $product_data['brand_name'];
			}
			?>
			<tr>
				<td onclick="document.location='/item/product/<?=$product_data['id'];?>';"><?=$product_data['code'];?></td>
				<td>
					<?$product_data['product_img'] = $product_data['product_img'] != '' ? $product_data['product_img'] : 'nopic.jpg';?>
					<div class="img-link pull-left" data-toggle="tooltip" data-placement="left" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$product_data['product_img'];?>' style='width:200px;'>">
						<img class="img-thumbnail" src="/public/img/thumb/<?=$product_data['product_img'];?>">
					</div>
				</td>
				<td onclick="document.location='/item/product/<?=$product_data['id'];?>';" class="catalog-name-col">
					<div>
						<a href="/item/product/<?=$product_data['id'];?>"><?=$product_data['name'];?></a>
					</div>
					<div><?=$product_data['short_description'];?></div>
					<div class="t-rating">
						<span class="glyphicon glyphicon-star"></span>
						<span class="glyphicon glyphicon-star"></span>
						<span class="glyphicon glyphicon-star"></span>
						<span class="glyphicon glyphicon-star-empty"></span>
						<span class="glyphicon glyphicon-star-empty"></span>
					</div>
				</td>
				<td class="t-price"><?=round(($product_data['price'] * (1 - $userDiscount / 100)), 0);?>р.</td>
				<td class="t-price t-num" colspan="<?=$countShop;?>">
					Под заказ
				</td>
				<td class="t-cart">
					<button type="button" id="addCartButton_<?=$product_data['id'];?>" class="btn btn-default cart-add" value="<?=$product_data['id'];?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
					<a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=$product_data['id'];?>" class="btn btn-success cart-in" value="<?=$product_data['id'];?>">Корзина <span class="glyphicon glyphicon-log-out"></span></a>
				</td>
			</tr>
			<?
		}
	}?>
</tbody>
</table>