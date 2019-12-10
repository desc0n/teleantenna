<?=View::factory('catalog_menu');?>
<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');

if(!empty($get)){?>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 main-content">
	<div class="post-nav ">
	<ol class="breadcrumb">
		<li><a href="/">Главная</a></li>
	  	<?=(($group_1 != 0 || $group_2 != 0) ? '<li '.($group_2 == 0 ? ' class="active">' : '><a href="/catalog/?group_1='.$group_2_parent_id.'">')
			.$group_1_name.($group_2 == 0 ? '' : '</a>').'</li>'
			: '');?>
	  	<?=$group_2 != 0 ?
			'<li '.($group_3 == 0
				? ' class="active">'
				: '><a href="/catalog/?group_2='.$group_3_parent_id.'">')
			.$group_2_name.($group_3 == 0 ? '' : '</a>').'</li>'
			: '';?>
	</ol>
	</div>
	<?=View::factory('catalog_table')->set('productsArr', $productModel->getProductList(['group_1' => Arr::get($get,'group_1',0), 'group_2' => Arr::get($get,'group_2',0), 'group_3' => Arr::get($get,'group_3',0)]));?>
</div>
<?} else {?>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 shop-content">

</div>
<?}?>