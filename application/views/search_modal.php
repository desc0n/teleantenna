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
                <div class="left-nav">
                    <?
                    $r = 1;

                    foreach($productModel->getProductCategoriesList() as $category){?>
                        <div class="search-slide-trigger">
                            <div class="search-panel-heading search-category">
                                <h4 class="search-panel-title">
                                    <?=$category['name'];?>
                                    <img src="/public/i/right.png" class="icon-right">
                                </h4>
                            </div>
                            <?$subCategories = $category['subCategories'];?>
                            <div class="search-panel-collapse search-slide-menu">
                                <ul class="list-group search-sub-category">
                                    <?foreach($subCategories as $key => $subCategory){?>
                                        <li class="list-group-item">
                                            <?=$subCategory['name'];?>
                                            <div class="search-panel-collapse search-slide-menu">
                                                <ul class="list-group">
                                                    <?foreach($productModel->getAdminCategoryProducts((int)$subCategory['id']) as $product){?>
                                                    <li class="list-category-product-item" onclick="setSearchModalItem('<?=$product['id'];?>');">(код <?=$product['code'];?>) <?=$product['name'];?> (<?=$product['price'];?> руб.)</li>
                                                    <?}?>
                                                </ul>
                                            </div>
                                        </li>
                                    <?}?>
                                </ul>
                            </div>
                        </div>
                    <?}?>
                    <input type="hidden" id="searchModalRow" value="-1">
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>