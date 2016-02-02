<div class="row">
	<div class="col-sm-12">
		<ol class="breadcrumb">
			<li><a href="/admin">Операции с товаром</a></li>
			<li><a href="/admin/?action=returns">Список возвратов</a></li>
			<li class="active">Возврат</li>
		</ol>
	</div>
	<h2 class="sub-header col-sm-12">Возврат № <?=$return_id;?></h2>
	<div class="col-sm-11">
		<table class="table table-hover table-bordered table-striped return-table">
			<thead>
				<tr>
					<th class="col-sm-1 col-code">Код</th>
					<th>Наименование</th>
					<th class="col-sm-3">Комментарий</th>
					<th class="col-sm-1 text-center col-num" colspan="">Кол-во (шт.)</th>
					<th class="col-sm-1 text-center col-num" colspan="">Действия</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 1;
				foreach ($returnData as $data){
					if($data['return_status'] == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-return goodsCode' id='goodsCode_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_code'];?>' autocomplete="OFF" data-items="7">
						<div class="col-xs-12 admin-typeahead" id="typeaheadCode<?=$data['id'];?>"></div>
						<input type='hidden' id='goodsId_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_id'];?>'>
					</td>
					<td>
						<div class="input-group">
							<input type=text class='form-control goods-field-return goodsName' id='goodsName_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_name'];?>' autocomplete="OFF" data-items="7">
							<div class="col-xs-12 admin-typeahead" id="typeaheadName<?=$data['id'];?>"></div>
							<div class="input-group-btn">
								<button  class="btn btn-default" onclick="javascript: openSearchModal(<?=$data['id'];?>);"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</td>
					<td>
						<input type=text class='form-control goods-field-return goodsComment' id='goodsComment_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['comment'];?>'>
					</td>
					<td>
						<input type=text class='form-control goods-field-return goodsNum' id='goodsNum_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['num'];?>'>
					</td>
					<td>
						<form action='/admin/return/?return=<?=$return_id;?>' method='post'>
							<input type='hidden' name='removeReturnPosition' value='<?=$data['id'];?>'>
							<button type='submit' class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr>
					<?
					} else if($data['return_status'] == 2){?>
				<tr>
					<td>
						<?=$data['product_code'];?>
					</td>
					<td class='text-left'>
						<?=$data['product_name'];?>
					</td>
					<td class='text-left'>
						<?=$data['comment'];?>
					</td>
					<td>
						<?=$data['num'];?>
					</td>
					<td>
					</td>
				</tr>
					<?
					}
					$i++;
				}
				?>
				<?if(Model::factory('Admin')->getReturnStatus($return_id) == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-return goodsCode' id='goodsCode_0' row='0' value='' autocomplete="OFF" data-items="7">
						<div class="col-xs-12 admin-typeahead" id="typeaheadCode0"></div>
						<input type='hidden' id='goodsId_0' row='0' value=''>
					</td>
					<td>
						<div class="input-group">
							<input type=text class='form-control goods-field-return goodsName' id='goodsName_0' row='0' value='' autocomplete="OFF" data-items="7">
							<div class="col-xs-12 admin-typeahead" id="typeaheadName0"></div>
							<div class="input-group-btn">
								<button  class="btn btn-default" onclick="javascript: openSearchModal(0);"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</td>
					<td>
						<input type=text class='form-control goods-field-return goodsComment' id='goodsComment_0' row='0' value=''>
					</td>
					<td>
						<input type=text class='form-control goods-field-return goodsNum' id='goodsNum_0' row='0' value='0'>
					</td>
					<td>
					</td>
				</tr>
				<?}?>
			</tbody>
		</table>
		<?if(Model::factory('Admin')->getReturnStatus($return_id) == 1){?>
		<form action='/admin/return/?return=<?=$return_id;?>' method='post' class='pull-left'>
			<button class='btn btn-success' name='carryOutReturn' value='<?=$return_id;?>'>Провести</button>
		</form>
		<?}?>
	</div>
	<input type='hidden' id='returnId' value='<?=$return_id;?>'>
</div>