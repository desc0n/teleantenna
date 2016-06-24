<h1>Операции с кассой</h1>
<div class="row admin-main-page">
	<ul class="nav nav-tabs">
		<li <?=(empty($get['action']) || Arr::get($get,'action', '') == 'cashincomes' ? 'class="active"' : '');?>><a href="#cashincomes" data-toggle="tab">Поступление</a></li>
		<li <?=(Arr::get($get,'action', '') == 'cashwriteoffs' ? 'class="active"' : '');?>><a href="#cashwriteoffs" data-toggle="tab">Списание</a></li>
		<?/*<li <?=(Arr::get($get,'action', '') == 'cashreturns' ? 'class="active"' : '');?>><a href="#cashreturns" data-toggle="tab">Возврат</a></li>*/?>
		<li <?=(Arr::get($get,'action', '') == 'cashclose' ? 'class="active"' : '');?>><a href="#cashclose" data-toggle="tab">Закрытие</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane <?=(empty($get['action']) || Arr::get($get,'action', '') == 'cashincomes' ? 'active' : '');?>" id="cashincomes">
			<div class="forms-field">
				<form id="newCashincome" action="/admin/cashincome" method="post" class="pull-left">
					<input type="hidden" name="newcashincome">
					<button class="btn btn-danger" type="submit">Новое поступление</button>
				</form>
				<form action="/admin/cash" method="get" class="pull-left">
					<?=(Arr::get($get,'archive', '') == 'cashincome' ? '<input type="hidden" name="action" value="cashincomes">' : '<input type="hidden" name="archive" value="cashincome"><input type="hidden" name="action" value="cashincomes">');?>
					<button class="btn btn-success" type="submit"><?=(Arr::get($get,'archive', '') == 'cashincome' ? 'За день' : 'Архив');?></button>
				</form>
			</div>
			<?
			if(ceil($cashincomesCount/$limit) > 0){
				$cashincomeStartPage = (Arr::get($get,'cashincomePage', 1) - 2) < 1 ? 1 : (Arr::get($get,'cashincomePage', 1) - 2);
				$cashincomePageLimit = (Arr::get($get,'cashincomePage', 1) + 3) > ceil($cashincomesCount/$limit) ? ceil($cashincomesCount/$limit) : (Arr::get($get,'cashincomePage', 1) + 3);
				?>
				<div class="btn-toolbar row" role="toolbar">
					<div class="btn-group">
						<?for ($p=$cashincomeStartPage;$p<$cashincomePageLimit;$p++){?>
							<a type="button" href="/admin/cash<?=$getString;?>&cashincomePage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'cashincomePage', 1) ? ' active' : '');?>"><?=$p;?></a>
						<?}?>
					</div>
					<?=(ceil($cashincomesCount/$limit) > 2 && $cashincomePageLimit != ceil($cashincomesCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
					<?=(ceil($cashincomesCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/cash'.(!empty($getString) ? $getString.'&cashincomePage='.ceil($cashincomesCount/$limit) : '?cashincomePage='.ceil($cashincomesCount/$limit)).'" class="btn btn-default'.(ceil($cashincomesCount/$limit) == Arr::get($get,'cashincomePage', 1) ? ' active' : '').'">'.ceil($cashincomesCount/$limit).'</a></div>' : '');?>
				</div>
				<h2></h2>
			<?}?>
			<h2 class="sub-header col-sm-12">Список поступлений <?=(Arr::get($get,'archive', '') == 'cashincome' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped cashincome-list-table">
					<thead>
					<tr>
						<th class="col-sm-1">Дата оформления</th>
						<th>Комментарий</th>
						<th class="col-sm-1">Сумма</th>
						<th class="col-sm-1">Ответственный</th>
						<th class="col-sm-1 text-center">Статус</th>
					</tr>
					</thead>
					<tbody>
					<?
					$i = 1;
					foreach ($cashincomesList as $data){
						?>
						<tr onclick="document.location='/admin/cashincome/?cashincome=<?=$data['id'];?>';">
							<td class="text-center">
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Admin')->getCashincomeData($data['id']) as $cashincomeData){
									?>
									<div class="text-left">
										<?=$cashincomeData['comment'];?>
									</div>
									<?
									$summ += $cashincomeData['price'];
								}
								?>
							</td>
							<td>
								<?=$summ;?>
							</td>
							<td class="text-center">
								<?=$data['manager_name'];?>
							</td>
							<td class="text-center">
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
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'cashwriteoffs' ? 'active' : '');?>" id="cashwriteoffs">
			<div class="forms-field">
				<form id="newCashwriteoff" action="/admin/cashwriteoff" method="post" class="pull-left">
					<input type="hidden" name="newcashwriteoff">
					<button class="btn btn-danger" type="submit">Новое списание</button>
				</form>
				<form action="/admin/cash" method="get" class="pull-left">
					<?=(Arr::get($get,'archive', '') == 'cashwriteoff' ? '<input type="hidden" name="action" value="cashwriteoffs">' : '<input type="hidden" name="archive" value="cashwriteoff"><input type="hidden" name="action" value="cashwriteoffs">');?>
					<button class="btn btn-success" type="submit"><?=(Arr::get($get,'archive', '') == 'cashwriteoff' ? 'За день' : 'Архив');?></button>
				</form>
			</div>
			<?
			if(ceil($cashwriteoffsCount/$limit) > 0){
				$cashwriteoffStartPage = (Arr::get($get,'cashwriteoffPage', 1) - 2) < 1 ? 1 : (Arr::get($get,'cashwriteoffPage', 1) - 2);
				$cashwriteoffPageLimit = (Arr::get($get,'cashwriteoffPage', 1) + 3) > ceil($cashwriteoffsCount/$limit) ? ceil($cashwriteoffsCount/$limit) : (Arr::get($get,'cashwriteoffPage', 1) + 3);
				?>
				<h4></h4>
				<div class="btn-toolbar row" role="toolbar">
					<div class="btn-group">
						<?for ($p=$cashwriteoffStartPage;$p<$cashwriteoffPageLimit;$p++){?>
							<a type="button" href="/admin/cash<?=$getString;?>&cashwriteoffPage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'cashwriteoffPage', 1) ? ' active' : '');?>"><?=$p;?></a>
						<?}?>
					</div>
					<?=(ceil($cashwriteoffsCount/$limit) > 2 && $cashwriteoffPageLimit != ceil($cashwriteoffsCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
					<?=(ceil($cashwriteoffsCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/cash'.(!empty($getString) ? $getString.'&cashwriteoffPage='.ceil($cashwriteoffsCount/$limit) : '?cashwriteoffPage='.ceil($cashwriteoffsCount/$limit)).'" class="btn btn-default'.(ceil($cashwriteoffsCount/$limit) == Arr::get($get,'cashwriteoffPage', 1) ? ' active' : '').'">'.ceil($cashwriteoffsCount/$limit).'</a></div>' : '');?>
				</div>
				<h2></h2>
			<?}?>
			<h2 class="sub-header col-sm-12">Список списаний <?=(Arr::get($get,'archive', '') == 'cashwriteoff' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped cashwriteoff-list-table">
					<thead>
					<tr>
						<th class="col-sm-1">Дата оформления</th>
						<th>Комментарий</th>
						<th class="col-sm-1">Сумма</th>
						<th class="col-sm-1">Ответственный</th>
						<th class="col-sm-1 text-center">Статус</th>
					</tr>
					</thead>
					<tbody>
					<?
					$i = 1;
					foreach ($cashwriteoffsList as $data){
						?>
						<tr onclick="document.location='/admin/cashwriteoff/?cashwriteoff=<?=$data['id'];?>';">
							<td class="text-center">
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Admin')->getCashwriteoffData($data['id']) as $cashwriteoffData){
									?>
									<div class="text-left">
										<?=$cashwriteoffData['comment'];?>
									</div>
								<?
									$summ += $cashwriteoffData['price'];
								}
								?>
							</td>
							<td class="text-center">
								<?=$summ;?>
							</td>
							<td class="text-center">
								<?=$data['manager_name'];?>
							</td>
							<td class="text-center">
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
		<?/*<div class="tab-pane <?=(Arr::get($get,'action', '') == 'cashreturns' ? 'active' : '');?>" id="cashreturns">
			<div class="forms-field">
				<form id="newCashreturn" action="/admin/cashreturn" method="post" class="pull-left">
					<input type="hidden" name="newcashreturn">
					<button class="btn btn-danger" type="submit">Новый возврат</button>
				</form>
				<form action="/admin/cash" method="get" class="pull-left">
					<?=(Arr::get($get,'archive', '') == 'cashreturn' ? '<input type="hidden" name="action" value="cashreturns">' : '<input type="hidden" name="archive" value="cashreturn"><input type="hidden" name="action" value="cashreturns">');?>
					<button class="btn btn-success" type="submit"><?=(Arr::get($get,'archive', '') == 'cashreturn' ? 'За день' : 'Архив');?></button>
				</form>
			</div>
			<?
			if(ceil($cashreturnsCount/$limit) > 0){
				$cashreturnStartPage = (Arr::get($get,'cashreturnPage', 1) - 2) < 1 ? 1 : (Arr::get($get,'cashreturnPage', 1) - 2);
				$cashreturnPageLimit = (Arr::get($get,'cashreturnPage', 1) + 3) > ceil($cashreturnsCount/$limit) ? ceil($cashreturnsCount/$limit) : (Arr::get($get,'cashreturnPage', 1) + 3);
				?>
				<div class="btn-toolbar row" role="toolbar">
					<div class="btn-group">
						<?for ($p=$cashreturnStartPage;$p<$cashreturnPageLimit;$p++){?>
							<a type="button" href="/admin/cash<?=$getString;?>&cashreturnPage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'cashreturnPage', 1) ? ' active' : '');?>"><?=$p;?></a>
						<?}?>
					</div>
					<?=(ceil($cashreturnsCount/$limit) > 2 && $cashreturnPageLimit != ceil($cashreturnsCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
					<?=(ceil($cashreturnsCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/cash'.(!empty($getString) ? $getString.'&cashreturnPage='.ceil($cashreturnsCount/$limit) : '?cashreturnPage='.ceil($cashreturnsCount/$limit)).'" class="btn btn-default'.(ceil($cashreturnsCount/$limit) == Arr::get($get,'cashreturnPage', 1) ? ' active' : '').'">'.ceil($cashreturnsCount/$limit).'</a></div>' : '');?>
				</div>
				<h2></h2>
			<?}?>
			<h2 class="sub-header col-sm-12">Список возвратов <?=(Arr::get($get,'archive', '') == 'cashreturn' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped cashreturn-list-table">
					<thead>
					<tr>
						<th class="col-sm-1">Дата оформления</th>
						<th>Комментарий</th>
						<th class="col-sm-1">Сумма</th>
						<th class="col-sm-1">Ответственный</th>
						<th class="col-sm-1 text-center">Статус</th>
					</tr>
					</thead>
					<tbody>
					<?
					$i = 1;
					foreach ($cashreturnsList as $data){
						?>
						<tr onclick="document.location='/admin/cashreturn/?cashreturn=<?=$data['id'];?>';">
							<td class="text-center">
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Admin')->getCashreturnData($data['id']) as $cashreturnData){
									?>
									<div class="text-left">
										<?=$cashreturnData['comment'];?>
									</div>
								<?
									$summ += $cashreturnData['price'];
								}
								?>
							</td>
							<td class="text-center">
								<?=$summ;?>
							</td>
							<td class="text-center">
								<?=$data['manager_name'];?>
							</td>
							<td class="text-center">
								<?=$data['status_name'];?>
							</td>
						</tr>
					<?
					}
					?>
					</tbody>
				</table>
			</div>
		</div>*/?>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'cashclose' ? 'active' : '');?>" id="cashclose">
			<div class="forms-field">
				<form action="/admin/cash" method="get">
					<?=(Arr::get($get,'archive', '') == 'cashclose' ? '<input type="hidden" name="action" value="cashclose">' : '<input type="hidden" name="archive" value="cashclose"><input type="hidden" name="action" value="cashclose">');?>
					<button class="btn btn-success" type="submit"><?=(Arr::get($get,'archive', '') == 'cashclose' ? 'За день' : 'Архив');?></button>
				</form>
			</div>
			<?
			if(ceil($cashcloseCount/$limit) > 0){
				$cashcloseStartPage = (Arr::get($get,'cashclosePage', 1) - 2) < 1 ? 1 : (Arr::get($get,'cashclosePage', 1) - 2);
				$cashclosePageLimit = (Arr::get($get,'cashclosePage', 1) + 3) > ceil($cashcloseCount/$limit) ? ceil($cashcloseCount/$limit) : (Arr::get($get,'cashclosePage', 1) + 3);
				?>
				<div class="btn-toolbar row" role="toolbar">
					<div class="btn-group">
						<?for ($p=$cashcloseStartPage;$p<$cashclosePageLimit;$p++){?>
							<a type="button" href="/admin/cash<?=$getString;?>&cashclosePage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'cashclosePage', 1) ? ' active' : '');?>"><?=$p;?></a>
						<?}?>
					</div>
					<?=(ceil($cashcloseCount/$limit) > 2 && $cashclosePageLimit != ceil($cashcloseCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
					<?=(ceil($cashcloseCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/cash'.(!empty($getString) ? $getString.'&cashclosePage='.ceil($cashcloseCount/$limit) : '?cashclosePage='.ceil($cashcloseCount/$limit)).'" class="btn btn-default'.(ceil($cashcloseCount/$limit) == Arr::get($get,'cashclosePage', 1) ? ' active' : '').'">'.ceil($cashcloseCount/$limit).'</a></div>' : '');?>
				</div>
				<h2></h2>
			<?}?>
			<form action='/admin/cash' method='post'>
				<table class="table table-hover table-bordered table-striped closecash-table">
					<tr>
						<th>
							Магазин
						</th>
						<th>
							Значение по документам
						</th>
						<th>
							Сумма реализаций
						</th>
						<th>
							Фактическое наличие
						</th>
						<th>
							Действия
						</th>
					</tr>
					<?
					if(Arr::get($get,'archive', '') == 'cashclose') {
						foreach($cashCloseList as $data){
							?>
					<tr>
						<td>
							<?=$data['shop_name'];?>
						</td>
						<td>
							<?=$data['doc_cash'];?>
						</td>
						<td>
							<?=$data['real_cash'];?>
						</td>
						<td>
							<?=$data['fact_cash'];?>
						</td>
						<td class='text-center'>
							<?=date("d.m.Y", strtotime($data['date']));?>
						</td>
					</tr>
							<?
						}
					} else {
						if(empty(Arr::get($rootCash[1], 'fact_cash'))) {
							?>
					<tr>
						<td>
							<?=Arr::get($rootCash[3], 'name');?>
						</td>
						<td>
							<?=$rootCash[0];?>
							<input type=hidden name='docCash' value="<?=$rootCash[0];?>">
						</td>
						<td>
							<?=$rootCash[2];?>
							<input type=hidden name='realCash' value="<?=$rootCash[2];?>">
						</td>
						<td>
							<input type=text class='form-control' name='factCash'>
						</td>
						<td class='text-center'>
							<input type='hidden' name='closeCash' value='true'>
							<button type='submit' class="btn btn-danger"><span class="glyphicon glyphicon-ok"></span> Закрыть кассу</button>
						</td>
					</tr>
					<?} else {?>
					<tr>
						<td>
							<?=Arr::get($rootCash[3], 'name');?>
						</td>
						<td>
							<?=$rootCash[0];?>
						</td>
						<td>
							<?=$rootCash[2];?>
						</td>
						<td>
							<?=Arr::get($rootCash[1], 'fact_cash', 0);?>
						</td>
						<td class='text-center'>
						</td>
					</tr>
					<?
						}
					}
					?>
				</table>
			</form>
		</div>
	</div>
</div>