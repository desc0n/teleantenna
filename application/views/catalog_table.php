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
$productsMobileArr = [];
?>
<table class="table table-hover table-bordered table-striped catalog-table hidden-xs">
	<thead>
	<tr>
		<th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 col-code">Код</th>
		<th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 text-center col-img">Фото</th>
		<th class="col-sm-4 col-xs-4 col-md-6 col-lg-6">Наименование</th>
		<th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 text-center col-price">Цена</th>
		<th class="col-sm-2 col-xs-2 col-md-2 col-lg-2 text-center col-num" colspan="<?=$countShop;?>">Наличие в магазинах (шт.)</th>
		<th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 col-cart"></th>
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
			$shop_info = $productModel->getProductNum($product_data['id'], 0, false);
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

			if($group_2_name != $product_data['group_2_name']){?>
				<tr>
					<td class="group-name" colspan="<?=($countShop + 5);?>" data-group-id="<?=$product_data['group_2'];?>" onclick="changGroupVisibility(<?=$product_data['group_2'];?>);">
						<?=$product_data['group_2_name'];?>
					</td>
				</tr>
				<?
				$group_2_name = $product_data['group_2_name'];
			}

			if($brand_name != $product_data['brand_name'] && !empty($product_data['brand_name'])){?>
				<tr>
					<td class="brand-name" colspan="<?=($countShop + 5);?>">
						<?=$product_data['brand_name'];?>
					</td>
				</tr>
				<?
				$brand_name = $product_data['brand_name'];
			}
			?>
			<tr class="group-row" data-group="<?=$product_data['group_2'];?>">
				<td onclick="document.location='/item/product/<?=$product_data['id'];?>';"><?=$product_data['code'];?></td>
				<td class="img-cell">
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
<!--						<span class="glyphicon glyphicon-star"></span>-->
<!--						<span class="glyphicon glyphicon-star"></span>-->
<!--						<span class="glyphicon glyphicon-star"></span>-->
<!--						<span class="glyphicon glyphicon-star-empty"></span>-->
<!--						<span class="glyphicon glyphicon-star-empty"></span>-->
					</div>
				</td>
				<td class="t-price"><?=round(($product_data['price'] * (1 - $userDiscount / 100)), 0);?>р.</td>
				<?if(count($shop_info)>0) {
					foreach($shop_info as $shop_data){
						$num = Arr::get($shop_data, 'num', 0);?>
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
            $productsMobileArr[] = $product_data;
		}
	}

	if (0 !== count($emptyNumProducts)) {?>
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
					<td class="empty-group-name" colspan="<?=($countShop + 5);?>" onclick="changEmptyGroupVisibility(<?=$product_data['group_2'];?>);">
						<?=$product_data['group_2_name'];?>
					</td>
				</tr>
				<?
				$group_2_name = $product_data['group_2_name'];
			}

			if($brand_name != $product_data['brand_name'] && !empty($product_data['brand_name'])){?>
				<tr>
					<td class="brand-name" colspan="<?=($countShop + 5);?>">
						<?=$product_data['brand_name'];?>
					</td>
				</tr>
				<?
                $brand_name = $product_data['brand_name'];
			}
			?>
			<tr class="empty-group-row" data-group="<?=$product_data['group_2'];?>">
				<td onclick="document.location='/item/product/<?=$product_data['id'];?>';"><?=$product_data['code'];?></td>
				<td class="img-cell">
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
<!--						<span class="glyphicon glyphicon-star"></span>-->
<!--						<span class="glyphicon glyphicon-star"></span>-->
<!--						<span class="glyphicon glyphicon-star"></span>-->
<!--						<span class="glyphicon glyphicon-star-empty"></span>-->
<!--						<span class="glyphicon glyphicon-star-empty"></span>-->
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
<div class="visible-xs">
    <?
    $productsChunkArr = array_chunk($productsMobileArr, 2);
    $emptyNumProducts = [];
    $brandName = '';
    $group1Name = '';
    $group2Name = '';

    foreach($productsChunkArr as $productChunkArr){?>
        <div class="row mobile-items">
        <?foreach($productChunkArr as $key => $productData){
            $shopInfo = $productModel->getProductNum($productData['id'], 0, false);
            $checkNum = false;

            foreach($shopInfo as $shopData) {
                $num = Arr::get($shopData, 'num', 0);

                if ($num > 0) {
                    $checkNum = true;
                }
            }

            if($group2Name !== $productData['group_2_name']){?>
                    <div class="col-xs-12 group-name" data-group-id="<?=$productData['group_2'];?>" onclick="changeMobileGroupVisibility(<?=$productData['group_2'];?>);">
                        <?=$productData['group_2_name'];?>
                    </div>
                <?
                $group2Name = $productData['group_2_name'];
            }

            if($brandName != $productData['brand_name'] && !empty($productData['brand_name'])){?>
                <div class="col-xs-12">
                    <?=$productData['brand_name'];?>
                </div>
                <?
                $brandName = $productData['brand_name'];
            }
            ?>
            <div class="col-xs-6 mobile-item mobile-item-<?=$key;?>-child" data-group="<?=$productData['group_2'];?>">
                <div class="mobile-item-img">
                    <table class="table">
                        <tr>
                            <td class="mobile-img-link img-link thumbnail">
                                <img class="mobile-img-thumbnail img-thumbnail" src="/public/img/thumb/<?=$productData['product_img'];?>">
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mobile-item-name">
                    <a href="/item/product/<?=$productData['id'];?>"><?=$productData['name'];?></a>
                </div>
                <div class="mobile-item-quantity">
                    <?=($checkNum ? 'В наличии' : 'Под заказ');?>
                </div>
                <div class="mobile-item-price">
                    Цена: <?=round(($product_data['price'] * (1 - $userDiscount / 100)), 0);?> р.
                </div>
                <div class="mobile-item-cart-action">
                    <button type="button" id="mobileAddCartButton_<?=$product_data['id'];?>" class="btn btn-default btn-lg mobile-cart-add" value="<?=$product_data['id'];?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
                </div>
            </div>
            <?
        }?>
        </div>
    <?}
?>
</div>