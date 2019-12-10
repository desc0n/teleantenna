<div class="row">
	<h2 class="sub-header col-sm-12">Популярные категории:</h2>
	<div class="col-sm-11">
		<table class="table">
			<tr>
				<th class="col-sm-1">Изображение</th>
				<th>Название</th>
			</tr>
			<?foreach($search_arr as $search_data){?>
			<tr>
				<td><?=$search_data['code'];?></td>
				<td class="text-left"><a href="/admin/redactproducts/?id=<?=$search_data['id'];?>"><?=$search_data['name'];?></a></td>
			</tr>
			<?}?>
		</table>
	</div>
</div>