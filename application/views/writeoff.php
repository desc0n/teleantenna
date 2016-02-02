<div class="row">
	<div class="col-sm-12">
		<ol class="breadcrumb">
			<li><a href="/admin">Операции с товаром</a></li>
			<li><a href="/admin/?action=writeoffs">Список списаний</a></li>
			<li class="active">Списание</li>
		</ol>
	</div>
	<h2 class="sub-header col-sm-12">Списание № <?=$writeoff_id;?></h2>
	<div class="col-sm-11">
		<table class="table table-hover table-bordered table-striped writeoff-table">
			<thead>
				<tr>
					<th class="col-sm-1 col-code">Код</th>
					<th>Наименование</th>
					<th class="col-sm-3">Комментарий</th>
					<th class="col-sm-1 text-center col-num" colspan="">Количество (шт.)</th>
					<th class="col-sm-1 text-center col-num" colspan="">Действия</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 1;
				$checkZero = 0;
				$checkMissed = 0;
				foreach ($writeoffData as $data){
					if($data['writeoff_status'] == 1){
						$checkZero += $data['num'];
						$checkMissed += $data['root_num'] < $data['num'] ? 1 : 0;
						?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-writeoff goodsCode' id='goodsCode_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_code'];?>' autocomplete="OFF" data-items="7">
						<input type='hidden' id='goodsId_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_id'];?>'>
						<div class="col-xs-12 admin-typeahead" id="typeaheadCode<?=$data['id'];?>"></div>
					</td>
					<td>
						<div class="input-group">
							<input type=text class='form-control goods-field-writeoff goodsName' id='goodsName_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_name'];?>' autocomplete="OFF" data-items="7">
							<div class="col-xs-12 admin-typeahead" id="typeaheadName<?=$data['id'];?>"></div>
							<div class="input-group-btn">
								<button  class="btn btn-default" onclick="javascript: openSearchModal(<?=$data['id'];?>);"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</td>
					<td>
						<input type=text class='form-control goods-field-writeoff goodsComment' id='goodsComment_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['comment'];?>'>
					</td>
					<td>
						<input type=text class='form-control goods-field-writeoff goodsNum' id='goodsNum_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['num'];?>'>
						<input type='hidden' id='goodsRootNum_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['root_num'];?>'>
					</td>
					<td>
						<form action='/admin/writeoff/?writeoff=<?=$writeoff_id;?>' method='post'>
							<input type='hidden' name='removeWriteoffPosition' value='<?=$data['id'];?>'>
							<button type='submit' class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr>
					<?
					} else if($data['writeoff_status'] == 2){?>
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
				<?if(Model::factory('Admin')->getWriteoffStatus($writeoff_id) == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-writeoff goodsCode' id='goodsCode_0' row='0' value='' autocomplete="OFF" data-items="7">
						<input type='hidden' id='goodsId_0' row='0' value=''>
						<div class="col-xs-12 admin-typeahead" id="typeaheadCode0"></div>
					</td>
					<td>
						<div class="input-group">
							<input type=text class='form-control goods-field-writeoff goodsName' id='goodsName_0' row='0' value='' autocomplete="OFF" data-items="7">
							<div class="col-xs-12 admin-typeahead" id="typeaheadName0"></div>
							<div class="input-group-btn">
								<button  class="btn btn-default" onclick="javascript: openSearchModal(0);"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</td>
					<td>
						<input type=text class='form-control goods-field-writeoff goodsComment' id='goodsComment_0' row='0' value=''>
					</td>
					<td>
						<input type=text class='form-control goods-field-writeoff goodsNum' id='goodsNum_0' row='0' value='0'>
						<input type='hidden' id='goodsRootNum_0' row='0' value='0'>
					</td>
					<td>
					</td>
				</tr>
				<?}?>
			</tbody>
		</table>
		<?if(Model::factory('Admin')->getWriteoffStatus($writeoff_id) == 1){
			if ($checkZero != 0) {
				if ($checkMissed == 0) {
					?>
		<form action='/admin/writeoff/?writeoff=<?=$writeoff_id;?>' method='post' class='pull-left'>
			<button class='btn btn-success' name='carryOutWriteoff' value='<?=$writeoff_id;?>'>Провести</button>
		</form>
			<?
				} else {
				?>
		<button class='btn btn-success' onclick="alert('Указанное количество больше наличия на складе!');">Провести</button>
				<?
				}
			} else {
				?>
		<button class='btn btn-success' onclick="alert('Не указано количество товара!');">Провести</button>
				<?
			}
		}?>
	</div>
	<input type='hidden' id='writeoffId' value='<?=$writeoff_id;?>'>
</div>