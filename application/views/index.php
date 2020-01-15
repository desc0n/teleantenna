<?php
$issetPopularCategories = false;
foreach ($categories as $category) {
    if((bool)$category['isPopular']) {
        $issetPopularCategories = true;
        break;
    }
}
?>
<?=View::factory('catalog_menu')->set('categories', $categories);?>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 shop-content">
    <div class="row popular-categories">
        <?if($issetPopularCategories){?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2 class="text-center">Популярные категории</h2>
        </div>
        <?}?>
        <?foreach ($categories as $category) {?>
        <?if(!(bool)$category['isPopular']) continue;?>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 main-popular-category">
            <a href="/catalog?categoryId=<?=$category['id'];?>">
                <img class="img-thumbnail" src="/public/i/categories/original/<?=($category['id'] . '_' . $category['imgSrc']);?>">
            </a>
            <div class="text-center">
                <a href="/catalog?categoryId=<?=$category['id'];?>">
                    <strong><?=preg_replace('/[0-9\.]+/', '', $category['name']);?></strong>
                </a>
            </div>
        </div>
        <?}?>
    </div>
    <div class="row popular-products">
        <?if(count($popularProducts) > 0){?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2 class="text-center">Популярные товары</h2>
        </div>
        <?}?>
        <?foreach ($popularProducts as $product) {?>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 main-popular-product">
            <?$product['product_img'] = $product['product_img'] != '' ? $product['product_img'] : 'nopic.jpg';?>
            <div class="main-popular-product-img">
                <a href="/item/product/<?=$product['id'];?>"><img class="img-thumbnail" src="/public/img/original/<?=$product['product_img'];?>"></a>
            </div>
            <div class="main-popular-product-name">
                <a href="/item/product/<?=$product['id'];?>"><?=$product['name'];?></a>
            </div>
            <div class="main-popular-product-price">
                <?=$product['price'];?> руб.
            </div>
            <div class="main-popular-product-button">
                <button type="button" class="btn btn-default cart-add" value="<?=$product['id'];?>">В корзину</button>
            </div>
        </div>
        <?}?>
    </div>
</div>