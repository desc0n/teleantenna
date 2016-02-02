<?php
class Model_Service extends Kohana_Model {

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
	
	
	public function getServiceGroup($type_id, $parent_id = '', $id = 0)
	{
		if($id != 0)
			$sql = "select * from `services_group_$type_id` where `id` = :id and `status_id` = 1";
		else if($parent_id == '')
			$sql = "select * from `services_group_$type_id` where `status_id` = 1";
		else
			$sql = "select * from `services_group_$type_id` where `parent_id` = :parent_id and `status_id` = 1";
		$query=DB::query(Database::SELECT,$sql);
		$query->param(':parent_id', $parent_id);
		$query->param(':id', $id);
		$service_group=$query->execute()->as_array();
		return $service_group;
	}
	
	public function getService($type_id = 0, $group_arr = Array(), $id = 0, $group_id = 0)
	{
		if($id != 0)
			$sql = "select *,
				(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
				from `services`
				where `id` = :id
				and `status_id` = 1
				order by `group_1`, `group_2`, `group_3`, `brand_name`
				limit 0,1";
		else
			if ($type_id != 0)
				$sql = "select *,
				(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
				from `services`
				where `group_1` = :group_sql_1
				and `group_2` = :group_sql_2
				and `group_3` = :group_sql_3
				and `status_id` = 1
				order by `group_1`, `group_2`, `group_3`, `brand_name`";
			else
				$sql = "select *,
				(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
				from `services`
				where `status_id` = 1
				order by `group_1`, `group_2`, `group_3`, `brand_name`";
		$service = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':group_sql_1', Arr::get($group_arr,1,0))
			->param(':group_sql_2', Arr::get($group_arr,2,0))
			->param(':group_sql_3', Arr::get($group_arr,3,0))
			->execute()
			->as_array();
		return $service;
	}
	
	public function getServiceList($params = Array())
	{
		if(Arr::get($params, 'group_1', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `services_imgs` `pi` where `pi`.`service_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `service_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `group_1` and `g1`.`status_id` = 1 limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `group_2` and `g2`.`status_id` = 1 limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `group_3` and `g3`.`status_id` = 1 limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `services_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `services` `p`
			where `p`.`group_1` = :group_1
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`";
		else if(Arr::get($params, 'group_2', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `services_imgs` `pi` where `pi`.`service_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `service_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `services_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `services` `p`
			where `p`.`group_2` = :group_2
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`";
		else if(Arr::get($params, 'group_3', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `services_imgs` `pi` where `pi`.`service_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `service_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `services_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `services` `p`
			where `p`.`group_3` = :group_3
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`";
		else 
			$sql = "select `p`.*,
			(select `src` from `services_imgs` `pi` where `pi`.`service_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `service_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `services_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `services_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `services_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `services_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `services` `p`
			where  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`";
		$service_list = DB::query(Database::SELECT,$sql)
			->param(':group_1', Arr::get($params,'group_1',0))
			->param(':group_2', Arr::get($params,'group_2',0))
			->param(':group_3', Arr::get($params,'group_3',0))
			->execute()
			->as_array();
		return $service_list;
	}
	
	public function getServiceInfo($id)
	{
		$main_data = $this->getService($type_id = 0, $group_arr = Array(), $id);
		$service_info = count($main_data) > 0 ? $main_data[0] : [];
		$service_info['imgs'] = $this->getServiceImgs($id);
		return $service_info;
	}
	
	public function getServiceImgs($id)
	{
		$service_imgs = Array();
		$sql = "select * from `services_imgs` where `service_id` = :id  and  `status_id` = 1";
		$query = DB::query(Database::SELECT,$sql);
		$query->param(':id', $id);
		$res = $query->execute()->as_array();
		foreach($res as $row){
			$service_imgs[$row['id']] = $row;
		}
		return $service_imgs;
	}

	public function getBrands($id = '')
	{
		if($id == '')
			$sql = "select * from `brands` where `status_id` = 1";
		else
			$sql = "select * from `brands` where `id` = :id and `status_id` = 1";
		return DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->execute()
			->as_array();
	}
	
	public function getAssort()
	{
		$assort = $this->getService();
		return json_encode($assort);
	}


	public function getSearchResult($params)
	{
		$sql = "select `p`.*,
			(select `src` from `services_imgs` `pi` where `pi`.`service_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `service_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1) as `brand_name`
			from `services` `p`
			where `p`.`name` like :searchName and `p`.`status_id` = 1 order by `brand_name`";
		return DB::query(Database::SELECT,$sql)
			->param(':searchName', '%'.Arr::get($params,'mainSearchName','').'%')
			->execute()
			->as_array();
	}

	public function getServiceParams($serviceId)
	{
		return DB::query(Database::SELECT, "select * from `services_params` where `service_id` = :service_id and `status_id` = 1")
			->param(':service_id', $serviceId)
			->execute()
			->as_array();
	}
}
?>