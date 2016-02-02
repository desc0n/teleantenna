<div class="row">
	<h2 class="sub-header col-sm-12">Отчет по движению товара</h2>
	<div class="col-sm-12">
		<form action="/admin/report" method="get" class="pull-left col-sm-12">
			<?=(Arr::get($get,'archive', '') == 'report' ? '<input type="hidden" name="action" value="reports">' : '<input type="hidden" name="archive" value="report"><input type="hidden" name="action" value="reports">');?>
			<button class="btn btn-success" type="submit"><?=(Arr::get($get,'archive', '') == 'report' ? 'За день' : 'Архив');?></button>
		</form>
		<div class="col-sm-12">
		<?
		if(ceil($reportsCount) > 0 && Arr::get($get,'archive', '') == 'report'){
			$reportStartPage = (Arr::get($get,'reportPage', 1) - 2) < 1 ? 1 : (Arr::get($get,'reportPage', 1) - 2);
			$reportPageLimit = (Arr::get($get,'reportPage', 1) + 3) > ceil($reportsCount) ? ceil($reportsCount) : (Arr::get($get,'reportPage', 1) + 3);
			?>
			<h1></h1>
			<div class="btn-toolbar pull-left" role="toolbar">
				<div class="btn-group">
					<?for ($p=$reportStartPage;$p<$reportPageLimit;$p++){?>
						<a type="button" href="/admin/report/<?=$getString;?>&reportPage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'reportPage', 1) ? ' active' : '');?>"><?=$p;?></a>
					<?}?>
				</div>
				<?=(ceil($reportsCount) > 2 && $reportPageLimit != ceil($reportsCount) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
				<?=(ceil($reportsCount) > 1 ? '<div class="btn-group"><a type="button" href="/admin/report/'.(!empty($getString) ? $getString.'&reportPage='.ceil($reportsCount) : '?reportPage='.ceil($reportsCount)).'" class="btn btn-default'.(ceil($reportsCount) == Arr::get($get,'reportPage', 1) ? ' active' : '').'">'.ceil($reportsCount).'</a></div>' : '');?>
			</div>
		<?}?>
		</div>
	</div>
	<div class="col-sm-11 redact-form">
		<h1></h1>
		<table class="table table-hover table-bordered table-striped order-list-table">
			<thead>
			<tr>
				<th class="col-sm-1 text-center">Дата</th>
				<th>Товар</th>
				<th class="col-sm-1 text-center">Кол-во в начале</th>
				<th class="col-sm-1 text-center">Кол-во в документе</th>
				<th class="col-sm-1 text-center">Кол-во в конце</th>
				<th class="col-sm-1 text-center">Документ</th>
				<th class="col-sm-1 text-center">Контрагент</th>
			</tr>
			</thead>
			<tbody>
			<?
			$i = 1;
			$docName = ['realization' => 'Реализация', 'income' => 'Поступление', 'return' => 'Возврат', 'writeoff' => 'Списание'];

			if (count($reportsList) === 0) {
			?>
			<tr>
				<td colspan="7"><h4>В этот день движения товара не было</h4></td>
			</tr>
			<?
			}
			foreach ($reportsList as $data){
				?>
				<tr>
                    <td class="text-center">
						<a href="/admin/<?=$data['document'];?>/?<?=$data['document'];?>=<?=$data['document_id'];?>" target="_blank">#<?=$data['document_id'];?></a><br>
						<?=date("d.m.Y H:i", strtotime($data['date']));?>
					</td>
					<td>
						<?=$data['product_name'];?>
					</td>
					<td class="text-center">
						<?=$data['root_num'];?>
					</td>
					<td class="text-center">
						<?=$data['num'];?>
					</td>
					<td class="text-center">
						<?=$data['new_num'];?>
					</td>
					<td class="text-center">
						<?=Arr::get($docName, $data['document'], '');?>
					</td>
                    <td class="text-center">
                        <?=$data['contractor'];?>
                    </td>
				</tr>
			<?
			}
			?>
			</tbody>
		</table>
	</div>
</div>