<div style="padding-top: 10px;">
<?
foreach($products as $product){?>
    <div class="alert alert-info" id="productInfo<?=$product['id'];?>">
        <a href="/admin/redactproducts/?id=<?=$product['id'];?>" target="_blank">(<?=$product['code'];?>) <?=$product['name'];?> (закуп. = <?=$product['purchase_price'];?> р.), (розн. = <?=$product['price'];?> р.) <span class="glyphicon glyphicon-pencil"></span></a>
        <span class="pull-right glyphicon glyphicon-remove" style="cursor: pointer;" onclick="if(confirm('Подтвердить удаление товара?')) {removeProduct(<?=$product['id'];?>);}"></span>
    </div>
<?}?>
</div>
