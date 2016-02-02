<?=$profile_menu;?>
<div class="col-sm-9 main-content prof-content">
	<div class="col-sm-12">
		<h3>Корзина</h3>
		<table class="table table-bordered cart-table">
			<thead>
			<tr>
				<th>Код</th>
				<th class="col-sm-1 text-center col-img">Фото</th>
				<th class="text-center">Наименование товара</th>
				<th class="col-sm-1 text-center">Цена</th>
				<th class="col-sm-2 text-center">Кол-во</th>
				<th class="col-sm-1 text-center">Всего</th>
				<th class="col-sm-1 text-center"></th>
			</tr>
			</thead>
			<tbody>
			<?
			$allPrice = 0;
			foreach(Model::factory('Cart')->getCart() as $cartData) {
				$allPrice += $cartData['price']*$cartData['num'];
				?>
			<tr id="tableRow_<?=$cartData['id'];?>">
				<td><?=$cartData['product_code'];?></td>
				<td>
					<div class="img-link pull-left" data-toggle="tooltip" data-placement="left" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$cartData['product_img'];?>' style='width:200px;'>">
						<img class="img-thumbnail" src="/public/img/thumb/<?=$cartData['product_img'];?>">
					</div>
				</td>
				<td class="text-left">
					<div>
						<a href="/item/product/<?=$cartData['product_id'];?>"><?=$cartData['product_name'];?></a>
					</div>
					<div><?=$cartData['product_short_description'];?></div>
				</td>
				<td>
					<h3>
						<span><?=$cartData['price'];?></span>
						<input type="hidden" class="position-price" id="positionPrice_<?=$cartData['id'];?>" value="<?=$cartData['price'];?>">
					</h3>
				</td>
				<td>
						<input class="form-control position-num" id="positionNum_<?=$cartData['id'];?>" type="text" value="<?=$cartData['num'];?>">
						<div class="btn-group">
							<button class="btn btn-success position-num-plus" value="<?=$cartData['id'];?>">+</button>
							<button class="btn btn-danger position-num-minus" value="<?=$cartData['id'];?>">-</button>
						</div>
				</td>
				<td>
					<h3>
						<span  id="positionSum_<?=$cartData['id'];?>"><?=$cartData['price']*$cartData['num'];?></span>
					</h3>
				</td>
				<td class="text-center">
					<button class="btn btn-primary removePosition" value="<?=$cartData['id'];?>">Удалить <span class="glyphicon glyphicon-remove"></span></button>
				</td>
			</tr>
			<?}?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="5" class="text-right text-muted">
					<strong>Общая сумма:</strong>
				</td>
				<td>
					<h3 class="text-primary" id="allPrice"><?=$allPrice;?></h3>
				</td>
				<td class="text-left">
					<button class="btn btn-primary removeAllPositions">Удалить всё</button>
				</td>
			</tr>
			</tfoot>
		</table>
    </div>
</div>