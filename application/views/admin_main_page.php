<h1>Операции с товаром</h1>
<div class="row admin-main-page">
	<ul class="nav nav-tabs">
		<li <?=(Arr::get($get,'action', '') == 'orders' ? 'class="active"' : '');?>><a href="#orders" data-toggle="tab">Заказы</a></li>
		<li <?=((empty($get['action']) || Arr::get($get,'action', '') == 'realizations') ? 'class="active"' : '');?>><a href="#realizations" data-toggle="tab">Реализация</a></li>
		<li <?=(Arr::get($get,'action', '') == 'incomes' ? 'class="active"' : '');?>><a href="#incomes" data-toggle="tab">Поступление</a></li>
		<?/*<li <?=(Arr::get($get,'action', '') == 'returns' ? 'class="active"' : '');?>><a href="#returns" data-toggle="tab">Возврат</a></li>*/?>
		<li <?=(Arr::get($get,'action', '') == 'writeoffs' ? 'class="active"' : '');?>><a href="#writeoffs" data-toggle="tab">Списание</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane  <?=(Arr::get($get,'action', '') == 'orders' ? 'active' : '');?>" id="orders">
			<h2 class="sub-header col-sm-12">Список заказов <?=(Arr::get($get,'archive', '') == 'orders' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped realization-list-table">
					<thead>
					<tr>
						<th class="col-sm-1 col-xs-1 col-md-1">Номер / Дата оформления</th>
						<th>Товар</th>
						<th class="col-sm-1 col-xs-1 col-md-1">Сумма</th>
						<th class="col-sm-1 col-xs-1 col-md-1 text-center">Статус</th>
					</tr>
					</thead>
					<tbody>
					<?
					$i = 1;
					foreach ($ordersList as $data){
						?>
						<tr onclick="document.location='/admin/order/?order=<?=$data['id'];?>';">
							<td>
								<div>#<?=$data['id'];?></div>
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Order')->getOrderData($data['id']) as $orderData){
									?>
									<div class="text-left">
										<?=$orderData['product_name'];?>  (<b><?=$orderData['price'];?></b> р. x <b><?=$orderData['num'];?></b> шт.)
									</div>
									<?
									$summ += $orderData['price']*$orderData['num'];
								}
								?>
							</td>
							<td>
								<?=$summ;?>
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
		<div class="tab-pane  <?=((empty($get['action']) || Arr::get($get,'action', '') == 'realizations') ? 'active' : '');?>" id="realizations">
			<div class="row">
				<form id="newRealization" action="/admin/realization" method="post" class="pull-left">
					<input type="hidden" name="newrealization">
					<button class="btn btn-danger" type="submit">Новая реализация</button>
				</form>
				<form action="/admin" method="get" class="pull-left col-sm-7 col-xs-7 col-md-7">
					<?//=(Arr::get($get,'archive', '') == 'realization' ? '<input type="hidden" name="action" value="realizations">' : '<input type="hidden" name="archive" value="realization"><input type="hidden" name="action" value="realizations">');?>
					<div class='col-sm-4 col-xs-4 col-md-4'>
						<div class="form-group">
							<div class='input-group date datetimepicker'>
								<input type='text' class="form-control" name="realizations_first_date" value="<?=Arr::get($get,'realizations_first_date');?>"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<div class='col-sm-4 col-xs-4 col-md-4'>
						<div class="form-group">
							<div class='input-group date datetimepicker'>
								<input type='text' class="form-control"  name="realizations_last_date" value="<?=Arr::get($get,'realizations_last_date');?>"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<input type="hidden" name="action" value="realizations">
					<button class="btn btn-success" type="submit">Фильтровать</button>
				</form>
				<?
				if(ceil($realizationsCount/$limit) > 0){
					$realizationStartPage = (Arr::get($get,'realizationPage', 1) - 2) < 1 ? 1 : (Arr::get($get,'realizationPage', 1) - 2);
					$realizationPageLimit = (Arr::get($get,'realizationPage', 1) + 3) > ceil($realizationsCount/$limit) ? ceil($realizationsCount/$limit) : (Arr::get($get,'realizationPage', 1) + 3);
					?>
				<div class="btn-toolbar" role="toolbar">
					<div class="btn-group">
						<?for ($p=$realizationStartPage;$p<$realizationPageLimit;$p++){?>
						<a type="button" href="/admin/<?=$getString;?>&realizationPage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'realizationPage', 1) ? ' active' : '');?>"><?=$p;?></a>
						<?}?>
					</div>
					<?=(ceil($realizationsCount/$limit) > 2 && $realizationPageLimit != ceil($realizationsCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
					<?=(ceil($realizationsCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/'.(!empty($getString) ? $getString.'&realizationPage='.ceil($realizationsCount/$limit) : '?realizationPage='.ceil($realizationsCount/$limit)).'" class="btn btn-default'.(ceil($realizationsCount/$limit) == Arr::get($get,'realizationPage', 1) ? ' active' : '').'">'.ceil($realizationsCount/$limit).'</a></div>' : '');?>
				</div>
				<?}?>
			</div>
			<h2 class="sub-header col-sm-12">Список реализаций <?//=(Arr::get($get,'archive', '') == 'realization' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped realization-list-table">
					<thead>
						<tr>
							<th class="col-sm-1 col-xs-1 col-md-1">Номер / Дата оформления</th>
							<th>Товар</th>
							<th class="col-sm-1 col-xs-1 col-md-1">Сумма</th>
							<th class="col-sm-1 col-xs-1 col-md-1">Ответственный</th>
							<th class="col-sm-1 col-xs-1 col-md-1 text-center">Статус</th>
							<th class="col-sm-1 col-xs-1 col-md-1 text-center">Печать</th>
						</tr>
					</thead>
					<tbody>
						<?
						$i = 1;
						foreach ($realizationsList as $data){
							?>
						<tr onclick="document.location='/admin/realization/?realization=<?=$data['id'];?>';">
							<td>
								<div>#<?=$data['id'];?></div>
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Admin')->getRealizationData($data['id']) as $realizationData){
									?>
									<div class="text-left">
										<?=$realizationData['product_name'];?>  (<b><?=$realizationData['price'];?></b> р. x <b><?=$realizationData['num'];?></b> шт.)
									</div>
									<?
									$summ += $realizationData['price']*$realizationData['num'];
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
							<td class="text-center">
								<a id="printRealisation<?=$data['id'];?>" href="/admin/print_realization/?realization=<?=$data['id'];?>" class="btn btn-default" target="_blank"><span class="glyphicon glyphicon-print"></span></a>
							</td>
						</tr>
							<?
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'incomes' ? 'active' : '');?>" id="incomes">
			<div class="row">
				<form id="newIncome" action="/admin/income" method="post" class="pull-left">
					<input type="hidden" name="newincome">
					<button class="btn btn-danger" type="submit">Новое поступление</button>
				</form>
				<form action="/admin" method="get" class="pull-left col-sm-7 col-xs-7 col-md-7">
					<?//=(Arr::get($get,'archive', '') == 'income' ? '<input type="hidden" name="action" value="incomes">' : '<input type="hidden" name="archive" value="income"><input type="hidden" name="action" value="incomes">');?>
					<div class='col-sm-4 col-xs-4 col-md-4'>
						<div class="form-group">
							<div class='input-group date datetimepicker'>
								<input type='text' class="form-control" name="incomes_first_date" value="<?=Arr::get($get,'incomes_first_date');?>"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<div class='col-sm-4 col-xs-4 col-md-4'>
						<div class="form-group">
							<div class='input-group date datetimepicker'>
								<input type='text' class="form-control"  name="incomes_last_date" value="<?=Arr::get($get,'incomes_last_date');?>"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<input type="hidden" name="action" value="incomes">
					<button class="btn btn-success" type="submit">Фильтровать</button>
				</form>
				<?
				if(ceil($incomesCount/$limit) > 0){
					$incomeStartPage = (Arr::get($get,'incomePage', 1) - 2) < 1 ? 1 : (Arr::get($get,'incomePage', 1) - 2);
					$incomePageLimit = (Arr::get($get,'incomePage', 1) + 3) > ceil($incomesCount/$limit) ? ceil($incomesCount/$limit) : (Arr::get($get,'incomePage', 1) + 3);
					?>
					<div class="btn-toolbar pull-left" role="toolbar">
						<div class="btn-group">
							<?for ($p=$incomeStartPage;$p<$incomePageLimit;$p++){?>
								<a type="button" href="/admin/<?=$getString;?>&incomePage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'incomePage', 1) ? ' active' : '');?>"><?=$p;?></a>
							<?}?>
						</div>
						<?=(ceil($incomesCount/$limit) > 2 && $incomePageLimit != ceil($incomesCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
						<?=(ceil($incomesCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/'.(!empty($getString) ? $getString.'&incomePage='.ceil($incomesCount/$limit) : '?incomePage='.ceil($incomesCount/$limit)).'" class="btn btn-default'.(ceil($incomesCount/$limit) == Arr::get($get,'incomePage', 1) ? ' active' : '').'">'.ceil($incomesCount/$limit).'</a></div>' : '');?>
					</div>
				<?}?>
			</div>
			<h2 class="sub-header col-sm-12">Список поступлений <?=(Arr::get($get,'archive', '') == 'income' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped income-list-table">
					<thead>
						<tr>
							<th class="col-sm-1">Дата оформления</th>
							<th>Товар</th>
							<th class="col-sm-1">Ответственный</th>
							<th class="col-sm-1 text-center">Статус</th>
						</tr>
					</thead>
					<tbody>
						<?
						$i = 1;
						foreach ($incomesList as $data){
							?>
						<tr onclick="document.location='/admin/income/?income=<?=$data['id'];?>';">
							<td>
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Admin')->getIncomeData($data['id']) as $incomeData){
									?>
									<div class="text-left">
										<?=$incomeData['product_name'];?> <b><?=$incomeData['num'];?></b> шт. <?=(!empty($incomeData['comment']) ? '('.$incomeData['comment'].')' : '');?>
									</div>
									<?
									$summ += $incomeData['price']*$incomeData['num'];
								}
								?>
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
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'writeoffs' ? 'active' : '');?>" id="writeoffs">
			<div class="row">
				<form id="newWriteoff" action="/admin/writeoff" method="post" class="pull-left">
					<input type="hidden" name="newwriteoff">
					<button class="btn btn-danger" type="submit">Новое списание</button>
				</form>
				<form action="/admin" method="get" class="pull-left">
					<?=(Arr::get($get,'archive', '') == 'writeoff' ? '<input type="hidden" name="action" value="writeoffs">' : '<input type="hidden" name="archive" value="writeoff"><input type="hidden" name="action" value="writeoffs">');?>
					<button class="btn btn-success" type="submit"><?=(Arr::get($get,'archive', '') == 'writeoff' ? 'За день' : 'Архив');?></button>
				</form>
				<?
				if(ceil($writeoffsCount/$limit) > 0){
					$writeoffStartPage = (Arr::get($get,'writeoffPage', 1) - 2) < 1 ? 1 : (Arr::get($get,'writeoffPage', 1) - 2);
					$writeoffPageLimit = (Arr::get($get,'writeoffPage', 1) + 3) > ceil($writeoffsCount/$limit) ? ceil($writeoffsCount/$limit) : (Arr::get($get,'writeoffPage', 1) + 3);
					?>
					<div class="btn-toolbar pull-left" role="toolbar">
						<div class="btn-group">
							<?for ($p=$writeoffStartPage;$p<$writeoffPageLimit;$p++){?>
								<a type="button" href="/admin/<?=$getString;?>&writeoffPage=<?=$p;?>" class="btn btn-default<?=($p == Arr::get($get,'writeoffPage', 1) ? ' active' : '');?>"><?=$p;?></a>
							<?}?>
						</div>
						<?=(ceil($writeoffsCount/$limit) > 2 && $writeoffPageLimit != ceil($writeoffsCount/$limit) ? '<div class="btn-group"><button type="button" class="btn btn-default">...</button></div>' : '');?>
						<?=(ceil($writeoffsCount/$limit) > 1 ? '<div class="btn-group"><a type="button" href="/admin/'.(!empty($getString) ? $getString.'&writeoffPage='.ceil($writeoffsCount/$limit) : '?writeoffPage='.ceil($writeoffsCount/$limit)).'" class="btn btn-default'.(ceil($writeoffsCount/$limit) == Arr::get($get,'writeoffPage', 1) ? ' active' : '').'">'.ceil($writeoffsCount/$limit).'</a></div>' : '');?>
					</div>
				<?}?>
			</div>
			<h2 class="sub-header col-sm-12">Список списаний <?=(Arr::get($get,'archive', '') == 'writeoff' ? '(архив)' : '(за день)');?></h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped writeoff-list-table">
					<thead>
					<tr>
						<th class="col-sm-1">Дата оформления</th>
						<th>Товар</th>
						<th class="col-sm-1">Ответственный</th>
						<th class="col-sm-1 text-center">Статус</th>
					</tr>
					</thead>
					<tbody>
					<?
					$i = 1;
					foreach ($writeoffsList as $data){
						?>
						<tr onclick="document.location='/admin/writeoff/?writeoff=<?=$data['id'];?>';">
							<td>
								<?=date("d.m.Y H:i", strtotime($data['date']));?>
							</td>
							<td>
								<?
								$summ = 0;
								foreach(Model::factory('Admin')->getWriteoffData($data['id']) as $writeoffData){
									?>
									<div class="text-left">
										<?=$writeoffData['product_name'];?>  <b><?=$writeoffData['num'];?></b> шт. <?=(!empty($writeoffData['comment']) ? '('.$writeoffData['comment'].')' : '');?>
									</div>
									<?
								}
								?>
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
	</div>
</div>