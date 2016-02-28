<div class="row">
	<h2 class="sub-header col-sm-12">Прайс фарпоста:</h2>
	<div class="col-sm-11">
		<? if(file_exists('public/prices/farpost/farpost.csv')) {?>
		Файл прайса доступен по ссылкe <a href="/public/prices/farpost/farpost.csv"><?=sprintf('http://%s/public/prices/farpost/farpost.csv', $_SERVER['SERVER_NAME']);?></a>
		<?} else {?>
		Прайс не сгенерирован
		<?}?>
		<br />
		<br />
		<div class="col-md-12 col-sm-12 no-padding">
			<form method="post" class="no-padding">
				<button class="btn btn-success" name="generatePrice">Сформировать прайс</button>
			</form>
		</div>
	</div>
</div>