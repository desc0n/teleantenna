<?php

/**
 * Class Model_Cart
 */
class Model_Cart extends Kohana_Model {

	private  $user_id;

	private  $cart_table;

	private $devLimit = '';

	public function __construct() {
		if(Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
			$this->cart_table = 'cart';
		}
		else {
			$this->user_id = Guestid::factory()->get_id();
			$this->cart_table = 'guest_cart';
		}

		$this->devLimit = preg_match('/\.lan/i', $_SERVER['SERVER_NAME']) ? 'limit 0, 10' : '';
		DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
	}

	public function getCart($params = Array())
	{
		$sql = "select `c`.*,
		`p`.`id` as `product_id`,
		`p`.`code` as `product_code`,
		`p`.`name` as `product_name`,
		`p`.`short_description` as `product_short_description`,
		(select sum(`c2`.`num`) from `$this->cart_table` `c2` where `c2`.`user_id` = :user_id)  as `cart_num`,
		ifnull((select `i`.`src` from `products_imgs` `i` where `i`.`product_id` = `p`.`id`  and  `i`.`status_id` = 1 limit 0,1), 'nopic.jpg') as `product_img`
		from `$this->cart_table` `c`
		inner join `products` `p`
			on `c`.`product_id` = `p`.`id`
		where `c`.`user_id` = :user_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		return $res;
	}
	
	public function getCartNum($params = Array())
	{
		$cartData = $this->getCart();
		return count($cartData) > 0 ? $cartData[0]['cart_num'] : 0;
	}
	
	public function getCartProduct($params = Array())
	{
		if(Arr::get($params, 'product_id', 0) == 0) {
			return $this->getCart($params);
		}
		else {
			$sql = "select `c`.*,
			`p`.`id` as `product_id`,
			`p`.`code` as `product_code`,
			`p`.`name` as `product_name`,
			`p`.`short_description` as `product_short_description`,
			(select sum(`c2`.`num`) from `$this->cart_table` `c2` where `c2`.`user_id` = :user_id and `c2`.`product_id` = :product_id)  as `cart_num`,
			ifnull((select `i`.`src` from `products_imgs` `i` where `i`.`product_id` = `p`.`id`  and  `i`.`status_id` = 1 limit 0,1), 'nopic.jpg') as `product_img`
			from `$this->cart_table` `c`
			inner join `products` `p`
				on `c`.`product_id` = `p`.`id`
			where `c`.`user_id` = :user_id
			and `c`.`product_id` = :product_id";
			$res = DB::query(Database::SELECT, $sql)
				->param(':user_id', $this->user_id)
				->param(':product_id', Arr::get($params, 'product_id', 0))
				->execute()
				->as_array();
			return $res;
		}
	}
	
	public function setInCart($params = Array())
	{
		if(count($this->getCartProduct($params)) > 0)
			$this->updateCartProductNum($params);
		else
			$this->insertIntoCart(Model::factory('Product')->getProduct(0, Array(), Arr::get($params, 'product_id', 0)));
		return $this->getCartNum($params);
	}
	
	public function updateCartProductNum($params = Array())
	{
		$num = Arr::get($params, 'num', 1);
		$sql = "update `$this->cart_table` set `num` = (`num` + $num) where `product_id` = :product_id and `user_id` = :user_id";
			DB::query(Database::UPDATE,$sql)
				->param(':user_id', $this->user_id)
				->param(':product_id', Arr::get($params, 'product_id', 0))
				->execute(); 
	}
	
	public function insertIntoCart($params = Array())
	{
		foreach($params as $data){
			$sql = "insert into `$this->cart_table` (`user_id`, `shop_id`, `product_id`, `price`, `num`) values (:user_id, :shop_id, :product_id, :price, 1)";
			DB::query(Database::INSERT,$sql)
				->param(':user_id', $this->user_id)
				->param(':shop_id', Model::factory('Shop')->getManagerShop())
				->param(':product_id', $data['id'])
				->param(':price', $data['price'])
				->execute();
		}
	}

	public function setCartPositionNum($params = Array())
	{
		$sql = "update `$this->cart_table` set `num` = :num where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params, 'id', 0))
			->param(':num', Arr::get($params, 'num', 1))
			->execute();
	}

	public function removeCartPosition($params = [])
	{
		$sql = "delete from `$this->cart_table` where `id` = :id";
		DB::query(Database::DELETE,$sql)
			->param(':id', Arr::get($params, 'id', 0))
			->execute();
	}

