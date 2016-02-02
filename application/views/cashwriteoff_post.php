<div class="row">
	<h2 class="sub-header col-sm-12">Списание № <?=$cashwriteoff_id;?></h2>
	<div class="col-sm-11">
		<?if($document_status == 1){?>
		<form action='/admin/cashwriteoff/?cashwriteoff=<?=$cashwriteoff_id;?>' method='post'>
		<?}?>
		<table class="table table-hover table-bordered table-striped cashwriteoff-table">
			<thead>
				<tr>
					<th>Комментарий</th>
					<th class="col-sm-2 text-center col-num">Сумма</th>
					<th class="col-sm-1 text-center col-num">Действия</th>
				</tr>
			</thead>
			<tbody id='tableBody'>
				<?if($document_status == 1){?>
				<tr id='row1'>
					<td>
						<input type=text class='form-control' id='goodsComment_1' row='1' name='comment[]' value=''>
					</td>
					<td>
						<input type=text class='form-control' id='goodsPrice_1' row='1' name='price[]' value=''>
					</td>
					<td class='text-center'>
						<button type='button' class="btn btn-default" onclick="deleteRow(1);">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</td>
				</tr>
				<?
				} else if($document_status == 2){
					$i = 1;
					foreach ($cashwriteoffData as $data) {
						?>
						<tr>
							<td class='text-left'>
								<?=$data['comment']; ?>
							</td>
							<td class='text-center'>
								<?=$data['price']; ?>
							</td>
							<td>
							</td>
						</tr>
						<?
						$i++;
					}
				}
				?>
			</tbody>
			<?if($document_status == 1){?>
			<tr>
				<td colspan="5" class="text-left">
					<button type="button" class='btn btn-primary' id='addRowBtn' onclick="addCashRow();">Добавить строку</button>
				</td>
			</tr>
			<?}?>
		</table>
		<?if($document_status == 1){?>
		<button class='btn btn-success' name='carryOutCashwriteoffPost' value='<?=$cashwriteoff_id;?>'>Провести</button>
		</form>
		<?}?>
	</div>
	<input type='hidden' id='cashwriteoff' value='<?=$cashwriteoff_id;?>'>
	<input type='hidden' id='rowNum' value='1'>
</div>