<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');
?>
<div class="modal fade" id="searchModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Поиск товара</h4>
			</div>
			<div class="modal-body">
				<div class="col-sm-4 left-nav">
					<div class="">
						<?foreach($productModel->getProductGroup(1) as $group_1_data){?>
							<div class="slide-trigger">
								<div class="catalog-link">
									<div class="panel-heading collapsed">
										<h4 class="panel-title">
											<?=$group_1_data['name'];?> <span class="glyphicon glyphicon-chevron-right pull-right"></span>
										</h4>
									</div>
								</div>
								<div class="panel-collapse collapse slidepnl">
									<ul class="list-group">
										<?foreach($productModel->getProductGroup(2, $group_1_data['id']) as $group_2_data){?>
											<div class="sub-trigger">
												<li class="list-group-item">
													<div class="catalog-link"><?=$group_2_data['name'];?></div>
												</li>
												<div class="panel-collapse collapse subslidepnl">
													<ul class="list-group">
														<?foreach($productModel->getProductGroup(3, $group_2_data['id']) as $group_3_data){?>
															<li class="list-group-item">
																<div><?=$group_3_data['name'];?></div>
															</li>
															<div class="panel-collapse collapse subslidepnl">
																<ul class="list-group">
																	<div class="sub-trigger">
																		<?foreach($productModel->getProduct(1, Array(1=>$group_1_data['id'],2=>$group_2_data['id'],3=>$group_3_data['id'])) as $product_3_data){?>
																			<li class="list-group-item"><div class="list-product-item" onclick="javascript: setSearchModalItem('<?=$product_3_data['id'];?>');">(код <?=$product_3_data['code'];?>) <?=$product_3_data['name'];?> (<?=$product_3_data['price'];?> руб.)</div></li>
																		<?}?>
																	</div>
																</ul>
															</div>
														<?}?>
														<?foreach($productModel->getProduct(1, Array(1=>$group_1_data['id'],2=>$group_2_data['id'],3=>0)) as $product_2_data){?>
															<li class="list-group-item"><div class="list-product-item" onclick="javascript: setSearchModalItem('<?=$product_2_data['id'];?>');">(код <?=$product_2_data['code'];?>) <?=$product_2_data['name'];?> (<?=$product_2_data['price'];?> руб.)</div></li>
														<?}?>
													</ul>
												</div>
											</div>
										<?}?>
										<?foreach($productModel->getProduct(1, Array(1=>$group_1_data['id'],2=>0,3=>0)) as $product_1_data){?>
											<li class="list-group-item"><div class="list-product-item" onclick="javascript: setSearchModalItem('<?=$product_1_data['id'];?>');">(код <?=$product_1_data['code'];?>) <?=$product_1_data['name'];?> (<?=$product_1_data['price'];?> руб.)</div></li>
										<?}?>
									</ul>
								</div>
							</div>
						<?}?>
					</div>
				</div>
				<input type="hidden" id="searchModalRow" value="-1">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>