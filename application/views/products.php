<?
/** @var Model_Shop $shopModel */
$shopModel = Model::factory('Shop');

/** @var Model_Users $userModel */
$userModel = Model::factory('Users');

/** @var Model_Product $productModel */
$productModel = Model::factory('Product');

$countShop = count($shopModel->getShop());
$userProfile = Auth::instance()->logged_in() ? $userModel->getUsersProfile(Auth::instance()->get_user()->id) : [];
$userDiscount = !empty($userProfile) ? ($userProfile[0]['contractor'] == 1 ? $userProfile[0]['discount'] : 0) : 0;
?>
<div class="container container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
        <table class="table table-hover table-bordered table-striped catalog-table">
            <thead>
            <tr>
                <th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 col-code">Код</th>
                <th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 text-center col-img">Фото</th>
                <th class="col-sm-4 col-xs-4 col-md-6 col-lg-6">Наименование</th>
                <th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 text-center col-price">Цена</th>
                <th class="col-sm-2 col-xs-2 col-md-2 col-lg-2 text-center col-num">Наличие в магазинах</th>
                <th class="col-sm-1 col-xs-1 col-md-1 col-lg-1 col-cart"></th>
            </tr>
            </thead>
            <tbody>
            <?
            $emptyNumProducts = [];
            $brand_name = '';
            $categoryName = '';

            foreach($products as $product){
                $checkNum = false;

                foreach($product['shop_info'] as $shop_data) {
                    if ((int)$shop_data['num'] > 0) $checkNum = true;
                }

                if (!$checkNum) {
                    $emptyNumProducts[] = $product;
                    continue;
                }

                if($categoryName != $product['category_name']){
                    ?>
                    <tr>
                        <td class="group-name" colspan="<?=($countShop + 5);?>" data-group-id="<?=$product['category_id'];?>" onclick="changGroupVisibility(<?=$product['category_id'];?>);">
                            <?=$product['category_name'];?>
                        </td>
                    </tr>
                    <?
                    $categoryName = $product['category_name'];
                }

                if($brand_name != $product['brand_name'] && !empty($product['brand_name'])){
                    ?>
                    <tr>
                        <td class="brand-name" colspan="<?=($countShop + 5);?>">
                            <?=$product['brand_name'];?>
                        </td>
                    </tr>
                    <?
                    $brand_name = $product['brand_name'];
                }
                ?>
                <tr class="group-row" id="productRow<?=$product['id'];?>" data-group="<?=$product['category_id'];?>">
                    <td onclick="document.location='/item/product/<?=$product['id'];?>';" class="product-cell-code"><?=$product['code'];?></td>
                    <td class="img-cell">
                        <?$product['product_img'] = $product['product_img'] != '' ? $product['product_img'] : 'nopic.jpg';?>
                        <div class="img-link pull-left" data-toggle="tooltip" data-placement="right" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$product['product_img'];?>' style='width:200px;'>">
                            <img class="img-thumbnail" src="/public/img/thumb/<?=$product['product_img'];?>">
                        </div>
                    </td>
                    <td onclick="document.location='/item/product/<?=$product['id'];?>';" class="catalog-name-col">
                        <div>
                            <a href="/item/product/<?=$product['id'];?>"><?=$product['name'];?></a>
                        </div>
                        <div><?=$product['short_description'];?></div>
<!--                        <div class="t-rating">-->
<!--                            <span class="glyphicon glyphicon-star"></span>-->
<!--                            <span class="glyphicon glyphicon-star"></span>-->
<!--                            <span class="glyphicon glyphicon-star"></span>-->
<!--                            <span class="glyphicon glyphicon-star-empty"></span>-->
<!--                            <span class="glyphicon glyphicon-star-empty"></span>-->
<!--                        </div>-->
                    </td>
                    <td class="product-cell-price"><?=round(($product['price'] * (1 - $userDiscount / 100)), 0);?>р.</td>
                    <td class="product-cell-num">
<!--                        <div-->
<!--                            class="popover-product-number"-->
<!--                            data-container="body"-->
<!--                            data-toggle="popover"-->
<!--                            data-placement="right"-->
<!--                            data-html="true"-->
<!--                            data-title="Наличие в магазинах"-->
<!--                            data-content=""-->
<!--                        >-->
<!--                            В наличии-->
<!--                        </div>-->
                        <?foreach($product['shop_info'] as $shop_data) {if (!(int)$shop_data['num']) continue;?>
                            <div><?=$shop_data['name'];?>: <strong><?=$shop_data['num'];?> шт.</strong></div>
                        <?}?>
                    </td>
                    <td class="product-cell-cart">
                        <button type="button" id="addCartButton_<?=$product['id'];?>" class="btn btn-default cart-add" value="<?=$product['id'];?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
                        <a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=$product['id'];?>" class="btn btn-success cart-in" value="<?=$product['id'];?>">Корзина <span class="glyphicon glyphicon-log-out"></span></a>
                    </td>
                </tr>
                <?
            }

            if (0 !== count($emptyNumProducts)) {
                ?>
                <tr>
                    <td class="empty-num-title" colspan="<?=($countShop + 5);?>">
                        Товары под заказ
                    </td>
                </tr>
                <?
            }

            $brand_name = '';
            $categoryName = '';

            foreach($emptyNumProducts as $product){
                if($categoryName != $product['category_name']){
                    ?>
                    <tr>
                        <td class="empty-group-name" colspan="<?=($countShop + 5);?>" onclick="changEmptyGroupVisibility(<?=$product['category_id'];?>);">
                            <?=$product['category_name'];?>
                        </td>
                    </tr>
                    <?
                    $categoryName = $product['category_name'];
                }

                if($brand_name != $product['brand_name'] && !empty($product['brand_name'])){
                    ?>
                    <tr>
                        <td class="brand-name" colspan="<?=($countShop + 5);?>">
                            <?=$product['brand_name'];?>
                        </td>
                    </tr>
                    <?
                    $brand_name = $product['brand_name'];
                }
                ?>
                <tr class="empty-group-row" data-group="<?=$product['category_id'];?>">
                    <td class="product-cell-code" onclick="document.location='/item/product/<?=$product['id'];?>';"><?=$product['code'];?></td>
                    <td class="img-cell">
                        <?$product['product_img'] = $product['product_img'] != '' ? $product['product_img'] : 'nopic.jpg';?>
                        <div class="img-link pull-left" data-toggle="tooltip" data-placement="right" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$product['product_img'];?>' style='width:200px;'>">
                            <img class="img-thumbnail" src="/public/img/thumb/<?=$product['product_img'];?>">
                        </div>
                    </td>
                    <td onclick="document.location='/item/product/<?=$product['id'];?>';" class="catalog-name-col">
                        <div>
                            <a href="/item/product/<?=$product['id'];?>"><?=$product['name'];?></a>
                        </div>
                        <div><?=$product['short_description'];?></div>
<!--                        <div class="t-rating">-->
<!--                            <span class="glyphicon glyphicon-star"></span>-->
<!--                            <span class="glyphicon glyphicon-star"></span>-->
<!--                            <span class="glyphicon glyphicon-star"></span>-->
<!--                            <span class="glyphicon glyphicon-star-empty"></span>-->
<!--                            <span class="glyphicon glyphicon-star-empty"></span>-->
<!--                        </div>-->
                    </td>
                    <td class="product-cell-price"><?=round(($product['price'] * (1 - $userDiscount / 100)), 0);?>р.</td>
                    <td class="product-cell-num">
                        Под заказ
                    </td>
                    <td class="product-cell-cart">
                        <button type="button" id="addCartButton_<?=$product['id'];?>" class="btn btn-default cart-add" value="<?=$product['id'];?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
                        <a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=$product['id'];?>" class="btn btn-success cart-in" value="<?=$product['id'];?>">Корзина <span class="glyphicon glyphicon-log-out"></span></a>
                    </td>
                </tr>
                <?
            }?>
            </tbody>
        </table>
        </div>
    </div>
</div>