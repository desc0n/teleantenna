<?php
class Model_Shop extends Kohana_Model {

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

	public function getCity($id = '')
	{
		if($id == '')
			$sql = "select * from `cities` where `status_id` = 1";
		else
			$sql = "select * from `cities` where `id` = :id and `status_id` = 1";
		return DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->execute()
			->as_array();
	}
	
	public function getShop($city_id = 0, $id = 0)
	{
		if($id != 0)
			$sql = "select * from `shopes` where `id` = :id and `status_id` = 1 limit 0,1";
		else
			if ($city_id != 0)
				$sql = "select * from `shopes` where `city_id` = :city_id and `status_id` = 1";
			else
				$sql = "select * from `shopes` where `status_id` = 1";
		return 	DB::query(Database::SELECT,$sql)
				->param(':id', $id)
				->param(':city_id', $city_id)
				->execute()
				->as_array();
	}
	
	public function getShopManager($id = 0)
	{
		$sql = "select `user_id` from `shopes_managers` where `shop_id` = :id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
				->param(':id', $id)
				->execute()
				->as_array();
		return count($res) > 0 ? $res[0]['user_id'] : 0;
	}
	
	public function getManagerShop($params = [])
	{
		$user_id = Arr::get($params, 'user_id', 0) == 0 ? $this->user_id : Arr::get($params, 'user_id', 0);
		$shops = $this->getShop();
		$first_shop = $shops[0]['id'];
		$sql = "select `shop_id` from `shopes_managers` where `user_id` = :user_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
				->param(':user_id', $user_id)
				->execute()
				->as_array();
		return count($res) > 0 ? $res[0]['shop_id'] : (Arr::get($params, 'empty', false) ? 0 : $first_shop);
	}
	
}
?>