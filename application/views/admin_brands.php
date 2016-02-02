<div class="row">
	<h2 class="sub-header col-sm-12">Производители:</h2>
	<div class="col-sm-11">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse">
					Список производителей
					</a>
					</h4>
				</div>
				<div id="collapse" class="panel-collapse collapse in">
					<div class="panel-body product-group-panel-body">
						<?foreach(Model::factory('Product')->getBrands() as $brand_data){?>
						<div class="row-accordion">
							<div class="panel-group" id="accordion1<?=$brand_data['id'];?>">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion1<?=$brand_data['id'];?>" href="#collapse1<?=$brand_data['id'];?>">
										<?=$brand_data['name'];?>
										</a>
										<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#remove_brand_form_1_<?=$brand_data['id'];?>').submit();"></span>
										</h4>
										<form id="remove_brand_form_1_<?=$brand_data['id'];?>" class="display-none" action="/admin/brands" method="post">
											<input type="hidden" name="removebrand" value="<?=$brand_data['id'];?>">
										</form>
									</div>
								</div>
							</div>
						</div>
						<?}?>
						<form action="/admin/brands" method="post">
							<div class="input-group">
								<input type="text" class="form-control" name="brand_name">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" name="addbrand" value="1"><span class="glyphicon glyphicon-plus"></span></button>
								</span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