	public function removeAllCartPositions($params = [])
	{
		$sql = "delete from `$this->cart_table` where `user_id` = :user_id";
		DB::query(Database::DELETE,$sql)
			->param(':user_id', $this->user_id)
			->execute();
	}

	public function getCartCustomer($params = [])
	{
		$res = DB::query(Database::SELECT, "select * from `" . $this->cart_table . "_customer` where `user_id` = :user_id")
			->param(':user_id', $this->user_id)
			->execute();
		return count($res) > 0 ? $res[0] : [];
	}

	public function setInCartCustomer($params = [])
	{
		if(count($this->getCartCustomer($params)) > 0)
			$this->updateCartCustomer($params);
		else
			$this->insertIntoCartCustomer($params);
	}

	public function updateCartCustomer($params = [])
	{
		$sql = "update `" . $this->cart_table . "_customer`
 			set `name` = :name,
 			`delivery_type` = :delivery_type,
 			`phone` = :phone,
 			`email` = :email,
 			`street` = :street,
 			`house` = :house,
 			`flat` = :flat,
 			`comment` = :comment
 			where `user_id` = :user_id";
		DB::query(Database::UPDATE,$sql)
			->param(':user_id', $this->user_id)
			->param(':name', Arr::get($params, 'customerName', ''))
			->param(':delivery_type', Arr::get($params, 'customerDeliveryType', 0))
			->param(':phone', Arr::get($params, 'customerPhone', ''))
			->param(':email', Arr::get($params, 'customerMail', ''))
			->param(':street', Arr::get($params, 'customerStreet', ''))
			->param(':house', Arr::get($params, 'customerHouse', ''))
			->param(':flat', Arr::get($params, 'customerFlat', ''))
			->param(':comment', Arr::get($params, 'customerComment', ''))
			->execute();
	}

	public function insertIntoCartCustomer($params = [])
	{
		$sql = "insert into `" . $this->cart_table . "_customer`
 			(`user_id`, `delivery_type`, `name`, `phone`, `email`, `street`, `house`, `flat`, `comment`)
 			 values (:user_id, :delivery_type, :name, :phone, :email, :street, :house, :flat, :comment)";
		DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->param(':name', Arr::get($params, 'customerName', ''))
			->param(':delivery_type', Arr::get($params, 'customerDeliveryType', 0))
			->param(':phone', Arr::get($params, 'customerPhone', ''))
			->param(':email', Arr::get($params, 'customerMail', ''))
			->param(':street', Arr::get($params, 'customerStreet', ''))
			->param(':house', Arr::get($params, 'customerHouse', ''))
			->param(':flat', Arr::get($params, 'customerFlat', ''))
			->param(':comment', Arr::get($params, 'customerComment', ''))
			->execute();
	}

	public function setCartShop($params = [])
	{
		$sql = "update `$this->cart_table` set `shop_id` = :shop_id where `user_id` = :user_id";
		DB::query(Database::UPDATE,$sql)
			->param(':user_id', $this->user_id)
			->param(':shop_id', Arr::get($params, 'shop_id', 0))
			->execute();
	}

