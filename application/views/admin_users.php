<?
/** @var Model_Shop $shopModel */
$shopModel = Model::factory('Shop');
?>
<h1>Работа с персоналом</h1>
<div class="row admin-main-page">
	<ul class="nav nav-tabs">
		<li <?=((empty($get['action']) || Arr::get($get,'action', '') == 'roles') ? 'class="active"' : '');?>><a href="#roles" data-toggle="tab">Присвоить права</a></li>
		<li <?=(Arr::get($get,'action', '') == 'shopes' ? 'class="active"' : '');?>><a href="#shopes" data-toggle="tab">Закрепить за магазином</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane <?=((empty($get['action']) || Arr::get($get,'action', '') == 'roles') ? 'active' : '');?>" id="roles">
			<h2 class="sub-header col-sm-12">Редактирование прав:</h2>
			<div class="row">
				<div class="col-sm-5">
					<input type="text" class="form-control" id="searchUserName" placeholder="Имя пользователя">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<table class="table table-bordered change-role-table">
						<thead>
							<tr>
								<th>Пользователь</th>
								<th>Права</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div id="tdChangeUserRoles"></div>
									<input type="hidden"  id="changeUserRolesId" value="0">
								</td>
								<td>
									<?foreach(Model::factory('Users')->getRolesList() as $roleData){?>
										<div class="row">
											<div class="col-lg-6">
												<div class="input-group">
													<span class="input-group-addon">
														<input type="checkbox" value="<?=$roleData['id'];?>" class="checkUserRoles" id="checkUserRoles_<?=$roleData['id'];?>">
													</span>
													<span class="input-group-addon"><?=$roleData['description'];?></span>
												</div>
											</div>
										</div>
									<?}?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="tab-pane <?=(Arr::get($get,'action', '') == 'shopes' ? 'active' : '');?>" id="shopes">
			<h2 class="sub-header col-sm-12">Закрепление менеджера за магазином:</h2>
			<div class="row">
				<div class="col-sm-5">
					<table class="table table-bordered change-user-shop-table">
						<tr>
							<th>Пользователи с правами менеджера</th>
							<th>Магазины</th>
						</tr>
						<?foreach(Model::factory('Users')->getManagers() as $userData){?>
						<tr>
							<td><?=$userData['username'];?></td>
							<td>
								<select class="form-control changeUserShop" id="userShop_<?=$userData['id'];?>" user-id="<?=$userData['id'];?>">
									<option value="0">Не задан</option>
									<?
									foreach($shopModel->getShop() as $shopData){
										$selected = $shopData['id'] == $shopModel->getManagerShop(['user_id' => $userData['id'], 'empty' => true]) ? 'selected' : '';
										?>
									<option value="<?=$shopData['id'];?>" <?=$selected;?> ><?=$shopData['name'];?></option>
									<?}?>
								</select>
							</td>
						</tr>
						<?}?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>