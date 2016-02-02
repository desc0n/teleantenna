<div class="col-sm-3 left-nav">

<!--полноразмерное меню-->

	<div class="hidden-xs">
		<?foreach(Model::factory('Product')->getProductGroup(1) as $group_1_data){?>
			<div class="slide-trigger">
				<div class="panel-heading collapsed">
					<h4 class="panel-title">    
						<a class="catalog-link" href="/catalog/?group_1=<?=$group_1_data['id'];?>"><?=$group_1_data['name'];?> <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
					</h4>
				</div>
				<div class="panel-collapse collapse slidepnl">
					<ul class="list-group">
						<?foreach(Model::factory('Product')->getProductGroup(2, $group_1_data['id']) as $group_2_data){?>
						<div class="sub-trigger">
							<li class="list-group-item"><a href="#"><?=$group_2_data['name'];?></a></li>
								<div class="panel-collapse collapse subslidepnl">
									<ul class="list-group">
										<?foreach(Model::factory('Product')->getProductGroup(3, $group_2_data['id']) as $group_3_data){?>
										<li class="list-group-item"><a href="#"><?=$group_3_data['name'];?></a></li>
										<?}?>
									</ul>
								</div>
						</div>
						<?}?>
					</ul>
				</div>
			</div>
		<?}?>
	</div>
</div>
<div class="col-sm-9 main-content">
	<table class="table table-hover table-bordered table-striped">
		<thead>
			<tr>
				<th class="col-sm-1 col-code">Код</th>
				<th class="col-sm-1 text-center col-img"></th>
				<th>Наименовение</th>
				<th class="col-sm-1 text-center col-price">Цена</th>
				<th class="col-sm-2 text-center col-num" colspan="<?=count(Model::factory('Shop')->getShop());?>">Наличие в магазинах (шт.)</th>
				<th class="col-sm-1 col-cart"></th>
			</tr>
		</thead>
		<tbody>
		<?foreach(Model::factory('Product')->getProductList(Array('group_1' => Arr::get($get,'group_1',0), 'group_2' => Arr::get($get,'group_2',0), 'group_3' => Arr::get($get,'group_3',0))) as $product_data){?><a href="catalog/katalog/sputnikovoe-oborudovanie/antennyi/svec-0,6.html">
		<tr>
			<td><?=$product_data['id'];?></td>
			<td>
				<?$product_data['product_img'] = $product_data['product_img'] != '' ? $product_data['product_img'] : 'nopic.jpg';?>
				<div class="img-link pull-left" data-toggle="tooltip" data-placement="left" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$product_data['product_img'];?>' style='width:200px;'>">
					<img class="img-thumbnail" src="/public/img/thumb/<?=$product_data['product_img'];?>">
				</div>
			</td>
			<td>
				<div>
					<a href="catalog/katalog/sputnikovoe-oborudovanie/antennyi/svec-0,6.html"><?=$product_data['name'];?></a>
				</div>
				<div>Краткое описание</div>
				<div class="t-rating">
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
				</div>
			</td>
			<td class="t-price"><?=$product_data['price'];?>р.</td>
			<?if(count($shop_info = Model::factory('Product')->getProductNum($product_data['id']))>0){foreach($shop_info as $shop_data){?>
			<td class="t-price t-num">
				<a class="shop-link" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?=Arr::get($shop_data, 'address', '');?> (<?=Arr::get($shop_data, 'num', '');?> шт.)">
					<?=Arr::get($shop_data, 'short_name', '');?>
					<p><?=Arr::get($shop_data, 'num', 0);?></p>
				</a>
			</td>
			<?}}else{?>
			<td class="t-price t-num"></td>
			<?}?>
			<td class="t-cart">
				<button type="button" class="btn btn-default cart-add">купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
			</td>
		</tr>
		<?}?>
	</tbody>
	</table>
</div>