<div class="row">
	<div class="col-sm-12">
		<ol class="breadcrumb">
			<li><a href="/admin">Операции с товаром</a></li>
			<li><a href="/admin/?action=realizations">Список реализаций</a></li>
			<li class="active">Реализация</li>
		</ol>
	</div>
	<h2 class="sub-header col-sm-12">Реализация № <?=$realization_id;?></h2>
	<h3 class="col-sm-2">Контрагент</h3>
	<h3 class="col-sm-5">
		<? if (Arr::get(Arr::get($realizationData, 0, []), 'realization_status', 1) == 1) {?>
		<select class="form-control" id="realizationContractor">
			<option value="0" <?=($contractor_id == 0 ? 'selected' : '');?>>Розничный покупатель (0%)</option>
			<?foreach ($contractorList as $contractorData) {?>
			<option value="<?=$contractorData['user_id'];?>" <?=($contractor_id == $contractorData['user_id'] ? 'selected' : '');?>><?=(!empty($contractorData['name']) ? $contractorData['name'] : $contractorData['username']);?> (<?=$contractorData['discount'];?> %)</option>
			<?}?>
		</select>
		<?} else {?>
			<?foreach ($contractorList as $contractorData) {
				if ($contractor_id == $contractorData['user_id']) {?>
					<?=(!empty($contractorData['name']) ? $contractorData['name'] : $contractorData['username']);?> (<?=$contractorData['discount'];?> %)
			<?}}?>
		<?}?>
	</h3>
	<div class="col-sm-11">
		<table class="table table-hover table-bordered table-striped realization-table">
			<thead>
				<tr>
					<th class="col-sm-1 col-code">Код</th>
					<th>Наименование</th>
					<th class="col-sm-1 text-center col-price">Цена</th>
					<th class="col-sm-1 text-center col-num" colspan="">Кол-во (шт.)</th>
					<th class="col-sm-1 text-center col-num" colspan="">Наличие (шт.)</th>
					<th class="col-sm-1 text-center col-num" colspan="">Действия</th>
				</tr>
			</thead>
			<tbody>
				<?
				$checkZero = 0;
				$checkMissed = 0;
				$i = 1;
				foreach ($realizationData as $data){
					if($data['realization_status'] == 1){
						$checkZero += $data['num'];
						$checkMissed += $data['root_num'] < $data['num'] ? 1 : 0;
						?>
				<tr>
					<td>
						<input type='hidden' id='goodsId_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_id'];?>'>
						<input type=text class='form-control goods-field-realization goodsCode' id='goodsCode_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_code'];?>' autocomplete="OFF" data-items="7">
						<div class="col-xs-12 admin-typeahead" id="typeaheadCode<?=$data['id'];?>"></div>
					</td>
					<td>
						<div class="input-group">
							<input type=text class='form-control goods-field-realization goodsName' id='goodsName_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['product_name'];?>' autocomplete="OFF" data-items="7">
							<div class="col-xs-12 admin-typeahead" id="typeaheadName<?=$data['id'];?>"></div>
							<div class="input-group-btn">
								<button  class="btn btn-default" onclick="javascript: openSearchModal(<?=$data['id'];?>);"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</td>
					<td>
						<input type=text class='form-control goods-field-realization goodsPrice' id='goodsPrice_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['price'];?>'>
					</td>
					<td>
						<input type=text class='form-control goods-field-realization goodsNum' id='goodsNum_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['num'];?>'>
					</td>
					<td>
						<span id='goodsRootNumHtml_<?=$data['id'];?>' row='<?=$data['id'];?>'><?=$data['root_num'];?></span>
						<input type='hidden' id='goodsRootNum_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['root_num'];?>'>
					</td>
					<td>
						<form action='/admin/realization/?realization=<?=$realization_id;?>' method='post'>
							<input type='hidden' name='removeRealizationPosition' value='<?=$data['id'];?>'>
							<button type='submit' class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr>
					<?
					} else if($data['realization_status'] == 2){?>
				<tr>
					<td>
						<?=$data['product_code'];?>
					</td>
					<td class='text-left'>
						<?=$data['product_name'];?>
					</td>
					<td>
						<?=$data['price'];?>
					</td>
					<td>
						<?=$data['num'];?>
					</td>
					<td>
						<?=$data['root_num'];?>
					</td>
					<td>
					</td>
				</tr>
					<?
					}
					$i++;
				}
				?>
				<?if(Model::factory('Admin')->getRealizationStatus($realization_id) == 1){?>
				<tr>
					<td>
						<input type='hidden' id='goodsId_0' row='0' value=''>
						<input type=text class='form-control goods-field-realization goodsCode' id='goodsCode_0' row='0' value='' autocomplete="OFF" data-items="7">
						<div class="col-xs-12 admin-typeahead" id="typeaheadCode0"></div>
					</td>
					<td>
						<div class="input-group">
							<input type=text class='form-control goods-field-realization goodsName' id='goodsName_0' row='0' value='' autocomplete="OFF" data-items="7">
							<div class="col-xs-12 admin-typeahead" id="typeaheadName0"></div>
							<div class="input-group-btn">
								<button  class="btn btn-default" onclick="javascript: openSearchModal(0);"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</td>
					<td>
						<input type=text class='form-control goods-field-realization goodsPrice' id='goodsPrice_0' row='0' value='0'>
					</td>
					<td>
						<input type=text class='form-control goods-field-realization goodsNum' id='goodsNum_0' row='0' value='0'>
					</td>
					<td>
						<span class='goodsRootNum' id='goodsRootNumHtml_0' row='0'>0</span>
						<input type='hidden' id='goodsRootNum_0' row='0' value='0'>
					</td>
					<td>
					</td>
				</tr>
				<?}?>
			</tbody>
		</table>
		<?if(Model::factory('Admin')->getRealizationStatus($realization_id) == 1){
			if ($checkZero != 0) {
				if ($checkMissed == 0) {
					?>
		<form id='carryOutRealizationForm' action='/admin/realization/?realization=<?=$realization_id;?>' method='post' class='pull-left' target='_blank'>
			<button class='btn btn-success' id='carryOutRealization' name='carryOutRealization' value='<?=$realization_id;?>'>Провести</button>
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
			} else if(Model::factory('Admin')->getRealizationStatus($realization_id) == 2){/*?>
		<form action='/admin/realization/?realization=<?=$realization_id;?>' method='post' class='pull-left'>
			<button class='btn btn-danger' name='createReturn' value='<?=$realization_id;?>'>Возврат</button>
		</form>
		<?*/}?>
	</div>
	<input type='hidden' id='realizationId' value='<?=$realization_id;?>'>
</div>
<?=(preg_match('/\.lan/i', $_SERVER['SERVER_NAME']) ? '' : View::factory('search_modal'));?>