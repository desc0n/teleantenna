<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');

function renderCategory($productCategory, $categoryId, $parentCategoryId = null) {
    $html = '
<table class="table redact-category-table" id="redactProductCategory' . $productCategory['id'] . '" data-hidden="1">
    <tr ' . ($parentCategoryId ? 'class="redact-category-sub-row redact-category-sub-row-' . $parentCategoryId . '"' : '') . '>
        <td>
            <div class="redact-category-form">
                <div class="input-group">
                    <input type="text" class="form-control product-category-name" value="' . $productCategory['name'] . '">
                    <span class="input-group-btn">
                        <button class="btn btn-default" name="action" onclick="$(this).attr(\'disabled\',\'disabled\');patchCategory(' . $productCategory['id'] . ', {productCategoryName: $(\'#redactProductCategory' . $productCategory['id'] . ' .redact-category-form .product-category-name\').val()});" >
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                    </span>
                </div>
            </div>
            <div class="redact-category-name">
            <span class="show-sub-category-btn show-sub-category-btn-' . $productCategory['id'] . ' glyphicon glyphicon-chevron-up" onclick="showSubCategories(' . $productCategory['id'] . ');"></span>' .
            '<span class="redact-category-name-value">' . $productCategory['name'] . '</span>'.
            ($productCategory['isPopular'] ? '<span class="glyphicon glyphicon-star change-popular-category" title="Удалить из популярных" style="color: #E25734;" onclick="removeFromPopularCategories(' . $productCategory['id'] . ');"></span>' : '<span class="glyphicon glyphicon-star-empty change-popular-category" title="Добавить в популярные" onclick="addToPopularCategories(' . $productCategory['id'] . ');"></span>') .
            '<span class="glyphicon glyphicon-pencil redactBtn" onclick="showRedactCategoryForm(' . $productCategory['id'] . ');"></span>
            </div>
            <div class="pull-right remove-category-form">
                <button class="btn btn-warning" type="button"  onclick="removeCategory(' . $productCategory['id'] . ');"><span class="glyphicon glyphicon-remove"></span></button>
            </div>
        </td>
    </tr>
    <tr class="redact-category-sub-row redact-category-sub-row-' . $productCategory['id'] . '">
        <td class="show-category-products-btn show-category-products-btn-' . $productCategory['id'] . '" data-hidden="1" onclick="showProductsList(' . $productCategory['id'] . ');">Показать список товаров</td>
    </tr>
    <tr class="redact-category-sub-row redact-category-sub-row-' . $productCategory['id'] . '">
        <td class="category-products-list category-products-list-' . $productCategory['id'] . '"></td>
    </tr>
    <tr class="redact-category-sub-row redact-category-sub-row-' . $productCategory['id'] . '">
        <td>
            <div class="input-group">
                <input type="text" class="form-control" id="newProduct' . $productCategory['id'] . '" placeholder="Добавить товар в группу ' . $productCategory['name'] . '">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="addProduct(' . $productCategory['id'] . ');">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
            </div>
        </td>
    </tr>
    <tr class="redact-category-sub-row redact-category-sub-row-' . $productCategory['id'] . '"><td>';
    foreach ($productCategory['subCategories'] as $subCategory) {
        $html .= renderCategory($subCategory, $categoryId, $productCategory['id']);
    }
$html .= '
    </td></tr>
    <tr class="redact-category-sub-row redact-category-sub-row-' . $productCategory['id'] . '">
        <td>
            <form method="post">
                <div class="input-group">
                    <input type="text" class="form-control" name="newProductCategory" placeholder="Добавить подгруппу в группу ' . $productCategory['name'] . '">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" name="action" value="addProductCategory">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </span>
                </div>
                <input type="hidden" name="parentCategoryId" value="' . $productCategory['id'] . '">
            </form>
        </td>
    </tr>
</table>
    ';

    return $html;
}
?>
<h1>Редактирование данных</h1>
<div class="row admin-main-page">
	<ul class="nav nav-tabs">
		<li <?=((!$action || $action === 'products') ? 'class="active"' : '');?>><a href="#products" data-toggle="tab">Товары</a></li>
		<li <?=($action === 'brands' ? 'class="active"' : '');?>><a href="#brands" data-toggle="tab">Производители</a></li>
		<li <?=($action === 'shops' ? 'class="active"' : '');?>><a href="#shops" data-toggle="tab">Магазины</a></li>
		<li <?=($action === 'services' ? 'class="active"' : '');?>><a href="#services" data-toggle="tab">Услуги</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane <?=((!$action || $action === 'products') ? 'active' : '');?>" id="products">
			<h2 class="sub-header col-sm-12">Добавление товаров:</h2>
			<div class="col-sm-11">
                <?foreach ($productsCategories as $productCategory) {?>
                    <?=renderCategory($productCategory, $categoryId);?>
                <?}?>
			</div>
		</div>
		<div class="tab-pane <?=($action === 'brands' ? 'active' : '');?>" id="brands">
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
		<div class="tab-pane <?=($action === 'shops' ? 'active' : '');?>" id="shops">
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
		<div class="tab-pane <?=($action === 'services' ? 'active' : '');?>" id="services">
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
											<div id="collapseServices1<?=$group_1_data['id'];?>" class="panel-collapse collapse <?/*=(Arr::get($get, 'group_1', 0) == $group_1_data['id'] ? 'in' : 'in');*/?>">
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
																<div id="collapseServices2<?=$group_2_data['id'];?>" class="panel-collapse collapse <?/*=(Arr::get($get, 'group_2', 0) == $group_2_data['id'] ? 'in' : '');*/?>">
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
																					<div id="collapseServices3<?=$group_3_data['id'];?>" class="panel-collapse collapse <?/*=(Arr::get($get, 'group_3', 0) == $group_3_data['id'] ? 'in' : '');*/?>">
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