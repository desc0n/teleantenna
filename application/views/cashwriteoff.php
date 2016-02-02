<div class="row">
	<h2 class="sub-header col-sm-12">Списание № <?=$cashwriteoff_id;?></h2>
	<div class="col-sm-11">
		<table class="table table-hover table-bordered table-striped cashwriteoff-table">
			<thead>
				<tr>
					<th>Комментарий</th>
					<th class="col-sm-2 text-center col-num">Сумма</th>
					<th class="col-sm-1">Действия</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 1;
				foreach ($cashwriteoffData as $data){
					if($data['cashwriteoff_status'] == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-cashwriteoff goodsComment' id='goodsComment_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['comment'];?>'>
					</td>
					<td>
						<input type=text class='form-control goods-field-cashwriteoff goodsPrice' id='goodsPrice_<?=$data['id'];?>' row='<?=$data['id'];?>' value='<?=$data['price'];?>'>
					</td>
					<td class='text-center'>
						<form action='/admin/cashwriteoff/?cashwriteoff=<?=$cashwriteoff_id;?>' method='post'>
							<input type='hidden' name='removeCashwriteoffPosition' value='<?=$data['id'];?>'>
							<button type='submit' class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr>
					<?
					} else if($data['cashwriteoff_status'] == 2){?>
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
				<?if(Model::factory('Admin')->getCashwriteoffStatus($cashwriteoff_id) == 1){?>
				<tr>
					<td>
						<input type=text class='form-control goods-field-cashwriteoff goodsComment' id='goodsComment_0' row='0' value=''>
					</td>
					<td>
						<input type=text class='form-control goods-field-cashwriteoff goodsPrice' id='goodsPrice_0' row='0' value='0'>
					</td>
					<td>
					</td>
				</tr>
				<?}?>
			</tbody>
		</table>
		<?if(Model::factory('Admin')->getCashwriteoffStatus($cashwriteoff_id) == 1){?>
		<form action='/admin/cashwriteoff/?cashwriteoff=<?=$cashwriteoff_id;?>' method='post' class='pull-left'>
			<button class='btn btn-success' name='carryOutCashwriteoff' value='<?=$cashwriteoff_id;?>'>Провести</button>
		</form>
		<?}?>
	</div>
	<input type='hidden' id='cashwriteoffId' value='<?=$cashwriteoff_id;?>'>
</div>