<?php

/**
 * Class Model_Users
 */
class Model_Users extends Kohana_Model {

	private  $user_id;
	public function __construct() {
		if(Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
		}
		else {
			$this->user_id = Guestid::factory()->get_id();
		}
		DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
	}

	public function getUser($params = [])
	{
		return Auth::instance()->logged_in() ? (Arr::get($params, 'user_id', 0) == 0 ? Auth::instance()->get_user()->id : Arr::get($params, 'user_id', 0)) : $this->user_id;
	}

	public function getUsers($params = [])
	{
		return DB::query(Database::SELECT,"select * from users")->execute()->as_array();
	}

	public function getUserRoles($params = [])
	{
		$sql = "select `u`.`id` as `user_id`,
		`u`.`username`,
		`r`.`description`,
		`r`.`id` as `role_id`
		from `users` `u`
		inner join `roles_users` `ru`
			on `u`.`id` = `ru`.`user_id`
		INNER JOIN `roles` `r`
			ON `r`.`id` = `ru`.`role_id`
		WHERE `u`.`username` = :username";
		$res = DB::query(Database::SELECT,$sql)
			->param(':username', Arr::get($params, 'username', ''))
			->execute()
			->as_array();
		return json_encode($res);
	}

	public function getRolesList()
	{
		return DB::query(Database::SELECT, "select * from `roles`")->execute()->as_array();
	}

	public function changeUserRoles($params = [])
	{
		$userRoles = json_decode($this->getUserRoles($params));
		$roleExists = 0;
		foreach($userRoles as $roleData){
			if($roleData->role_id == Arr::get($params, 'role_id', -1))
				$roleExists = 1;
		}
		if($roleExists == 0){
			DB::query(Database::INSERT, "insert into `roles_users` (`user_id`, `role_id`) values (:user_id, :role_id)")
				->param(':user_id', Arr::get($params, 'user_id', 0))
				->param(':role_id', Arr::get($params, 'role_id', 0))
				->execute();
		} else {
			DB::query(Database::DELETE, "delete from `roles_users` where `user_id` = :user_id and `role_id` = :role_id")
				->param(':user_id', Arr::get($params, 'user_id', 0))
				->param(':role_id', Arr::get($params, 'role_id', 0))
				->execute();
		}
	}

	public function changeUserShop($params = [])
	{
		$params['empty'] = true;
		$userShop = Model::factory('Shop')->getManagerShop($params);
		if(empty($userShop)){
			DB::query(Database::INSERT, "insert into `shopes_managers` (`shop_id`, `user_id`) values (:shop_id, :user_id)")
				->param(':shop_id', Arr::get($params, 'shop_id', 0))
				->param(':user_id', Arr::get($params, 'user_id', 0))
				->execute();
		} else {
			DB::query(Database::UPDATE, "update `shopes_managers` set `shop_id` = :shop_id where `user_id` = :user_id")
				->param(':shop_id', Arr::get($params, 'shop_id', 0))
				->param(':user_id', Arr::get($params, 'user_id', 0))
				->execute();
		}
	}

	public function getUsersProfile($id = false, $sort = false)
	{
		$idSql = $id ? "where `u`.`id` = $id" : "";
		$sortSql = $sort ? "order by `u`.`username`" : "";

		return DB::query(Database::SELECT,
			"select `u`.*,
			(select `p`.`name` from `profile` `p` where `p`.`user_id` = `u`.`id` limit 0,1) as `name`,
			(select `p`.`phone` from `profile` `p` where `p`.`user_id` = `u`.`id` limit 0,1) as `phone`,
			(select `p`.`card` from `profile` `p` where `p`.`user_id` = `u`.`id` limit 0,1) as `card`,
			(select `p`.`discount` from `profile` `p` where `p`.`user_id` = `u`.`id` limit 0,1) as `discount`,
			(select `p`.`contractor` from `profile` `p` where `p`.`user_id` = `u`.`id` limit 0,1) as `contractor`
			from `users` `u`
			$idSql
			$sortSql
        ")
            ->execute()
            ->as_array();
	}

	public function setUsersProfile($params)
	{
		return DB::query(Database::INSERT,
			"INSERT INTO `profile` (`user_id`, `name`, `phone`, `card`, `contractor`, `discount`) VALUES (:user_id, :name, :phone, :card, :contractor, :discount)
			ON DUPLICATE KEY UPDATE
			`name` = :name,
			`phone` = :phone,
			`card` = :card,
			`contractor` = :contractor,
			`discount` = :discount")
				->param(':user_id', Arr::get($params, 'redactcontractor', ''))
				->param(':name', Arr::get($params, 'name', ''))
				->param(':phone', Arr::get($params, 'phone', ''))
				->param(':card', Arr::get($params, 'card', ''))
				->param(':contractor', !empty(Arr::get($params, 'contractor', null)) ? 1 : 0)
				->param(':discount', Arr::get($params, 'discount', 0))
				->execute();
	}

	public function getManagers($params = [])
	{
		return DB::query(Database::SELECT,"select `u`.* from `users` `u` inner join `roles_users` `ru` on `ru`.`user_id` = `u`.`id` and `ru`.`role_id` = 3")->execute()->as_array();
	}


	public function addNewUser($params = [])
	{
		$res = DB::query(Database::SELECT, "select max(`id`) as `id` from `users`")
			->execute()
			->as_array();
		$user_id = $res[0]['id'];
		$params['redactcontractor'] = $user_id;
		$params['phone'] = Arr::get($params, 'username', '');
		$this->setUsersProfile($params);
	}

	public function searchUserByPhone($params = [])
	{
		$phone = str_replace('+', '', Arr::get($params, 'phone', 'empty'));
		$phone = mb_substr($phone, 0, 1) == 7 ? '8' . mb_substr($phone, 1) : $phone;
		$sql = "select
			(select `id` from `users` where replace(`username`, '+', '') = :phone limit 0,1) as `main_user_id`,
			(select `user_id` from `profile` where replace(`phone`, '+', '') = :phone limit 0,1) as `profile_user_id`
			from dual
			";
		$res = DB::query(Database::SELECT, $sql)
			->param(':phone', $phone)
			->execute()
			->as_array();
		return empty($res) ? 0 : (!empty($res[0]['main_user_id']) ? $res[0]['main_user_id'] : (!empty($res[0]['profile_user_id']) ? $res[0]['profile_user_id'] : 0));
	}

	public function setNewPassword($params = [])
	{
		$auth = Auth::instance();
		$new_pass_hash = $auth->hash(Arr::get($params, 'pass', ''));
		$sql = "update `users` set password =  :new_pass  where id = :user_id";
		DB::query(Database::UPDATE, $sql)
			->param(':user_id', Arr::get($params, 'user_id', 0))
			->param(':new_pass', $new_pass_hash)
			->execute();
	}
}
?>