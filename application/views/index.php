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
</div>