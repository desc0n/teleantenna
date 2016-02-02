<div class="row">
	<h2 class="sub-header col-sm-12">Редактирование товаров:</h2>
	<div class="col-sm-11">
		<div class="col-sm-10 search-block">
			<form class="form-inline" role="form" action="/admin/redactproductsnum" method="post">
				<div class="col-sm-9 input-group">
					<input type="text" class="form-control search" name="redact_search" placeholder="Поиск по названию или каталожному номеру" style="border: 1px solid #ddd;">
					<span class="input-group-btn"><button type="submit" class="btn btn-default search" style="border: 1px solid #ddd;"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</form>
		</div>
	</div>
	<?if($redact_search){?>
	<h2 class="sub-header col-sm-12">Найденные позиции:</h2>
	<div class="col-sm-11">
		<table class="table">
			<tr>
				<th class="col-sm-1 col-code">Код</th>
				<th>Название</th>
			</tr>
			<?foreach($search_arr as $search_data){?>
			<tr>
				<td><?=$search_data['id'];?></td>
				<td class="text-left"><a href="/admin/redactproductsnum/?id=<?=$search_data['id'];?>"><?=$search_data['name'];?></a></td>
			</tr>
			<?}?>
		</table>
	</div>
	<?}?>
	<?if($product_id != ''){?>
	<h2 class="sub-header col-sm-12">Наличие товара (<b><?=Arr::get($product_info,'name','');?></b>):</h2>
	<div class="col-sm-11 redact-form">
		<table class="table redact-num-table">
			<tr>
				<th class="col-sm-3">Название магазина</th>
				<th>
					Наличие
				</th>
			</tr>
		<?foreach(Model::factory('Shop')->getShop() as $shop_data){?>
			<tr>
				<td><?=$shop_data['name'];?></td>
				<td>
					<form class="form-inline" role="form" action="/admin/redactproductsnum/?id=<?=$product_id;?>" method="post">
						<div class="col-sm-4 input-group">
								<input type="text" class="form-control search" name="num" placeholder="Наличие" style="border: 1px solid #ddd;" value="<?=Arr::get(Arr::get(Arr::get($product_info, 'num',Array()),$shop_data['id'],Array()),'num',0);?>">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-default search" style="border: 1px solid #ddd;" name="redactnum" value="1">
										<span class="glyphicon glyphicon-ok"></span>
									</button>
								</span>
						</div>
						<input type="hidden" name="shop" value="<?=$shop_data['id'];?>">
						<input type="hidden" name="redactproduct" value="<?=$product_id;?>">
					</form>
				</td>
			</tr>
			<?}?>
		</table>
	</div>
	<?}?>
</div>