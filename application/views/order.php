<div class="row">
	<h2 class="sub-header col-sm-12">Заказ № <?=$order_id;?></h2>
	<div class="col-sm-11">
		<legend><h3 class="col-sm-12">Контактные данные</h3></legend>
		<table>
			<tr>
				<td class="col-sm-3 col-xs-3 col-md-3"><h4>Способ доставки:</h4></td>
				<td><strong><?=(Arr::get($orderDeliveryInfo, 'delivery_type', 0) == 0 ? 'Самовывоз' : 'Курьером');?></strong></td>
			</tr>
			<tr>
				<td class="col-sm-3 col-xs-3 col-md-3"><h4>Магазин:</h4></td>
				<td><strong><?=Arr::get($orderDeliveryInfo, 'shop_name', '');?></strong></td>
			</tr>
			<tr>
				<td class="col-sm-3 col-xs-3 col-md-3"><h4>Имя клиента:</h4></td>
				<td><strong><?=Arr::get($orderDeliveryInfo, 'name', '');?></strong></td>
			</tr>
			<tr>
				<td class="col-sm-3 col-xs-3 col-md-3"><h4>Телефон клиента:</h4></td>
				<td><strong><?=Arr::get($orderDeliveryInfo, 'phone', '');?></strong></td>
			</tr>
			<tr>
				<td class="col-sm-3 col-xs-3 col-md-3"><h4>E-mail клиента:</h4></td>
				<td><strong><?=Arr::get($orderDeliveryInfo, 'email', '');?></strong></td>
			</tr>
		</table>
		<div <?=(Arr::get($orderDeliveryInfo, 'delivery_type', 0) == 0 ? 'style="display:none;"' : '');?>>
			<legend><h3 class="col-sm-12">Данные для доставки</h3></legend>
			<table>
				<tr>
					<td class="col-sm-3 col-xs-3 col-md-3"><h4>Улица:</h4></td>
					<td><strong><?=Arr::get($orderDeliveryInfo, 'street', '');?></strong></td>
				</tr>
				<tr>
					<td class="col-sm-3 col-xs-3 col-md-3"><h4>Дом:</h4></td>
					<td><strong><?=Arr::get($orderDeliveryInfo, 'house', '');?></strong></td>
				</tr>
				<tr>
					<td class="col-sm-3 col-xs-3 col-md-3"><h4>Квартира:</h4></td>
					<td><strong><?=Arr::get($orderDeliveryInfo, 'flat', '');?></strong></td>
				</tr>
				<tr>
					<td class="col-sm-3 col-xs-3 col-md-3"><h4>Комментарий:</h4></td>
					<td><strong><?=Arr::get($orderDeliveryInfo, 'comment', '');?></strong></td>
				</tr>
			</table>
		</div>
		<table class="table table-hover table-bordered table-striped realization-table">
			<thead>
				<tr>
					<th class="col-sm-1 col-xs-1 col-md-1 col-code">Код</th>
					<th>Наименование</th>
					<th class="col-sm-1 col-xs-1 col-md-1 text-center col-price">Цена</th>
					<th class="col-sm-1 col-xs-1 col-md-1 text-center col-num" colspan="">Кол-во (шт.)</th>
					<th class="col-sm-1 col-xs-1 col-md-1 text-center col-num" colspan="">Наличие (шт.)</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 1;
				foreach ($orderData as $data){
					?>
				<tr>
					<td>
						<?=$data['product_code'];?>
					</td>
					<td class='text-left'>
						<?=$data['product_name'];?>
					</td>
					<td>
						<?=$data['price'];?>
					</td>
					<td>
						<?=$data['num'];?>
					</td>
					<td>
						<?=$data['root_num'];?>
					</td>
				</tr>
					<?
					$i++;
				}
				?>
			</tbody>
		</table>
		<?if (Arr::get(Arr::get($orderData, 0, []), 'order_status', 3) != 6) {?>
		<form method='post' class='pull-left'>
			<input type='hidden' name="orderId" id='orderId' value='<?=$order_id;?>'>
			<button class='btn btn-success' name='createRealization' value='<?=$order_id;?>'>Создать реализацию</button>
		</form>
		<form method='post' class='col-sm-3 col-xs-3 col-md-3 pull-left'>
			<input type='hidden' name="orderId" id='orderId' value='<?=$order_id;?>'>
			<button class='btn btn-danger' name='canceledOrder' value='<?=$order_id;?>'>Отменить реализацию</button>
		</form>
		<?}?>
	</div>
</div>
