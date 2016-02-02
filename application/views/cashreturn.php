<div class="row">
	<h2 class="sub-header col-sm-12">Возврат № <?=$cashreturn_id;?></h2>
	<div class="col-sm-11">
		<table class="table table-hover table-bordered table-striped cashreturn-table">
			<thead>
				<tr>
					<th>Комментарий</th>
					<th class="col-sm-1 text-center col-num">Сумма</th>
					<th class="col-sm-1">Действия</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 1;
				foreach ($cashreturnData as $data){
					if($data['cashreturn_status'] == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-cashreturn goodsComment' id='goodsComment_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['comment'];?>'>
					</td>
					<td>
						<input type=text class='form-control goods-field-cashreturn goodsPrice' id='goodsPrice_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['price'];?>'>
					</td>
					<td class='text-center'>
						<form action='/admin/cashreturn/?cashreturn=<?=$cashreturn_id;?>' method='post'>
							<input type='hidden' name='removeCashreturnPosition' value='<?=$data['id'];?>'>
							<button type='submit' class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr>
					<?
					} else if($data['cashreturn_status'] == 2){?>
				<tr>
					<td class='text-left'>
						<?=$data['comment'];?>
					</td>
					<td class='text-center'>
						<?=$data['price'];?>
					</td>
					<td>
					</td>
				</tr>
					<?
					}
					$i++;
				}
				?>
				<?if(Model::factory('Admin')->getCashreturnStatus($cashreturn_id) == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-cashreturn goodsComment' id='goodsComment_0' row='0' value=''>
					</td>
					<td>
						<input type=text class='form-control goods-field-cashreturn goodsPrice' id='goodsPrice_0' row='0' value='0'>
					</td>
					<td>
					</td>
				</tr>
				<?}?>
			</tbody>
		</table>
		<?if(Model::factory('Admin')->getCashreturnStatus($cashreturn_id) == 1){?>
		<form action='/admin/cashreturn/?cashreturn=<?=$cashreturn_id;?>' method='post' class='pull-left'>
			<button class='btn btn-success' name='carryOutCashreturn' value='<?=$cashreturn_id;?>'>Провести</button>
		</form>
		<?}?>
	</div>
	<input type='hidden' id='cashreturnId' value='<?=$cashreturn_id;?>'>
</div>