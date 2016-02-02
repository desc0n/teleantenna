<div class="row">
	<h2 class="sub-header col-sm-12">Города:</h2>
	<div class="col-sm-11">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse">
					Список городов
					</a>
					</h4>
				</div>
				<div id="collapse" class="panel-collapse collapse in">
					<div class="panel-body product-group-panel-body">
						<?foreach(Model::factory('Shop')->getCity() as $city_data){?>
						<div class="row-accordion">
							<div class="panel-group" id="accordion1<?=$city_data['id'];?>">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion1<?=$city_data['id'];?>" href="#collapse1<?=$city_data['id'];?>">
										<?=$city_data['name'];?>
										</a>
										<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="javascript: $('#remove_city_form_1_<?=$city_data['id'];?>').submit();"></span>
										</h4>
										<form id="remove_city_form_1_<?=$city_data['id'];?>" class="display-none" action="/admin/addcities" method="post">
											<input type="hidden" name="removecity" value="<?=$city_data['id'];?>">
										</form>
									</div>
								</div>
							</div>
						</div>
						<?}?>
						<form action="/admin/addcities" method="post">
							<div class="input-group">
								<input type="text" class="form-control" name="city_name">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" name="addcity" value="1"><span class="glyphicon glyphicon-plus"></span></button>
								</span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