	public function getMainAssort($params = [])
	{
		$assort = [];
		//Антенна STR-I-036A
		$searchText = !empty(Arr::get($params, 'searchText', '')) ? Arr::get($params, 'searchText', '') : 'emptyText';
        $searchText = str_replace('"', '', $searchText);
        $searchText = str_replace("'", '', $searchText);
        $searchText = str_replace('(', '', $searchText);
        $searchText = str_replace(')', '', $searchText);
		$products = Model::factory('Product')->getProduct();
		$checkArr = [];
		foreach($products as $i => $product){
			$product['group_1_name'] = str_replace('(', '', $product['group_1_name']);
			$product['group_1_name'] = str_replace(')', '', $product['group_1_name']);
			$product['group_2_name'] = str_replace('(', '', $product['group_2_name']);
			$product['group_2_name'] = str_replace(')', '', $product['group_2_name']);
			$product['group_3_name'] = str_replace('(', '', $product['group_3_name']);
			$product['group_3_name'] = str_replace(')', '', $product['group_3_name']);
			$product['name'] = str_replace('(', '', $product['name']);
			$product['name'] = str_replace(')', '', $product['name']);
			$product['name'] = str_replace('(', '', $product['name']);
			$product['name'] = str_replace(')', '', $product['name']);
			$product['code'] = str_replace('(', '', $product['code']);
			$product['code'] = str_replace(')', '', $product['code']);

			if (preg_match("/" . mb_strtolower(($searchText)) . "/i", mb_strtolower(($product['group_1_name']))) && !in_array($product['group_1_name'], $checkArr)) {
				$checkArr[] = $product['group_1_name'];
				$product['group_1_name'] = str_replace('', ')', $product['group_1_name']);
				$assort[$i]['group_1_name'] = ($product['group_1_name']);
				$assort[$i]['group_1'] = $product['group_1'];
			}

			if (preg_match("/" . mb_strtolower(($searchText)) . "/i", mb_strtolower(($product['group_2_name']))) && !in_array($product['group_2_name'], $checkArr)) {
				$checkArr[] = $product['group_2_name'];
				$product['group_2_name'] = str_replace('', ')', $product['group_2_name']);
				$assort[$i]['group_2_name'] = ($product['group_2_name']);
				$assort[$i]['group_2'] = $product['group_2'];
			}

			if (preg_match("/" . mb_strtolower(($searchText)) . "/i", mb_strtolower(($product['group_3_name']))) && !in_array($product['group_3_name'], $checkArr)) {
				$checkArr[] = $product['group_3_name'];
				$product['group_3_name'] = str_replace('', ')', $product['group_3_name']);
				$assort[$i]['group_3_name'] = ($product['group_3_name']);
				$assort[$i]['group_3'] = $product['group_3'];
			}

			if (preg_match("/" . mb_strtolower(($searchText)) . "/i", mb_strtolower(($product['name']))) && !in_array(($product['name']), $checkArr)) {
				$product['name'] = str_replace('', ')', $product['name']);
				$assort[$i]['product_name'] = ($product['name']);
				$assort[$i]['id'] = $product['id'];
				$checkArr[] = ($product['name']);
			}

			if (preg_match("/" .mb_strtolower(($searchText)). "/i", mb_strtolower(($product['code']))) && !in_array(($product['code']), $checkArr)) {
				$product['name'] = str_replace('', ')', $product['name']);
				$product['code'] = str_replace('', ')', $product['code']);
				$assort[$i]['product_name'] = ($product['name']);
				$assort[$i]['code'] = ($product['code']);
				$assort[$i]['id'] = $product['id'];
				$checkArr[] = ($product['name']);
			}

		}
		return json_encode($assort);
	}

	public function findMainAssortByCode($params = [])
	{
		$sql = sprintf("select *,
			REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
			(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
			(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
			(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`
			from `products`
			where `status_id` = 1
			and `code` like '%%%s%%'
			order by `group_1`, `group_2`, `group_3`, `brand_name`
			%s
		", Arr::get($params, 'searchText', 'nocode'), $this->devLimit);

		$assort = DB::query(Database::SELECT,$sql)
			->execute()
			->as_array()
		;

		return json_encode($assort);
	}

	public function getUniqueAssocArr($arr)
	{
		$checkArr = [];
		foreach ($arr as $key => $val){
			foreach ($val as $i => $data) {
				if (isset($checkArr[$data]))
					unset($arr[$key]);
				$checkArr[$data] = $data;
			}
		}

		return $arr;
	}

    /**
     * @param string $query
     *
     * @return array
     */
    public function findProducts($query)
    {
        if (empty($query) || mb_strlen($query) < 3) {
            return [];
        }

        $result = [];

        $searchRes = DB::select('pc.*')
            ->from(['products__categories', 'pc'])
            ->where('pc.name', 'LIKE', "%$query%")
            ->and_where('pc.show', '=', 1)
                ->execute()
            ->as_array()
        ;

        foreach ($searchRes as $res) {
            $result[] = [
                'target' => 'category',
                'id' => $res['id'],
                'name' => str_replace('"', '', $res['name'])
            ];
        }

        $searchRes = DB::select('p.*')
            ->from(['products', 'p'])
            ->where('p.name', 'LIKE', "%$query%")
            ->and_where('p.status_id', '=', 1)
            ->execute()
            ->as_array()
        ;

        foreach ($searchRes as $res) {
            $result[] = [
                'target' => 'product',
                'id' => $res['id'],
                'name' => str_replace('"', '', $res['name'])
            ];
        }

        return $result;
    }

}
