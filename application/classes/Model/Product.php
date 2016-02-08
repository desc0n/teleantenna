<?php

/**
 * Class Model_Product
 */
class Model_Product extends Kohana_Model {

	private  $user_id;

	private $devLimit = '';

	public function __construct() {
		if(Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
		}
		else {
			$this->user_id = Guestid::factory()->get_id();
		}

		$this->devLimit = preg_match('/\.lan/i', $_SERVER['SERVER_NAME']) ? 'limit 0, 10' : '';
        DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
    }
	
	
	public function getProductGroup($type_id, $parent_id = '', $id = 0)
	{
		if($id != 0)
			$sql = "select * from `products_group_$type_id` where `id` = :id and `status_id` = 1 $this->devLimit";
		else if($parent_id == '')
			$sql = "select * from `products_group_$type_id` where `status_id` = 1 $this->devLimit";
		else
			$sql = "select * from `products_group_$type_id` where `parent_id` = :parent_id and `status_id` = 1 $this->devLimit";

		return DB::query(Database::SELECT,$sql)
				->param(':parent_id', $parent_id)
				->param(':id', $id)
				->execute()
				->as_array()
			;
	}
	
	public function getProduct($type_id = 0, $group_arr = Array(), $id = 0, $group_id = 0)
	{
		if($id != 0)
			$sql = "select *,
                REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
				(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
				from `products`
				where `id` = :id
				and `status_id` = 1
				order by `group_1`, `group_2`, `group_3`, `brand_name`
				limit 0,1";
		else
			if ($type_id != 0)
				$sql = "select *,
                REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
				(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
				from `products`
				where `group_1` = :group_sql_1
				and `group_2` = :group_sql_2
				and `group_3` = :group_sql_3
				and `status_id` = 1
				order by `group_1`, `group_2`, `group_3`, `brand_name`
				$this->devLimit";
			else
				$sql = "select *,
                REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
				(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
				from `products`
				where `status_id` = 1
				order by `group_1`, `group_2`, `group_3`, `brand_name`
				$this->devLimit";
		$product = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':group_sql_1', Arr::get($group_arr,1,0))
			->param(':group_sql_2', Arr::get($group_arr,2,0))
			->param(':group_sql_3', Arr::get($group_arr,3,0))
			->execute()
			->as_array();
		return $product;
	}
	
	public function getProductList($params = Array())
	{
		if(Arr::get($params, 'group_1', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `group_1` and `g1`.`status_id` = 1 limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `group_2` and `g2`.`status_id` = 1 limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `group_3` and `g3`.`status_id` = 1 limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where `p`.`group_1` = :group_1
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`
			$this->devLimit";
		else if(Arr::get($params, 'group_2', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where `p`.`group_2` = :group_2
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`
			$this->devLimit";
		else if(Arr::get($params, 'group_3', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where `p`.`group_3` = :group_3
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`
			$this->devLimit";
		else 
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`
			$this->devLimit";
		$product_list = DB::query(Database::SELECT,$sql)
			->param(':group_1', Arr::get($params,'group_1',0))
			->param(':group_2', Arr::get($params,'group_2',0))
			->param(':group_3', Arr::get($params,'group_3',0))
			->execute()
			->as_array();
		return $product_list;
	}
	
	public function getProductInfo($id, $shop_id = 0)
	{
		$main_data = $this->getProduct($type_id = 0, $group_arr = Array(), $id);
		$product_info = count($main_data) > 0 ? $main_data[0] : [];
		$product_info['imgs'] = $this->getProductImgs($id);
		$product_info['num'] = $this->getProductNum($id, $shop_id);
		return $product_info;
	}

	public function getProductImgs($id)
	{
		$product_imgs = Array();
		$sql = "select * from `products_imgs` where `product_id` = :id  and  `status_id` = 1";
		$query = DB::query(Database::SELECT,$sql);
		$query->param(':id', $id);
		$res = $query->execute()->as_array();
		foreach($res as $row){
			$product_imgs[$row['id']] = $row;
		}
		return $product_imgs;
	}
	
	public function getProductNum($id, $shop_id = 0, $ordersNum = false)
	{
		$product_num = [];

		$ordersSql =
			$ordersNum
			? '-
				ifnull(
					(
						select `og`.`num`
						from `orders_goods` `og`
						inner join `orders` `o`
							on `o`.`id` = `og`.`order_id`
						where `og`.`product_id` = :id
						and `og`.`shop_id` = `s`.`id`
						and `o`.`status_id` in (4,5)
						limit 0,1
					), 0)'
			: ''
		;

		if($shop_id != 0) {
			$sql = "select `s`.*,
				(
					ifnull(
						(
							select `num`
							from `products_num`
							where `product_id` = :id
							and `shop_id` = `s`.`id`
							limit 0,1
						),0)
					$ordersSql
				) as `num`
				from `shopes` `s`
				where `s`.`id` = :shop_id
				and `s`.`status_id` = 1
				limit 0,1";
		} else {
			$sql = "select `s`.*,
				(ifnull(
					(
						select `num`
						from `products_num`
						where `product_id` = :id
						and `shop_id` = `s`.`id`
						limit 0,1
					),0)
					$ordersSql
				) as `num`
				from `shopes` `s`
				where `s`.`status_id` = 1";
		}

		$res = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':shop_id', $shop_id)
			->execute()
			->as_array();

		foreach($res as $row){
			$product_num[$row['id']] = $row;
		}

		return $shop_id == 0 ? $product_num : (count($res) === 0 ? [] : $res[0]);
	}

	public function getOrdersProductNum($id, $shop_id = 0)
	{
		$product_num = [];

		$sql = "";

		$res = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':shop_id', $shop_id)
			->execute()
			->as_array();
		foreach($res as $row){
			$product_num[$row['id']] = $row;
		}
		return $shop_id == 0 ? $product_num : (count($res) === 0 ? [] : $res[0]);
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
	
	public function getAssort($params = [])
	{
		$searchText = !empty(Arr::get($params, 'searchText', '')) ? Arr::get($params, 'searchText', '') : 'emptyText';
		$products = $this->getProduct();
		foreach($products as $i => $product){
			$shopesNum = $this->getProductNum($product['id'], Model::factory('Shop')->getManagerShop());
			$products[$i]['root_num'] = $shopesNum[Model::factory('Shop')->getManagerShop()]['num'];
			if (preg_match("/" . $searchText . "/i", $product['name']))
				$products[$i]['product_name'] = str_replace('"', "'", $product['name']);
			else
				unset($products[$i]);
		}
		return json_encode($products);
	}


	public function getSearchResult($params)
	{
		$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1) as `brand_name`
			from `products` `p`
			where `p`.`name` like :searchName and `p`.`status_id` = 1 order by `brand_name`";
		return DB::query(Database::SELECT,$sql)
			->param(':searchName', '%'.Arr::get($params,'mainSearchName','').'%')
			->execute()
			->as_array();
	}

	public function getProductParams($productId)
	{
		return DB::query(Database::SELECT, "select * from `products_params` where `product_id` = :product_id and `status_id` = 1")
			->param(':product_id', $productId)
			->execute()
			->as_array();
	}
}
?>