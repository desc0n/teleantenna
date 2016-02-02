<div class="row">
	<h2 class="sub-header col-sm-12">Карточка контрагента:</h2>
	<div class="col-sm-11 redact-form">
		<form id="redactcontractor_form" role="form" action="/admin/redactcontractor/?id=<?=$contractor_id;?>" method="post">
		<table class="table">
			<tr>
				<th class="text-left">ФИО</th>
				<td><input type="text" name="name" class="form-control" value="<?=Arr::get($contractor_info,'name','');?>"></td>
			</tr>
			<tr>
				<th class="text-left">Телефон</th>
				<td><input type="text" name="phone" class="form-control" value="<?=Arr::get($contractor_info,'phone','');?>"></td>
			</tr>
			<tr>
				<th class="text-left">Номер карты</th>
				<td><input type="text" name="card" class="form-control" value="<?=Arr::get($contractor_info,'card','');?>"></td>
			</tr>
			<tr>
				<th class="text-left">Процент скидки</th>
				<td><input type="text" name="discount" class="price-form form-control" value="<?=Arr::get($contractor_info,'discount',0);?>"></td>
			</tr>
			<tr>
				<th class="text-left">Контрагент</th>
				<td><input type="checkbox" name="contractor" <?=(Arr::get($contractor_info,'contractor',0) == 1 ? 'checked' : '');?>></td>
			</tr>
			<input type="hidden" name="redactcontractor" value="<?=$contractor_id;?>">
		</table>
		<button class="btn btn-success" type="submit">Сохранить</button>
		</form>
	</div>
</div>