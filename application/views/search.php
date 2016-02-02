<?=View::factory('catalog_menu');?>
<div class="col-sm-9 main-content">
	<div class="post-nav ">
	<ol class="breadcrumb">
	  <li><a href="/catalog/">Каталог</a></li>
	  <li class="active">Поиск</li>
	</ol>
	</div>
	<?=View::factory('catalog_table')->set('productsArr', $productsArr);?>
</div>