<div style="padding-top: 10px;">
<?
foreach($products as $product){?>
    <div class="alert alert-info" id="productInfo<?=$product['id'];?>">
        <a style="margin-right: 10px;" href="/admin/redactproducts/?id=<?=$product['id'];?>" target="_blank">(<?=$product['code'];?>) <?=$product['name'];?> (закуп. = <?=$product['purchase_price'];?> р.), (розн. = <?=$product['price'];?> р.)
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
        <?=($product['is_popular'] ? '<span class="glyphicon glyphicon-star change-popular-product" title="Удалить из популярных" style="color: #E25734;" onclick="removeFromPopularProducts(' . (int)$product['id'] . ', ' . (int)$product['category_id'] . ');"></span>' : '<span class="glyphicon glyphicon-star-empty change-popular-product" title="Добавить в популярные" onclick="addToPopularProducts(' . (int)$product['id'] . ', ' . (int)$product['category_id'] . ');"></span>');?>
        <span class="pull-right glyphicon glyphicon-remove cursor-pointer" onclick="if(confirm('Подтвердить удаление товара?')) {removeProduct(<?=$product['id'];?>);}"></span>
    </div>
<?}?>
</div>
