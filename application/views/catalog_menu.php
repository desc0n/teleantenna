<?
/** @var Model_Product $productModel */
$productModel = Model::factory('Product');
?>
<div class="col-sm-3 left-nav">
<div class="b-name hidden-xs">ТелеАНТЕННА</div>
<!--полноразмерное меню-->
	<div class="hidden-xs">
		<?foreach($productModel->getProductGroup(1) as $group_1_data){?>
			<div class="slide-trigger">
				<a class="catalog-link" href="/catalog/?group_1=<?=$group_1_data['id'];?>">
					<div class="panel-heading collapsed">
						<h4 class="panel-title">    
							<?=$group_1_data['name'];?> <span class="glyphicon glyphicon-chevron-right pull-right"></span>
						</h4>
					</div>
				</a>
				<?
				$limit = 10;
				$groupData2 = $productModel->getProductGroup(2, $group_1_data['id']);
				$subListCount = ceil(count($groupData2) / $limit);

				for($i = 0; $i < $subListCount; $i++) {
				?>
				<div class="panel-collapse collapse slidepnl" style="margin-left: <?=($i + 1);?>00%;">
					<ul class="list-group">
						<?foreach($groupData2 as $key => $group_2_data){
							if ($key >= ($i * $limit) && $key < (($i + 1) * $limit)) {?>
						<li class="list-group-item"><a class="catalog-link" href="/catalog/?group_2=<?=$group_2_data['id'];?>"><?=$group_2_data['name'];?></a></li>
							<?}
						}?>
					</ul>
				</div>
				<?}?>
			</div>
		<?}?>
	</div>
</div>