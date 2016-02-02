<div class="row">
	<h2 class="sub-header col-sm-12">Магазины:</h2>
	<div class="col-sm-11">
	<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse">
					Список магазинов
					</a>
					</h4>
				</div>
				<div id="collapse" class="panel-collapse collapse ">
					<div class="panel-body product-group-panel-body">
						<?foreach(Model::factory('Shop')->getCity() as $city_data){?>
						<div class="row-accordion">
							<div class="panel-group" id="accordion<?=$city_data['id'];?>">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title" id="redactCityTitle1_<?=$city_data['id'];?>">
											<a data-toggle="collapse" data-parent="#accordion<?=$city_data['id'];?>" href="#collapse<?=$city_data['id'];?>">
												<?=$city_data['name'];?>
											</a>
											<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactCityTitle1_<?=$city_data['id'];?>').css('display', 'none');$('#redactCityForm1_<?=$city_data['id'];?>').css('display', 'block');"></span>
										</h4>
										<form action="/admin/product" method="post" id="redactCityForm1_<?=$city_data['id'];?>" style="display: none;">
											<div class="input-group">
												<input type="text" class="form-control" name="cityName" value="<?=$city_data['name'];?>">
												<span class="input-group-btn">
													<button class="btn btn-default" type="submit" name="redactCity" value="1">
														<span class="glyphicon glyphicon-ok"></span>
													</button>
												</span>
											</div>
											<input type="hidden" name="redactCityId" value="<?=$city_data['id'];?>">
										</form>
									</div>
									<div id="collapse<?=$city_data['id'];?>" class="panel-collapse collapse">
										<div class="panel-body product-group-panel-body">
											<?foreach(Model::factory('Shop')->getShop($city_data['id'], 0) as $shop_data){?>
											<div class="alert alert-info shop-info">
												<div class="redact-shop-head">
													<button class="btn btn-danger"><span class="pull-left glyphicon glyphicon-remove" title="удалить" onclick="javascript: var den=confirm('Вы точно хотите удалить магазин?');if(den){$('#remove_shop_form_<?=$shop_data['id'];?>').submit();}"></span></button>
													<strong><?=$shop_data['id'];?></strong>
												</div>
												<form action="/admin/addshopes" method="post">
													<input class="shop-name form-control" type="text" name="shop_name" value="<?=$shop_data['name'];?>" placeholder="Название">
													<input class="shop-short-name form-control" type="text" name="shop_short_name" value="<?=$shop_data['short_name'];?>" placeholder="Сокращение">
													<input class="shop-address form-control" type="text" name="shop_address" value="<?=$shop_data['address'];?>" placeholder="Адрес">
													<input type="hidden" name="redactshop" value="<?=$shop_data['id'];?>">
													<button class="btn btn-success pull-right"><span class="pull-left glyphicon glyphicon-ok" title="Сохранить"></span></button>
												</form>
											</div>
											<form id="remove_shop_form_<?=$shop_data['id'];?>" class="display-none" action="/admin/addshopes" method="post">
												<input type="hidden" name="removeshop" value="<?=$shop_data['id'];?>">
											</form>
											<?}?>
											<form action="/admin/addshopes" method="post">
												<div class="input-group">
													<input type="text" class="form-control" name="shop_name">
													<span class="input-group-btn">
														<button class="btn btn-default" type="submit" name="addshop" value="1"><span class="glyphicon glyphicon-plus"></span></button>
													</span>
												</div>
												<input type="hidden" name="city" value="<?=$city_data['id'];?>">
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>