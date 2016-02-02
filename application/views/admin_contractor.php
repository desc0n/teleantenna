<h1>Работа с контрагентами</h1>
<div class="row admin-main-page">
	<ul class="nav nav-tabs">
		<li <?=((empty($get['action']) || Arr::get($get,'action', '') == 'users') ? 'class="active"' : '');?>><a href="#users" data-toggle="tab">Список пользователей</a></li>
		<li <?=(Arr::get($get,'action', '') == 'add' ? 'class="active"' : '');?>><a href="#add" data-toggle="tab">Добавить пользователей</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane  <?=((empty($get['action']) || Arr::get($get,'action', '') == 'users') ? 'active' : '');?>" id="users">
			<h2 class="sub-header col-sm-12">Список пользователей</h2>
			<div class="col-sm-11">
				<table class="table table-hover table-bordered table-striped contractor-list-table">
					<thead>
					<tr>
						<th class="col-sm-3 col-xs-3 col-md-3">Логин</th>
						<th class="col-sm-3 col-xs-3 col-md-3">Почта</th>
						<th>ФИО</th>
						<th class="col-sm-2 col-xs-2 col-md-2 text-center">Телефон</th>
						<th class="col-sm-2 col-xs-2 col-md-2 text-center">Контрагент</th>
						<th class="col-sm-1 col-xs-1 col-md-1 text-center">Действия</th>
					</tr>
					</thead>
					<tbody>
					<?foreach($usersList as $userData){?>
						<tr class="cursor-pointer">
							<td onclick="document.location='/admin/redactcontractor/?id=<?=$userData['id'];?>';"><?=$userData['username'];?></td>
							<td onclick="document.location='/admin/redactcontractor/?id=<?=$userData['id'];?>';"><?=$userData['email'];?></td>
							<td onclick="document.location='/admin/redactcontractor/?id=<?=$userData['id'];?>';"><?=$userData['name'];?></td>
							<td onclick="document.location='/admin/redactcontractor/?id=<?=$userData['id'];?>';"><?=$userData['phone'];?></td>
							<td  onclick="document.location='/admin/redactcontractor/?id=<?=$userData['id'];?>';" class="text-center"><?=($userData['contractor'] == 0 ? 'нет' : 'да');?></td>
                            <td>
                                <form method="post">
                                    <button class="btn btn-danger" name="removeuser" value="<?=$userData['id'];?>">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </form>
                            </td>
						</tr>
					<?}?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane  <?=(Arr::get($get,'action', '') == 'add' ? 'active' : '');?>" id="add">
			<h2 class="sub-header col-sm-12">Добавление пользователя</h2>
			<div class="col-sm-11">
				<?=$error;?>
				<form method="post">
					<p>
					<div class="row">
						<div class="text-muted">Логин*:</div>
						<div class="col-sm-6">
							<input type="text" class="form-control"  name="username" placeholder="Логин" value="<?=$username;?>">
						</div>
					</div>
					</p>
					<p>
					<div class="row">
						<div class="text-muted">Пароль*:</div>
						<div class="col-sm-3">
							<input type="password" class="form-control"  name="password" placeholder="Пароль">
						</div>
						<div class="col-sm-3">
							<input type="password" class="form-control"  name="password2"  placeholder="Еще раз">
						</div>
					</div>
					</p>
					<p>
					<div class="row">
						<div class="text-muted">Эл. почта*:</div>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="email" placeholder="E-mail" value="<?=$email;?>">
						</div>
					</div>
					</p>
					<p>
					<div class="row" style="margin-top:25px;">
						<div class="col-sm-6">
							<button type="submit" class="btn btn-block btn-success" name="reg">Добавить</button>
						</div>
					</div>
					</p>
				</form>
			</div>
		</div>
	</div>
</div>