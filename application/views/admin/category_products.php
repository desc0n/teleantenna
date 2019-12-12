<div style="padding-top: 10px;">
<?
foreach($products as $product){?>
    <div class="alert alert-info">
        <a href="/admin/redactproducts/?id=<?=$product['id'];?>" target="_blank">(<?=$product['code'];?>) <?=$product['name'];?> (закуп. = <?=$product['purchase_price'];?> р.), (розн. = <?=$product['price'];?> р.) <span class="glyphicon glyphicon-pencil"></span></a>
        <form method="post" class="pull-right" style="width: auto!important;">
            <span class="glyphicon glyphicon-remove" style="cursor: pointer;" onclick="if(confirm('Подтвердить удаление товара?')) {$(this).parents('form:first').submit();}"></span>
            <input type="hidden" name="productId" value="<?=$product['id'];?>">
            <input type="hidden" name="action" value="removeProduct">
        </form>
    </div>
<?}?>
</div>
