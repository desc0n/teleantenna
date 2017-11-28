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
	<h2>Список магазинов</h2>
	<?foreach($shopArr as $shopData){?>
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 main-page-shop cursor-pointer" data-id="<?=$shopData['id'];?>">
			<h3><?=$shopData['name'];?></h3>
            <div class="row">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <?=(!empty($shopData['img']) ? '<img class="shop-img img-thumbnail" src="/public/img/shopes/'.$shopData['id'].'_'.$shopData['img'].'" alt="'.$shopData['name'].'">' : '');?>
                </div>
                <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
                    <div class="row">
                        <?=(!empty($shopData['address']) ? '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12"><p><strong>Адрес: </strong>'.$shopData['address'].'</p></div>' : '');?>
                        <?=(!empty($shopData['info']) ? '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12"><p><strong>Информация: </strong>'.$shopData['info'].'</p></div>' : '');?>
                    </div>
                </div>
		    </div>
		</div>
	</div>
	<?}?>
</div>
<?}?>