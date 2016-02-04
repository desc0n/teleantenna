<?=$profile_menu;?>
<div class="col-sm-9 main-content prof-content">
	<h1 class="orders-title">Корзина и заказы</h1>
	<div class="col-sm-12">
		<div class="row">
			<ul class="nav nav-tabs">
				<li <?=($action == 'cart' ? 'class="active"' : '');?>><a href="#cart-tab" data-toggle="tab">Корзина</a></li>
				<li <?=($action == 'list' ? 'class="active"' : '');?>><a href="#orders-tab" data-toggle="tab">Заказы</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane  <?=($action == 'cart' ? 'active' : '');?>" id="cart-tab">
					<div class="row-fluid">
						<h3>Оформление заказа</h3>
						<div>
							<div class="well well-white well-noshadow">
								<legend>Получение товара</legend>
								<div>
									<label>
										<input value="0" name="delivery-type" class="delivery-type" <?=(Arr::get($customerCartInfo, 'delivery_type', 0) == 0 ? 'checked="checked"' : '');?> type="radio">
										Самовывоз
									</label>
								</div>
								<div>
									<label>
										<input value="1" name="delivery-type" class="delivery-type" <?=(Arr::get($customerCartInfo, 'delivery_type', 0) == 1 ? 'checked="checked"' : '');?> type="radio">
										Доставка курьером
									</label>
								</div>
							</div>
							<div class="well well-white well-noshadow">
								<legend>Самовывоз <span class="color-red">*</span></legend>
								<?
								$i = 0;
								foreach(Model::factory('Shop')->getShop() as $shopData){
									$checked = count($cartInfo) > 0 ? ($shopData['id'] == $cartInfo[0]['shop_id'] ? 'checked="checked"' : ($i == 0 ? 'checked="checked"' : '')) : ($i == 0 ? 'checked="checked"' : '');
									$i++;
									?>
								<div>
									<label>
										<input <?//=$checked;?> type="radio" name="cart-shop" class="cart-shop" value="<?=$shopData['id'];?>">
										<span><?=$shopData['name'];?> (<?=$shopData['address'];?>)</span>
									</label>
								</div>
								<?}?>
							</div>
							<div class="well well-white well-noshadow" id="deliveryTypeForm1" <?=(Arr::get($customerCartInfo, 'delivery_type', 0) == 1 ? 'style="display: none;"' : '');?>>
								<legend>Контактная информация</legend>
								<div>
									<div>
										<div class="col-sm-4 col-xs-4 col-md-4 quest-client-order-form">
											<label>Телефон <span class="color-red">*</span> :</label>
											<div class="input-group">
												<span class="input-group-addon">+7</span>
												<input class="form-control cart-customer-field" id="shortCustomerPhone" type="text" placeholder="Телефон" value="<?=Arr::get($customerCartInfo, 'phone', '');?>">
											</div>
										</div>
										<div class=" col-sm-4 col-xs-4 col-md-4 quest-client-order-form">
											<label>Адрес эл. почты</label>
											<input class="form-control cart-customer-field" id="shortCustomerMail" placeholder="Адрес эл. почты" type="text" value="<?=Arr::get($customerCartInfo, 'email', '');?>">
										</div>
									</div>
									<div class="quest-client-message">
										<p>Необходимо вводить федеральный номер сотового телефона в формате <nobr>(+7 9XX XXX XXXX)</nobr>, чтобы отправить SMS-уведомление о статусе заказа.</p>
										<p>Адрес эл. почты вводить не обязательно, но рекомендуется его указать, чтобы получать уведомления о заказе.</p>
									</div>
								</div>
							</div>
							<div class="well well-white well-noshadow" id="deliveryTypeForm2" <?=(Arr::get($customerCartInfo, 'delivery_type', 0) == 0 ? 'style="display: none;"' : '');?>>
								<div>
									<legend>Адрес доставки</legend>
									<div>
										<div class="row-fluid">
											<table class="table table-non-bordered">
												<tbody>
												<tr>
													<td class="col-sm-2 col-xs-2 col-md-2">
														Имя:
													</td>
													<td colspan="3">
														<input class="form-control cart-customer-field" placeholder="Имя получателя" id="customerName" type="text" maxlength="128" value="<?=Arr::get($customerCartInfo, 'name', '');?>">
													</td>
												</tr>
												<tr>
													<td>
														Телефон <span class="color-red">*</span> :
													</td>
													<td>
														<input class="form-control cart-customer-field" placeholder="Телефон" id="customerPhone" type="text" value="<?=Arr::get($customerCartInfo, 'phone', '');?>">
													</td>
												</tr>
												<tr>
													<td>
														Адрес эл. почты:
													</td>
													<td>
														<input class="form-control cart-customer-field" placeholder="Адрес эл. почты" id="customerMail" type="text" value="<?=Arr::get($customerCartInfo, 'email', '');?>">
													</td>
												</tr>
												<tr>
													<td>
														Улица:
													</td>
													<td>
														<input class="form-control cart-customer-field" placeholder="Улица" id="customerStreet" type="text" maxlength="64" value="<?=Arr::get($customerCartInfo, 'street', '');?>">
													</td>
												</tr>
												<tr>
													<td>
														Дом:
													</td>
													<td>
														<input class="form-control cart-customer-field" placeholder="Дом" id="customerHouse" type="text" maxlength="6" value="<?=Arr::get($customerCartInfo, 'house', '');?>">
													</td>
												</tr>
												<tr>
													<td>
														Кв. или офис:
													</td>
													<td>
														<input class="form-control cart-customer-field" placeholder="Кв. или офис" id="customerFlat" type="text" maxlength="6" value="<?=Arr::get($customerCartInfo, 'flat', '');?>">
													</td>
												</tr>
												<tr>
													<td colspan="4">
														<textarea class="form-control cart-customer-field" placeholder="Комментарий" rows="5" id="customerComment"></textarea>
													</td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="quest-client-message">
										<p>Необходимо вводить федеральный номер сотового телефона в формате <nobr>(+7 9XX XXX XXXX)</nobr>, чтобы отправить SMS-уведомление о статусе заказа.</p>
										<p>Адрес эл. почты вводить не обязательно, но рекомендуется его указать, чтобы получать уведомления о заказе.</p>
									</div>
								</div>
							</div>
							<h3>Список товаров</h3>
							<table class="table table-bordered cart-table">
								<thead>
									<tr>
										<th>Код</th>
										<th class="col-sm-1 col-xs-1 col-md-1 text-center col-img">Фото</th>
										<th class="text-center">Наименование товара</th>
										<th class="col-sm-1 col-xs-1 col-md-1 text-center">Цена</th>
										<th class="col-sm-2 col-xs-3 col-md-3 text-center">Кол-во</th>
										<th class="col-sm-1 col-xs-1 col-md-1 text-center">Всего</th>
										<th class="col-sm-1 col-xs-1 col-md-1 text-center"></th>
									</tr>
								</thead>
								<tbody>
								<?
							$allPrice = 0;
							foreach($cartInfo as $cartData) {
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
										<span class="cart-price"><?=$cartData['price'];?></span>
										<input type="hidden" class="position-price" id="positionPrice_<?=$cartData['id'];?>" value="<?=$cartData['price'];?>">
									</td>
									<td>
										<input class="form-control position-num" id="positionNum_<?=$cartData['id'];?>" type="text" value="<?=$cartData['num'];?>">
										<div class="btn-group">
											<button class="btn btn-default text-success position-num-plus" value="<?=$cartData['id'];?>">+</button>
											<button class="btn btn-default text-danger position-num-minus" value="<?=$cartData['id'];?>">-</button>
										</div>
									</td>
									<td>
										<span class="cart-price" id="positionSum_<?=$cartData['id'];?>"><?=$cartData['price']*$cartData['num'];?></span>
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
									<span class="text-primary" id="allPrice"><?=$allPrice;?></span>
								</td>
								<td class="text-left">

								</td>
							</tr>
							<tr>
								<td colspan="7" class="text-right">
									<form id="newOrderForm" action="/profile/orders/list" method="post">
										<input type="hidden" name="newOrder">
									</form>
									<button class="btn btn-success createOrder" onclick="createOrder();">Оформить заказ</button>
								</td>
							</tr>
							</tfoot>
							</table>
							<div class="modal fade" id="errorModal">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">Предупреждение</h4>
										</div>
										<div class="modal-body">
											<div class="alert alert-danger">
												<span id="error-message">В корзине отсутствуют товары!</span>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane  <?=($action == 'list' ? 'active' : '');?>" id="orders-tab">
					<table class="table orders-list">
						<thead>
							<tr>
								<th class="col-sm-1 col-xs-1 col-md-1 text-center">Номер / Дата оформления</th>
								<th>Товар</th>
								<th class="col-sm-1 col-xs-1 col-md-1 text-center">Статус</th>
								<th class="col-sm-2 col-xs-2 col-md-2 text-center">Сумма</th>
							</tr>
						</thead>
						<tbody>
							<?foreach ($ordersList as $data){?>
							<tr>
								<td class="text-center"><a href="#"><b>№ <?=$data['id'];?><br/><?=date("d.m.Y", strtotime($data['date']));?></b></a></td>
								<td>
									<?
									$summ = 0;
									foreach(Model::factory('Order')->getOrderData($data['id']) as $orderData){
										?>
										<div class="text-left">
											<?=$orderData['product_name'];?>  (<b><?=$orderData['price'];?></b> р. x <b><?=$orderData['num'];?></b> шт.)
										</div>
										<?
										$summ += $orderData['price']*$orderData['num'];
									}
									?>
								</td>
								<td class="text-center"><span class="label label-success"><?=$data['status_name'];?><span></td>
								<td class="text-center"><b><?=$summ;?> руб.</b></td>
							</tr>
							<?}?>
						</tbody>
					</table>
				</div>
    		</div>
		</div>
	</div>
</div>