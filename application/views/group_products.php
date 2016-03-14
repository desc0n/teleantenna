<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');

foreach($productModel->getProduct(1, $params, 0, ['sortSql' => 'order by `code`']) as $product_data){?>
    <div class="alert alert-info">
        <a href="/admin/redactproducts/?id=<?=$product_data['id'];?>">(<?=$product_data['code'];?>) <?=$product_data['name'];?> (закуп. = <?=$product_data['purchase_price'];?> р.), (розн. = <?=$product_data['price'];?> р.) <span class="glyphicon glyphicon-pencil"></span></a>
        <span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="$('#removeproduct').val(<?=$product_data['id'];?>);$('#remove_product > #group_1').val(<?=$params[1];?>);$('#remove_product > #group_2').val(<?=$params[2];?>);$('#remove_product > #group_3').val(<?=$params[3];?>);$('#remove_product').submit();"></span>
    </div>
<?}?>