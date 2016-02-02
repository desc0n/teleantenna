<div class="col-sm-3 left-nav">
<div class="b-name hidden-xs">ТелеАНТЕННА</div>
<!--полноразмерное меню-->
	<div class="hidden-xs">
		<?foreach(Model::factory('Product')->getProductGroup(1) as $group_1_data){?>
			<div class="slide-trigger">
				<a class="catalog-link" href="/catalog/?group_1=<?=$group_1_data['id'];?>">
					<div class="panel-heading collapsed">
						<h4 class="panel-title">    
							<?=$group_1_data['name'];?> <span class="glyphicon glyphicon-chevron-right pull-right"></span>
						</h4>
					</div>
				</a>
				<div class="panel-collapse collapse slidepnl">
					<ul class="list-group">
						<?foreach(Model::factory('Product')->getProductGroup(2, $group_1_data['id']) as $group_2_data){?>
						<div class="sub-trigger">
							<li class="list-group-item"><a class="catalog-link" href="/catalog/?group_2=<?=$group_2_data['id'];?>"><?=$group_2_data['name'];?></a></li>
							<div class="panel-collapse collapse subslidepnl">
								<ul class="list-group">
									<?foreach(Model::factory('Product')->getProductGroup(3, $group_2_data['id']) as $group_3_data){?>
									<li class="list-group-item"><a href="#"><?=$group_3_data['name'];?></a></li>
									<?}?>
								</ul>
							</div>
						</div>
						<?}?>
					</ul>
				</div>
			</div>
		<?}?>
	</div>
</div>