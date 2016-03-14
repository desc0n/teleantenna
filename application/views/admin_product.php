<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');
?>
<h1>Редактирование данных</h1>
<div class="row admin-main-page">
	<ul class="nav nav-tabs">
		<li <?=((empty($get['action']) || Arr::get($get,'action', '') == 'products') ? 'class="active"' : '');?>><a href="#products" data-toggle="tab">Товары</a></li>
		<li <?=(Arr::get($get,'action', '') == 'groups' ? 'class="active"' : '');?>><a href="#groups" data-toggle="tab">Группы</a></li>
		<li <?=(Arr::get($get,'action', '') == 'brands' ? 'class="active"' : '');?>><a href="#brands" data-toggle="tab">Производители</a></li>
		<li <?=(Arr::get($get,'action', '') == 'shops' ? 'class="active"' : '');?>><a href="#shops" data-toggle="tab">Магазины</a></li>
		<li <?=(Arr::get($get,'action', '') == 'services' ? 'class="active"' : '');?>><a href="#services" data-toggle="tab">Услуги</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane <?=((empty($get['action']) || Arr::get($get,'action', '') == 'products') ? 'active' : '');?>" id="products">
			<h2 class="sub-header col-sm-12">Добавление товаров:</h2>
			<div class="col-sm-11">
				<div class="panel-group" id="accordionProducts">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordionProducts" href="#collapse">
							Основные группы
							</a>
							</h4>
						</div>
						<div id="collapseProducts" class="panel-collapse collapse in">
							<div class="panel-body product-group-panel-body">
								<?foreach($productModel->getProductGroup(1) as $group_1_data){?>
								<div class="row-accordion">
									<div class="panel-group" id="accordionProducts1<?=$group_1_data['id'];?>">
										<div class="panel panel-default">
											<div class="panel-heading panel-group-1">
												<h4 class="panel-title" id="redactGroupTitle1_<?=$group_1_data['id'];?>">
													<a data-toggle="collapse" data-parent="#accordionProducts1<?=$group_1_data['id'];?>" href="#collapseProducts1<?=$group_1_data['id'];?>" onclick="writeGroupProduct(1,<?=$group_1_data['id'];?>, 0, 0);">
													<?=$group_1_data['name'];?>
													</a>
													<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactGroupTitle1_<?=$group_1_data['id'];?>').css('display', 'none');$('#redactGroupForm1_<?=$group_1_data['id'];?>').css('display', 'block');"></span>
												</h4>
												<form action="/admin/redactproductsgroup" method="post" id="redactGroupForm1_<?=$group_1_data['id'];?>" style="display: none;">
													<div class="input-group">
														<input type="text" class="form-control" name="groupName" value="<?=$group_1_data['name'];?>">
														<span class="input-group-btn">
															<button class="btn btn-default" type="submit" name="redactGroup" value="1"><span class="glyphicon glyphicon-ok"></span></button>
														</span>
													</div>
													<input type="hidden" name="redactGroupId" value="<?=$group_1_data['id'];?>">
													<input type="hidden" name="groupId1" value="<?=$group_1_data['id'];?>">
												</form>
											</div>
											<div id="collapseProducts1<?=$group_1_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get, 'group_1', 0) == $group_1_data['id'] ? 'in' : '');?>">
												<div class="panel-body product-group-panel-body">
													<?foreach($productModel->getProductGroup(2, $group_1_data['id']) as $group_2_data){?>
													<div class="row-accordion">
														<div class="panel-group" id="accordionProducts2<?=$group_2_data['id'];?>">
															<div class="panel panel-default">
																<div class="panel-heading panel-group-2">
																	<h4 class="panel-title">
																		<a data-toggle="collapse" data-parent="#accordionProducts2<?=$group_2_data['id'];?>" href="#collapseProducts2<?=$group_2_data['id'];?>" onclick="javascript: writeGroupProduct(2,<?=$group_1_data['id'];?>, <?=$group_2_data['id'];?>, 0);">
																		<?=$group_2_data['name'];?>
																		</a>
																		<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactGroupTitle2_<?=$group_2_data['id'];?>').css('display', 'none');$('#redactGroupForm2_<?=$group_2_data['id'];?>').css('display', 'block');"></span>
																	</h4>
																	<form action="/admin/redactproductsgroup" method="post" id="redactGroupForm2_<?=$group_2_data['id'];?>" style="display: none;">
																		<div class="input-group">
																			<input type="text" class="form-control" name="groupName" value="<?=$group_2_data['name'];?>">
																			<span class="input-group-btn">
																				<button class="btn btn-default" type="submit" name="redactGroup" value="2"><span class="glyphicon glyphicon-ok"></span></button>
																			</span>
																		</div>
																		<input type="hidden" name="redactGroupId" value="<?=$group_2_data['id'];?>">
																		<input type="hidden" name="groupId1" value="<?=$group_1_data['id'];?>">
																		<input type="hidden" name="groupId2" value="<?=$group_2_data['id'];?>">
																	</form>
																</div>
																<div id="collapseProducts2<?=$group_2_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get, 'group_2', 0) == $group_2_data['id'] ? 'in' : '');?>">
																	<div class="panel-body product-group-panel-body">
																		<?foreach($productModel->getProductGroup(3, $group_2_data['id']) as $group_3_data){?>
																		<div class="row-accordion">
																			<div class="panel-group" id="accordionProducts3<?=$group_3_data['id'];?>">
																				<div class="panel panel-default">
																					<div class="panel-heading panel-group-3">
																						<h4 class="panel-title">
																							<a data-toggle="collapse" data-parent="#accordionProducts3<?=$group_3_data['id'];?>" href="#collapseProducts3<?=$group_3_data['id'];?>" onclick="javascript: writeGroupProduct(3,<?=$group_1_data['id'];?>, <?=$group_2_data['id'];?>, <?=$group_3_data['id'];?>);">
																							<?=$group_3_data['name'];?>
																							</a>
																							<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactGroupTitle3_<?=$group_3_data['id'];?>').css('display', 'none');$('#redactGroupForm3_<?=$group_3_data['id'];?>').css('display', 'block');"></span>
																						</h4>
																						<form action="/admin/redactproductsgroup" method="post" id="redactGroupForm3_<?=$group_3_data['id'];?>" style="display: none;">
																							<div class="input-group">
																								<input type="text" class="form-control" name="groupName" value="<?=$group_3_data['name'];?>">
																								<span class="input-group-btn">
																									<button class="btn btn-default" type="submit" name="redactGroup" value="3"><span class="glyphicon glyphicon-ok"></span></button>
																								</span>
																							</div>
																							<input type="hidden" name="redactGroupId" value="<?=$group_3_data['id'];?>">
																							<input type="hidden" name="groupId1" value="<?=$group_1_data['id'];?>">
																							<input type="hidden" name="groupId2" value="<?=$group_2_data['id'];?>">
																							<input type="hidden" name="groupId3" value="<?=$group_3_data['id'];?>">
																						</form>
																					</div>
																					<div id="collapseProducts3<?=$group_3_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get, 'group_3', 0) == $group_3_data['id'] ? 'in' : '');?>">
																						<div class="panel-body product-group-panel-body">
																							<div class="groupProducts" id="groupProducts3<?=$group_3_data['id'];?>"></div>
																							<form action="/admin/product" method="post">
																								<div class="input-group">
																									<input type="text" class="form-control" name="product_name" placeholder="Добавить товар в группу '<?=$group_3_data['name'];?>'">
																									<span class="input-group-btn">
																										<button class="btn btn-default" type="submit" name="addproduct" value="3"><span class="glyphicon glyphicon-plus"></span></button>
																									</span>
																								</div>
																								<input type="hidden" name="group_1" value="<?=$group_1_data['id'];?>">
																								<input type="hidden" name="group_2" value="<?=$group_2_data['id'];?>">
																								<input type="hidden" name="group_3" value="<?=$group_3_data['id'];?>">
																							</form>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<?}?>
																		<div class="groupProducts" id="groupProducts2<?=$group_2_data['id'];?>"></div>
																		<form action="/admin/product" method="post">
																			<div class="input-group">
																				<input type="text" class="form-control" name="product_name" placeholder="Добавить товар в группу '<?=$group_2_data['name'];?>'">
																				<span class="input-group-btn">
																					<button class="btn btn-default" type="submit" name="addproduct" value="3"><span class="glyphicon glyphicon-plus"></span></button>
																				</span>
																			</div>
																			<input type="hidden" name="group_1" value="<?=$group_1_data['id'];?>">
																			<input type="hidden" name="group_2" value="<?=$group_2_data['id'];?>">
																			<input type="hidden" name="group_3" value="0">
																		</form>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<?}?>
													<div class="groupProducts" id="groupProducts1<?=$group_1_data['id'];?>"></div>
													<form action="/admin/product" method="post">
														<div class="input-group">
															<input type="text" class="form-control" name="product_name" placeholder="Добавить товар в группу '<?=$group_1_data['name'];?>'">
															<span class="input-group-btn">
																<button class="btn btn-default" type="submit" name="addproduct" value="2"><span class="glyphicon glyphicon-plus"></span></button>
															</span>
														</div>
														<input type="hidden" name="group_1" value="<?=$group_1_data['id'];?>">
														<input type="hidden" name="group_2" value="0">
														<input type="hidden" name="group_3" value="0">
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?}?>
								<div class="groupProducts" id="groupProducts"></div>
								<?foreach($productModel->getProduct(1) as $product_data){?>
									<div class="alert alert-info">
										<strong><?=$product_data['id'];?></strong> <?=$product_data['name'];?>
										<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeproduct').val(<?=$product_data['id'];?>);$('#remove_product > #group_1').val(0);$('#remove_product > #group_2').val(0);$('#remove_product > #group_3').val(0);$('#remove_product').submit();"></span>
									</div>
								<?}?>
								<form action="/admin/product" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="product_name">
										<span class="input-group-btn">
											<button class="btn btn-default" type="submit" name="addproduct" value="1"><span class="glyphicon glyphicon-plus"></span></button>
										</span>
									</div>
									<input type="hidden" name="group_1" value="0">
									<input type="hidden" name="group_2" value="0">
									<input type="hidden" name="group_3" value="0">
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<form id="remove_product" action="/admin/product" method="post">
				<input type="hidden" id="removeproduct" name="removeproduct">
				<input type="hidden" id="group_1" name="group_1" value="0">
				<input type="hidden" id="group_2" name="group_2" value="0">
				<input type="hidden" id="group_3" name="group_3" value="0">
			</form>
		</div>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'groups' ? 'active' : '');?>" id="groups">
			<h2 class="sub-header col-sm-12">Группа товаров:</h2>
			<div class="col-sm-11">
				<div class="panel-group" id="accordionGroups">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordionGroups" href="#collapseGroups">
									Основные группы
								</a>
							</h4>
						</div>
						<div id="collapseGroups" class="panel-collapse collapse in">
							<div class="panel-body product-group-panel-body">
								<?
								$i1 = 0;
								foreach($productModel->getProductGroup(1) as $group_1_data){
									$i1++;
									?>
									<div class="row-accordion">
										<div class="panel-group" id="accordionGroups1<?=$group_1_data['id'];?>">
											<div class="panel panel-default">
												<div class="panel-heading panel-group-1">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#accordionGroups1<?=$group_1_data['id'];?>" href="#collapseGroups1<?=$group_1_data['id'];?>">
															<?=$i1;?>. <?=$group_1_data['name'];?>
														</a>
														<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: var den=confirm('Точно подтверждаете удаление?');if(den)$('#remove_group_form_1_<?=$group_1_data['id'];?>').submit();"></span>
													</h4>
													<form id="remove_group_form_1_<?=$group_1_data['id'];?>" class="display-none" action="/admin/product" method="post">
														<input type="hidden" name="type_id" value="1">
														<input type="hidden" name="removegroup" value="<?=$group_1_data['id'];?>">
													</form>
												</div>
												<div id="collapseGroups1<?=$group_1_data['id'];?>" class="panel-collapse collapse">
													<div class="panel-body product-group-panel-body">
														<?
														$i2 = 0;
														foreach($productModel->getProductGroup(2, $group_1_data['id']) as $group_2_data){
															$i2++;
															?>
															<div class="row-accordion">
																<div class="panel-group" id="accordionGroups2<?=$group_2_data['id'];?>">
																	<div class="panel panel-default">
																		<div class="panel-heading panel-group-2">
																			<h4 class="panel-title">
																				<a data-toggle="collapse" data-parent="#accordionGroups2<?=$group_2_data['id'];?>" href="#collapseGroups2<?=$group_2_data['id'];?>">
																					<?=$i1;?>.<?=$i2;?>. <?=$group_2_data['name'];?>
																				</a>
																				<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: var den=confirm('Точно подтверждаете удаление?');if(den)$('#remove_group_form_2_<?=$group_2_data['id'];?>').submit();"></span>
																			</h4>
																			<form id="remove_group_form_2_<?=$group_2_data['id'];?>" class="display-none" action="/admin/product" method="post">
																				<input type="hidden" name="type_id" value="2">
																				<input type="hidden" name="removegroup" value="<?=$group_2_data['id'];?>">
																			</form>
																		</div>
																		<div id="collapseGroups2<?=$group_2_data['id'];?>" class="panel-collapse collapse">
																			<div class="panel-body product-group-panel-body">
																				<?
																				$i3 = 0;
																				foreach($productModel->getProductGroup(3, $group_2_data['id']) as $group_3_data){
																					$i3++;
																					?>
																					<div class="row-accordion">
																						<div class="panel-group" id="accordionGroups3<?=$group_3_data['id'];?>">
																							<div class="panel panel-default">
																								<div class="panel-heading panel-group-3">
																									<h4 class="panel-title">
																										<a data-toggle="collapse" data-parent="#accordionGroups3<?=$group_3_data['id'];?>" href="#collapseGroups3<?=$group_3_data['id'];?>">
																											<?=$i1;?>.<?=$i2;?>.<?=$i3;?>. <?=$group_3_data['name'];?>
																										</a>
																										<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: var den=confirm('Точно подтверждаете удаление?');if(den)$('#remove_group_form_3_<?=$group_3_data['id'];?>').submit();"></span>
																									</h4>
																									<form id="remove_group_form_3_<?=$group_3_data['id'];?>" class="display-none" action="/admin/product" method="post">
																										<input type="hidden" name="type_id" value="3">
																										<input type="hidden" name="removegroup" value="<?=$group_3_data['id'];?>">
																									</form>
																								</div>
																								<div id="collapseGroups3<?=$group_3_data['id'];?>" class="panel-collapse collapse">
																									<div class="panel-body product-group-panel-body">
																										<form action="/admin/product" method="post">
																											<div class="input-group">
																												<input type="text" class="form-control" name="group_name">
																												<span class="input-group-btn">
																													<button class="btn btn-default" type="submit" name="addgroup" value="3"><span class="glyphicon glyphicon-plus"></span></button>
																												</span>
																											</div>
																											<input type="hidden" name="parent_id" value="<?=$group_3_data['id'];?>">
																										</form>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				<?}?>
																				<form action="/admin/product" method="post">
																					<div class="input-group">
																						<input type="text" class="form-control" name="group_name">
																						<span class="input-group-btn">
																							<button class="btn btn-default" type="submit" name="addgroup" value="3"><span class="glyphicon glyphicon-plus"></span></button>
																						</span>
																					</div>
																					<input type="hidden" name="parent_id" value="<?=$group_2_data['id'];?>">
																				</form>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														<?}?>
														<form action="/admin/product" method="post">
															<div class="input-group">
																<input type="text" class="form-control" name="group_name">
													<span class="input-group-btn">
														<button class="btn btn-default" type="submit" name="addgroup" value="2"><span class="glyphicon glyphicon-plus"></span></button>
													</span>
															</div>
															<input type="hidden" name="parent_id" value="<?=$group_1_data['id'];?>">
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?}?>
								<form action="/admin/product" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="group_name">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" name="addgroup" value="1"><span class="glyphicon glyphicon-plus"></span></button>
								</span>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'brands' ? 'active' : '');?>" id="brands">
			<h2 class="sub-header col-sm-12">Производители:</h2>
			<div class="col-sm-11">
				<div class="panel-group" id="accordionBrands">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordionBrands" href="#collapseBrands">
									Список производителей
								</a>
							</h4>
						</div>
						<div id="collapseBrands" class="panel-collapse collapse in">
							<div class="panel-body product-group-panel-body">
								<?foreach($productModel->getBrands() as $brand_data){?>
									<div class="row-accordion">
										<div class="panel-group" id="accordionBrands1<?=$brand_data['id'];?>">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#accordionBrands1<?=$brand_data['id'];?>" href="#collapse1<?=$brand_data['id'];?>">
															<?=$brand_data['name'];?>
														</a>
														<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#remove_brand_form_1_<?=$brand_data['id'];?>').submit();"></span>
													</h4>
													<form id="remove_brand_form_1_<?=$brand_data['id'];?>" class="display-none" action="/admin/product" method="post">
														<input type="hidden" name="removebrand" value="<?=$brand_data['id'];?>">
													</form>
												</div>
											</div>
										</div>
									</div>
								<?}?>
								<form action="/admin/product" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="brand_name">
										<span class="input-group-btn">
											<button class="btn btn-default" type="submit" name="addbrand" value="1"><span class="glyphicon glyphicon-plus"></span></button>
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'shops' ? 'active' : '');?>" id="shops">
			<h2 class="sub-header col-sm-12">Магазины:</h2>
			<div class="col-sm-11">
				<div class="panel-group" id="accordionShops">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordionShops" href="#collapseShops">
									Список магазинов
								</a>
							</h4>
						</div>
						<div id="collapseShops" class="panel-collapse collapse in">
							<div class="panel-body product-group-panel-body">
								<?foreach(Model::factory('Shop')->getCity() as $city_data){?>
									<div class="row-accordion">
										<div class="panel-group" id="accordionShops1<?=$city_data['id'];?>">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title" id="redactCityTitle1_<?=$city_data['id'];?>">
														<a data-toggle="collapse" data-parent="#accordionShops1" href="#collapseShops1">
															<?=$city_data['name'];?>
														</a>
														<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactCityTitle1_<?=$city_data['id'];?>').css('display', 'none');$('#redactCityForm1_<?=$city_data['id'];?>').css('display', 'block');"></span>
														<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: var den=confirm('Точно подтверждаете удаление?');if(den)$('#remove_city_form_1_<?=$city_data['id'];?>').submit();"></span>
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
														<input type="hidden" name="redactcity" value="<?=$city_data['id'];?>">
													</form>
													<form id="remove_city_form_1_<?=$city_data['id'];?>" class="display-none" action="/admin/product" method="post">
														<input type="hidden" name="removecity" value="<?=$city_data['id'];?>">
													</form>
												</div>
												<div id="collapseShops1" class="panel-collapse collapse">
													<div class="panel-body product-group-panel-body">
														<?foreach(Model::factory('Shop')->getShop($city_data['id'], 0) as $shop_data){?>
														<div class="alert alert-info shop-info">
															<form action="/admin/product/?action=shopes" method="post" class="form-horizontal">
																<div class="row">
																	<input class="shop-name form-control" type="text" name="shop_name" value="<?=$shop_data['name'];?>" placeholder="Название">
																	<input class="shop-short-name form-control" type="text" name="shop_short_name" value="<?=$shop_data['short_name'];?>" placeholder="Сокращение">
																	<input class="shop-address form-control" type="text" name="shop_address" value="<?=$shop_data['address'];?>" placeholder="Адрес">
																	<input type="hidden" name="redactshop" value="<?=$shop_data['id'];?>">
																</div>
																<div class="row">
																	<textarea name="info" class="form-control col-sm-12 col-md-12" placeholder="Информация"><?=$shop_data['info'];?></textarea>
																</div>
																<div class="row">
																	<button class="btn btn-danger"><span class="pull-left glyphicon glyphicon-remove" title="удалить" onclick="javascript: var den=confirm('Вы точно хотите удалить магазин?');if(den){$('#remove_shop_form_<?=$shop_data['id'];?>').submit();}"></span></button>
																	<button class="btn btn-success"><span class="pull-left glyphicon glyphicon-ok" title="Сохранить"></span></button>
																</div>
															</form>
															<form role="form" action="/admin/product/?action=shopes" method="post" enctype='multipart/form-data'>
																<div class="form-group row">
																	<span class="col-md-3 col-sm-3 file-title"><?=(!empty($shop_data['img']) ? $shop_data['img'] : 'изображение не загружено');?></span>
																	<input type="file" class="pull-left file" name="imgname">
																	<input type="hidden" name="loadshopimg" value="<?=$shop_data['id'];?>">
																	<button type="submit" class="btn btn-default">Загрузить</button>
																</div>
															</form>
														</div>
														<form id="remove_shop_form_<?=$shop_data['id'];?>" class="display-none" action="/admin/product" method="post">
															<input type="hidden" name="removeshop" value="<?=$shop_data['id'];?>">
														</form>
														<?}?>
														<form action="/admin/product" method="post">
															<div class="input-group">
																<input type="text" class="form-control" name="shop_name" placeholder="Название нового магазина">
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
								<form action="/admin/product" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="city_name" placeholder="Новый город">
										<span class="input-group-btn">
											<button class="btn btn-default" type="submit" name="addcity" value="1"><span class="glyphicon glyphicon-plus"></span></button>
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'services' ? 'active' : '');?>" id="services">
			<h2 class="sub-header col-sm-12">Добавление услуг:</h2>
			<div class="col-sm-11">
				<div class="panel-group" id="accordionServices">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordionServices" href="#collapse">
									Список услуг
								</a>
							</h4>
						</div>
						<div id="collapseServices" class="panel-collapse collapse in">
							<div class="panel-body service-group-panel-body">
								<?foreach(Model::factory('Service')->getServiceGroup(1) as $group_1_data){?>
								<div class="row-accordion">
									<div class="panel-group" id="accordionServices1<?=$group_1_data['id'];?>">
										<div class="panel panel-default">
											<div class="panel-heading panel-group-1">
												<h4 class="panel-title" id="redactGroupTitle1_<?=$group_1_data['id'];?>">
													<a data-toggle="collapse" data-parent="#accordionServices1<?=$group_1_data['id'];?>" href="#collapseServices1<?=$group_1_data['id'];?>">
													<?=$group_1_data['name'];?>
													</a>
													<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactGroupTitle1_<?=$group_1_data['id'];?>').css('display', 'none');$('#redactGroupForm1_<?=$group_1_data['id'];?>').css('display', 'block');"></span>
												</h4>
												<form action="/admin/redactservicesgroup" method="post" id="redactGroupForm1_<?=$group_1_data['id'];?>" style="display: none;">
													<div class="input-group">
														<input type="text" class="form-control" name="groupName" value="<?=$group_1_data['name'];?>">
														<span class="input-group-btn">
															<button class="btn btn-default" type="submit" name="redactGroup" value="1"><span class="glyphicon glyphicon-ok"></span></button>
														</span>
													</div>
													<input type="hidden" name="redactGroupId" value="<?=$group_1_data['id'];?>">
													<input type="hidden" name="groupId1" value="<?=$group_1_data['id'];?>">
												</form>
											</div>
											<div id="collapseServices1<?=$group_1_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get, 'group_1', 0) == $group_1_data['id'] ? 'in' : 'in');?>">
												<div class="panel-body service-group-panel-body">
													<?foreach(Model::factory('Service')->getServiceGroup(2, $group_1_data['id']) as $group_2_data){?>
													<div class="row-accordion">
														<div class="panel-group" id="accordionServices2<?=$group_2_data['id'];?>">
															<div class="panel panel-default">
																<div class="panel-heading panel-group-2">
																	<h4 class="panel-title">
																		<a data-toggle="collapse" data-parent="#accordionServices2<?=$group_2_data['id'];?>" href="#collapseServices2<?=$group_2_data['id'];?>">
																		<?=$group_2_data['name'];?>
																		</a>
																		<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactGroupTitle2_<?=$group_2_data['id'];?>').css('display', 'none');$('#redactGroupForm2_<?=$group_2_data['id'];?>').css('display', 'block');"></span>
																	</h4>
																	<form action="/admin/redactservicesgroup" method="post" id="redactGroupForm2_<?=$group_2_data['id'];?>" style="display: none;">
																		<div class="input-group">
																			<input type="text" class="form-control" name="groupName" value="<?=$group_2_data['name'];?>">
																			<span class="input-group-btn">
																				<button class="btn btn-default" type="submit" name="redactGroup" value="2"><span class="glyphicon glyphicon-ok"></span></button>
																			</span>
																		</div>
																		<input type="hidden" name="redactGroupId" value="<?=$group_2_data['id'];?>">
																		<input type="hidden" name="groupId1" value="<?=$group_1_data['id'];?>">
																		<input type="hidden" name="groupId2" value="<?=$group_2_data['id'];?>">
																	</form>
																</div>
																<div id="collapseServices2<?=$group_2_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get, 'group_2', 0) == $group_2_data['id'] ? 'in' : '');?>">
																	<div class="panel-body service-group-panel-body">
																		<?foreach(Model::factory('Service')->getServiceGroup(3, $group_2_data['id']) as $group_3_data){?>
																		<div class="row-accordion">
																			<div class="panel-group" id="accordionServices3<?=$group_3_data['id'];?>">
																				<div class="panel panel-default">
																					<div class="panel-heading panel-group-3">
																						<h4 class="panel-title">
																							<a data-toggle="collapse" data-parent="#accordionServices3<?=$group_3_data['id'];?>" href="#collapseServices3<?=$group_3_data['id'];?>">
																							<?=$group_3_data['name'];?>
																							</a>
																							<span class="glyphicon glyphicon-pencil redactBtn" onclick="javascript: $('#redactGroupTitle3_<?=$group_3_data['id'];?>').css('display', 'none');$('#redactGroupForm3_<?=$group_3_data['id'];?>').css('display', 'block');"></span>
																						</h4>
																						<form action="/admin/redactservicesgroup" method="post" id="redactGroupForm3_<?=$group_3_data['id'];?>" style="display: none;">
																							<div class="input-group">
																								<input type="text" class="form-control" name="groupName" value="<?=$group_3_data['name'];?>">
																								<span class="input-group-btn">
																									<button class="btn btn-default" type="submit" name="redactGroup" value="3"><span class="glyphicon glyphicon-ok"></span></button>
																								</span>
																							</div>
																							<input type="hidden" name="redactGroupId" value="<?=$group_3_data['id'];?>">
																							<input type="hidden" name="groupId1" value="<?=$group_1_data['id'];?>">
																							<input type="hidden" name="groupId2" value="<?=$group_2_data['id'];?>">
																							<input type="hidden" name="groupId3" value="<?=$group_3_data['id'];?>">
																						</form>
																					</div>
																					<div id="collapseServices3<?=$group_3_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get, 'group_3', 0) == $group_3_data['id'] ? 'in' : '');?>">
																						<div class="panel-body service-group-panel-body">
																							<?foreach(Model::factory('Service')->getService(1, Array(1=>$group_1_data['id'],2=>$group_2_data['id'],3=>$group_3_data['id'])) as $service_3_data){?>
																								<div class="alert alert-info">
																									<a href="/admin/redactservices/?id=<?=$service_3_data['id'];?>">(<?=$service_3_data['code'];?>) <?=$service_3_data['name'];?> (закуп. = <?=$service_3_data['purchase_price'];?> р.), (розн. = <?=$service_3_data['price'];?> р.) <span class="glyphicon glyphicon-pencil"></span></a>
																									<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeservice').val(<?=$service_3_data['id'];?>);$('#remove_service > #group_1').val(<?=$group_1_data['id'];?>);$('#remove_service > #group_2').val(<?=$group_2_data['id'];?>);$('#remove_service > #group_3').val(<?=$group_3_data['id'];?>);$('#remove_service').submit();"></span>
																								</div>
																							<?}?>
																							<form action="/admin/product/?action=services" method="post">
																								<div class="input-group">
																									<input type="text" class="form-control" name="service_name" placeholder="Добавить товар в группу '<?=$group_3_data['name'];?>'">
																									<span class="input-group-btn">
																										<button class="btn btn-default" type="submit" name="addservice" value="3"><span class="glyphicon glyphicon-plus"></span></button>
																									</span>
																								</div>
																								<input type="hidden" name="group_1" value="<?=$group_1_data['id'];?>">
																								<input type="hidden" name="group_2" value="<?=$group_2_data['id'];?>">
																								<input type="hidden" name="group_3" value="<?=$group_3_data['id'];?>">
																							</form>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<?}?>
																		<?foreach(Model::factory('Service')->getService(1, Array(1=>$group_1_data['id'],2=>$group_2_data['id'],3=>0)) as $service_2_data){?>
																			<div class="alert alert-info">
																				<a href="/admin/redactservices/?id=<?=$service_2_data['id'];?>">(<?=$service_2_data['code'];?>) <?=$service_2_data['name'];?> (закуп. = <?=$service_2_data['purchase_price'];?> р.), (розн. = <?=$service_2_data['price'];?> р.)<span class="glyphicon glyphicon-pencil"></span></a>
																				<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeservice').val(<?=$service_2_data['id'];?>);$('#remove_service > #group_1').val(<?=$group_1_data['id'];?>);$('#remove_service > #group_2').val(<?=$group_2_data['id'];?>);$('#remove_service > #group_3').val(0);$('#remove_service').submit();"></span>
																			</div>
																		<?}?>
																		<form action="/admin/product/?action=services" method="post">
																			<div class="input-group">
																				<input type="text" class="form-control" name="service_name" placeholder="Добавить товар в группу '<?=$group_2_data['name'];?>'">
																				<span class="input-group-btn">
																					<button class="btn btn-default" type="submit" name="addservice" value="3"><span class="glyphicon glyphicon-plus"></span></button>
																				</span>
																			</div>
																			<input type="hidden" name="group_1" value="<?=$group_1_data['id'];?>">
																			<input type="hidden" name="group_2" value="<?=$group_2_data['id'];?>">
																			<input type="hidden" name="group_3" value="0">
																		</form>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<?}?>
													<?foreach(Model::factory('Service')->getService(1, Array(1=>$group_1_data['id'],2=>0,3=>0)) as $service_1_data){?>
														<div class="alert alert-info">
															<a href="/admin/redactservices/?id=<?=$service_1_data['id'];?>">(<?=$service_1_data['code'];?>) <?=$service_1_data['name'];?> (закуп. = <?=$service_1_data['purchase_price'];?> р.), (розн. = <?=$service_1_data['price'];?> р.) <span class="glyphicon glyphicon-pencil"></span></a>
															<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeservice').val(<?=$service_1_data['id'];?>);$('#remove_service > #group_1').val(<?=$group_1_data['id'];?>);$('#remove_service > #group_2').val(0);$('#remove_service > #group_3').val(0);$('#remove_service').submit();"></span>
														</div>
													<?}?>
													<form action="/admin/product/?action=services" method="post">
														<div class="input-group">
															<input type="text" class="form-control" name="service_name" placeholder="Добавить товар в группу '<?=$group_1_data['name'];?>'">
															<span class="input-group-btn">
																<button class="btn btn-default" type="submit" name="addservice" value="2"><span class="glyphicon glyphicon-plus"></span></button>
															</span>
														</div>
														<input type="hidden" name="group_1" value="<?=$group_1_data['id'];?>">
														<input type="hidden" name="group_2" value="0">
														<input type="hidden" name="group_3" value="0">
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?}?>
								<?foreach(Model::factory('Service')->getService(1) as $service_data){?>
									<div class="alert alert-info">
										<strong><?=$service_data['id'];?></strong> <?=$service_data['name'];?>
										<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeservice').val(<?=$service_data['id'];?>);$('#remove_service > #group_1').val(0);$('#remove_service > #group_2').val(0);$('#remove_service > #group_3').val(0);$('#remove_service').submit();"></span>
									</div>
								<?}?>
								<!--<form action="/admin/product/?action=services" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="service_name">
										<span class="input-group-btn">
											<button class="btn btn-default" type="submit" name="addservice" value="1"><span class="glyphicon glyphicon-plus"></span></button>
										</span>
									</div>
									<input type="hidden" name="group_1" value="0">
									<input type="hidden" name="group_2" value="0">
									<input type="hidden" name="group_3" value="0">
								</form>-->
							</div>
						</div>
					</div>
				</div>
			</div>
			<form id="remove_service" action="/admin/product/?action=services" method="post">
				<input type="hidden" id="removeservice" name="removeservice">
				<input type="hidden" id="group_1" name="group_1" value="0">
				<input type="hidden" id="group_2" name="group_2" value="0">
				<input type="hidden" id="group_3" name="group_3" value="0">
			</form>
		</div>
	</div>
</div>