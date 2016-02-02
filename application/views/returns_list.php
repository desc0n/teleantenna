<div class="row">
	<h2 class="sub-header col-sm-12">Список возвратов</h2>
	<div class="col-sm-11">
		<table class="table table-hover table-bordered table-striped return-list-table">
			<thead>
				<tr>
					<th class="col-sm-1 col-code">№</th>
					<th>Дата оформления</th>
					<th>Ответственный</th>
					<th class="col-sm-2 text-center col-price">Статус</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 1;
				foreach ($returnsList as $data){
					?>
				<tr onclick="document.location='/admin/return/?return=<?=$data['id'];?>';">
					<td>
						<?=$data['id'];?>
					</td>
					<td>
						<?=$data['date'];?>
					</td>
					<td>
						<?=$data['manager_name'];?>
					</td>
					<td>
						<?=$data['status_name'];?>
					</td>
				</tr>
					<?
				}
				?>
			</tbody>
		</table>
	</div>
</div>