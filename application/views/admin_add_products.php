<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');
?>
<div class="row">
	<h2 class="sub-header col-sm-12">Группа товаров:</h2>
	<div class="col-sm-11">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse">
					Основные группы
					</a>
					</h4>
				</div>
				<div id="collapse" class="panel-collapse collapse in">
					<div class="panel-body product-group-panel-body">
						<?foreach($productModel->getProductGroup(1) as $group_1_data){?>
						<div class="row-accordion">
							<div class="panel-group" id="accordion1<?=$group_1_data['id'];?>">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion1<?=$group_1_data['id'];?>" href="#collapse1<?=$group_1_data['id'];?>">
										<?=$group_1_data['name'];?>
										</a>
										</h4>
											</div>
									<div id="collapse1<?=$group_1_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get,'group_1','') == $group_1_data['id'] ? 'in' : '');?>">
										<div class="panel-body product-group-panel-body">
											<?foreach($productModel->getProductGroup(2, $group_1_data['id']) as $group_2_data){?>
											<div class="row-accordion">
												<div class="panel-group" id="accordion2<?=$group_2_data['id'];?>">
													<div class="panel panel-default">
														<div class="panel-heading">
															<h4 class="panel-title">
															<a data-toggle="collapse" data-parent="#accordion2<?=$group_2_data['id'];?>" href="#collapse2<?=$group_2_data['id'];?>">
															<?=$group_2_data['name'];?>
															</a>
															</h4>
														</div>
														<div id="collapse2<?=$group_2_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get,'group_2','') == $group_2_data['id'] ? 'in' : '');?>">
															<div class="panel-body product-group-panel-body">
																<?foreach($productModel->getProductGroup(3, $group_2_data['id']) as $group_3_data){?>
																<div class="row-accordion">
																	<div class="panel-group" id="accordion3<?=$group_3_data['id'];?>">
																		<div class="panel panel-default">
																			<div class="panel-heading">
																				<h4 class="panel-title">
																				<a data-toggle="collapse" data-parent="#accordion3<?=$group_3_data['id'];?>" href="#collapse3<?=$group_3_data['id'];?>">
																				<?=$group_3_data['name'];?>
																				</a>
																				</h4>
																			</div>
																			<div id="collapse3<?=$group_3_data['id'];?>" class="panel-collapse collapse <?=(Arr::get($get,'group_3','') == $group_3_data['id'] ? 'in' : '');?>">
																				<div class="panel-body product-group-panel-body">
																					<?foreach($productModel->getProduct(1, Array(1=>$group_1_data['id'],2=>$group_2_data['id'],3=>$group_3_data['id'])) as $product_3_data){?>
																						<div class="alert alert-info">
																							<strong><?=$product_3_data['id'];?></strong> <a href="/admin/redactproducts/?id=<?=$product_3_data['id'];?>"><?=$product_3_data['name'];?></a>
																							<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeproduct').val(<?=$product_3_data['id'];?>);$('#remove_product > #group_1').val(<?=$group_1_data['id'];?>);$('#remove_product > #group_2').val(<?=$group_2_data['id'];?>);$('#remove_product > #group_3').val(<?=$group_3_data['id'];?>);$('#remove_product').submit();"></span>
																						</div>
																					<?}?>
																					<form action="/admin/addproducts" method="post">
																						<div class="input-group">
																							<input type="text" class="form-control" name="product_name">
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
																<?foreach($productModel->getProduct(1, [1 => $group_1_data['id'], 2 => $group_2_data['id'], 3 => 0]) as $product_2_data){?>
																	<div class="alert alert-info">
																		<strong><?=$product_2_data['id'];?></strong>  <a href="/admin/redactproducts/?id=<?=$product_2_data['id'];?>"><?=$product_2_data['name'];?></a>
																		<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeproduct').val(<?=$product_2_data['id'];?>);$('#remove_product > #group_1').val(<?=$group_1_data['id'];?>);$('#remove_product > #group_2').val(<?=$group_2_data['id'];?>);$('#remove_product > #group_3').val(0);$('#remove_product').submit();"></span>
																	</div>
																<?}?>
																<form action="/admin/addproducts" method="post">
																	<div class="input-group">
																		<input type="text" class="form-control" name="product_name">
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
											<?foreach($productModel->getProduct(1, [1 => $group_1_data['id'], 2 => 0,3 => 0]) as $product_1_data){?>
												<div class="alert alert-info">
													<strong><?=$product_1_data['id'];?></strong> <a href="/admin/redactproducts/?id=<?=$product_1_data['id'];?>"><?=$product_1_data['name'];?></a>
													<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeproduct').val(<?=$product_1_data['id'];?>);$('#remove_product > #group_1').val(<?=$group_1_data['id'];?>);$('#remove_product > #group_2').val(0);$('#remove_product > #group_3').val(0);$('#remove_product').submit();"></span>
												</div>
											<?}?>
											<form action="/admin/addproducts" method="post">
												<div class="input-group">
													<input type="text" class="form-control" name="product_name">
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
						<?foreach($productModel->getProduct(1) as $product_data){?>
							<div class="alert alert-info">
								<strong><?=$product_data['id'];?></strong> <?=$product_data['name'];?>
								<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#removeproduct').val(<?=$product_data['id'];?>);$('#remove_product > #group_1').val(0);$('#remove_product > #group_2').val(0);$('#remove_product > #group_3').val(0);$('#remove_product').submit();"></span>
							</div>
						<?}?>
						<form action="/admin/addproducts" method="post">
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
</div>
<form id="remove_product" action="/admin/addproducts" method="post">
	<input type="hidden" id="removeproduct" name="removeproduct">
	<input type="hidden" id="group_1" name="group_1" value="0">
	<input type="hidden" id="group_2" name="group_2" value="0">
	<input type="hidden" id="group_3" name="group_3" value="0">
</form>