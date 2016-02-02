﻿<div class="row">
	<h2 class="sub-header col-sm-12">Редактирование услуги:</h2>
	<div class="col-sm-11">
		<div class="col-sm-10 search-block">
			<form class="form-inline" role="form" action="/admin/redactservices" method="post">
				<div class="col-sm-9 input-group">
					<input type="text" class="form-control search" name="redact_search" placeholder="Поиск по названию или каталожному номеру" style="border: 1px solid #ddd;" value="<?=$redact_search;?>">
					<span class="input-group-btn"><button type="submit" class="btn btn-default search" style="border: 1px solid #ddd;"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</form>
		</div>
	</div>
	<?if($redact_search){?>
	<h2 class="sub-header col-sm-12">Найденные позиции:</h2>
	<div class="col-sm-11">
		<table class="table">
			<tr>
				<th class="col-sm-1 col-code">Код</th>
				<th>Название</th>
			</tr>
			<?foreach($search_arr as $search_data){?>
			<tr>
				<td><?=$search_data['code'];?></td>
				<td class="text-left"><a href="/admin/redactservices/?id=<?=$search_data['id'];?>"><?=$search_data['name'];?></a></td>
			</tr>
			<?}?>
		</table>
	</div>
	<?}?>
	<?if($service_id != ''){?>
	<h2 class="sub-header col-sm-12">Карточка товара:</h2>
	<div class="col-sm-11 redact-form">
		<table class="table">
			<form id="redactservice_form" role="form" action="/admin/redactservices/?id=<?=$service_id;?>" method="post">
			<tr>
				<th class="text-left">Наименование</th>
				<td><textarea name="name" class="form-control"><?=Arr::get($service_info,'name','');?></textarea></td>
			</tr>
			<tr>
				<th class="text-left">Код</th>
				<td><input type="text" name="code" class="price-form form-control" value="<?=Arr::get($service_info,'code','');?>"></td>
			</tr>
			<tr>
				<th class="text-left">Краткое описание</th>
				<td><textarea name="short_description" class="form-control"><?=Arr::get($service_info,'short_description','');?></textarea></td>
			</tr>
			<tr>
				<th class="text-left">Описание</th>
				<td><textarea name="description" class="form-control"><?=Arr::get($service_info,'description','');?></textarea></td>
			</tr>
			<tr>
				<th class="text-left">Цена</th>
				<td><input type="text" name="price" class="price-form form-control" value="<?=Arr::get($service_info,'price',0);?>"></td>
			</tr>
			<input type="hidden" name="redactservice" value="<?=$service_id;?>">
			</form>
			<tr>
				<th class="text-left">Фото</th>
				<td class="imgs-form">
					<?foreach(Arr::get($service_info,'imgs',[]) as $img){?>
					<div class="img-link pull-left" data-toggle="tooltip" data-placement="left" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$img['src'];?>' style='width:200px;'>">
						<img src="/public/img/thumb/<?=$img['src'];?>">
						<span class="pull-right glyphicon glyphicon-remove" title="удалить" onclick="$('#remove_img > #removeimg').val(<?=$img['id'];?>);$('#remove_img').submit();"></span>
					</div>
					<?}?>
					<button class="btn btn-primary" onclick="$('#loadimg_modal').modal('toggle');"><span class="pull-right glyphicon glyphicon-plus"></span></button>
				</td>
			</tr>
		</table>
		<h2 class="sub-header col-sm-12">Характеристики услуги:</h2>
		<table class="table params-table">
			<tr>
				<th class="text-center">Название характеристики</th>
				<th class="text-center">Значение характеристики</th>
				<th class="text-center">Действия</th>
			</tr>
			<?foreach($serviceParams as $paramsData){?>
			<tr>
				<td><?=$paramsData['name'];?></td>
				<td><?=$paramsData['value'];?></td>
				<td class="text-center">
					<form action="/admin/redactservices/?id=<?=$service_id;?>" method="post">
						<button class="btn btn-danger" type="submit" name="removeServiceParam" value="<?=$paramsData['id'];?>"><span class="glyphicon glyphicon-remove"></span></button>
					</form>
				</td>
			</tr>
			<?}?>
			<form class="form-inline" role="form" action="/admin/redactservices/?id=<?=$service_id;?>" method="post">
			<td>
				<input type="text" class="form-control" name="newParamsName" value="">
			</td>
			<td>
				<input type="text" class="form-control" name="newParamsValue" value="">
			</td>
			<td class="text-center">
				<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
				<input type="hidden" name="newServiceParam" value="<?=$service_id;?>">
			</td>
			</form>
		</table>
		<button class="btn btn-success" onclick="$('#redactservice_form').submit();">Сохранить</button>
	</div>
	<?}?>
</div>
<div class="modal fade" id="loadimg_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Загрузка изображения</h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/redactservices/?id=<?=$service_id;?>" method="post" enctype='multipart/form-data'>
		  <div class="form-group">
			<label for="exampleInputFile">Выбор файла</label>
			<input type="file" name="imgname" id="exampleInputFile">
		  </div>
		  <input type="hidden" name="loadserviceimg" value="<?=$service_id;?>">
		  <button type="submit" class="btn btn-default">Загрузить</button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<form id="remove_img" action="/admin/redactservices/?id=<?=$service_id;?>" method="post">
	<input type="hidden" id="removeimg" name="removeimg" value="0">
</form>