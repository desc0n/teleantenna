<?=View::factory('catalog_menu');?>
<?
if(!empty($get)){?>
<div class="col-sm-9 main-content">
	<div class="post-nav ">
	<ol class="breadcrumb">
		<li><a href="/catalog/">Каталог</a></li>
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
	<?=View::factory('catalog_services_table')->set('servicesArr', Model::factory('Service')->getServiceList(Array('group_1' => Arr::get($get,'group_1',0), 'group_2' => Arr::get($get,'group_2',0), 'group_3' => Arr::get($get,'group_3',0))));?>
</div>
<?} else {?>
<div class="col-sm-9 shop-content">
	<h2>Список магазинов</h2>
	<?foreach($shopArr as $shopData){?>
	<div class="row">
		<div class="col-12 col-sm-12 col-lg-12">
			<h3><?=$shopData['name'];?></h3>
			<?=(!empty($shopData['img']) ? '<p><img class="shop-img" src="public/img/shopes/'.$shopData['id'].'_'.$shopData['img'].'" alt="'.$shopData['name'].'" class="img-thumbnail"></p>' : '');?>
			<?=(!empty($shopData['address']) ? '<p><strong>Адрес: </strong>'.$shopData['address'].'</p>' : '');?>
			<?=(!empty($shopData['info']) ? '<p><strong>Информация: </strong>'.$shopData['info'].'</p>' : '');?>
		</div>
	</div>
	<?}?>
</div>
<?}?>