<?php

/**
 * Class Model_Order
 */
class Model_Order extends Kohana_Model {

	private  $user_id;
	private  $cart_table;
	private $aeroLogin = 'sun73111@gmail.com';
	private $aeroPass = '12345678';

	public function __construct() {
		if(Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
			$this->cart_table = 'cart';
		}
		else {
			$this->user_id = Guestid::factory()->get_id();
			$this->cart_table = 'guest_cart';
		}
		DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
	}

	public function addNewOrder()
	{
		$sql="insert into `orders` (`user_id`, `date`) values (:user_id, now())";
		DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `orders` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}

	public function createOrder()
	{
		/** @var Model_Cart $cartModel */
		$cartModel = Model::factory('Cart');

		/** @var Model_Shop $shopModel */
		$shopModel = Model::factory('Shop');

		$cartCustomer = $cartModel->getCartCustomer();
		$params = $cartCustomer;

		if(strlen(preg_replace("/[^0-9]+/i", "", Arr::get($params, 'phone', ''))) == 10) {
			$params['phone'] = sprintf('+7%s', $params['phone']);

			$order_id = $this->addNewOrder();
			$shops = $shopModel->getShop();
			$shop_id = $shops[0]['id'];
			$cartData = $cartModel->getCart();

			foreach($cartData as $data){
				$sql="insert into `orders_goods` (`order_id`, `product_id`, `shop_id`, `price`, `num`) values (:order_id, :product_id, :manager_shop, :price, :num)";

				DB::query(Database::INSERT,$sql)
					->param(':order_id', $order_id)
					->param(':manager_shop', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', $data['num'])
					->param(':price', $data['price'])
					->execute();

				$shop_id = $data['shop_id'];
			}

			$this->setOrderStatus(['order_id' => $order_id, 'status_id' => 3]);

			$sql = "insert into `orders_status_history` (`order_id`, `status_id`, `user_id`, `date`) values (:order_id, 3, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':order_id', $order_id)
				->param(':user_id', $this->user_id)
				->execute();

			$params['order_id'] = $order_id;
			$params['shop_id'] = $shop_id;

			$this->setOrderDeliveryInfo($params);
			$cartModel->removeAllCartPositions();
			$this->sendSms($params);
		}
	}

	public function getOrdersList($params)
	{
		$sqlDate =  Arr::get($params, 'archive', '') != 'order' ? "and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlCountDate =  Arr::get($params, 'archive', '') != 'order' ? "and `r1`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlLimit = '';
		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			(select count(`r1`.`id`) from `orders` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where 1 $sqlCountDate) as `orders_count`
			from `orders` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			where 1
			$sqlDate
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			(select count(`r1`.`id`) from `orders` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where `r1`.`user_id` = :user_id $sqlCountDate) as `orders_count`
			from `orders` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			where `r`.`user_id` = :user_id
			$sqlDate
			$sqlLimit";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		return $res;
	}

	public function getOrderData($order_id)
	{
		$sql = "select `p`.`name` as `product_name`,
		`p`.`code` as `product_code`,
		`r`.`status_id` as `order_status`,
		ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = `rg`.`shop_id` limit 0,1),0) as `root_num`,
		`rg`.*
		from `orders` `r`
		inner join `orders_goods` `rg`
			on `r`.`id` = `rg`.`order_id`
		inner join `products` `p`
			on `rg`.`product_id` = `p`.`id`
		where `rg`.`order_id` = :order_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':order_id', $order_id)
			->execute()
			->as_array();
		return $res;
	}

	public function getOrderDeliveryInfo($params = [])
	{
		$res = DB::query(Database::SELECT, "
            select `odi`.*,
            `s`.`name` as `shop_name`
            from `orders_delivery_info` `odi`
            inner join `shopes` `s`
                on `s`.`id` = `odi`.`shop_id`
            where `odi`.`order_id` = :order_id
            limit 0,1
        ")
			->param(':order_id', Arr::get($params, 'order_id', 0))
			->execute()
			->current()
		;

		return $res;
	}

	public function setOrderDeliveryInfo($params = [])
	{
		if(!empty($this->getOrderDeliveryInfo($params))) {
			$this->updateOrderDeliveryInfo($params);
		} else {
			$this->insertOrderDeliveryInfo($params);
		}
	}


	public function updateOrderDeliveryInfo($params = [])
	{
		$sql = "update `orders_delivery_info`
 			set `name` = :name,
 			`delivery_type` = :delivery_type,
 			`phone` = :phone,
 			`email` = :email,
 			`street` = :street,
 			`house` = :house,
 			`flat` = :flat,
 			`comment` = :comment,
 			`shop_id` = :shop_id,
 			`order_id` = :order_id,
 			`user_type` = :user_type
 			where `order_id` = :order_id";
		DB::query(Database::UPDATE,$sql)
			->param(':name', Arr::get($params, 'name', ''))
			->param(':delivery_type', Arr::get($params, 'delivery_type', 0))
			->param(':phone', Arr::get($params, 'phone', ''))
			->param(':email', Arr::get($params, 'email', ''))
			->param(':street', Arr::get($params, 'street', ''))
			->param(':house', Arr::get($params, 'house', ''))
			->param(':flat', Arr::get($params, 'flat', ''))
			->param(':comment', Arr::get($params, 'comment', ''))
			->param(':shop_id', Arr::get($params, 'shop_id', 4))
			->param(':order_id', Arr::get($params, 'order_id', 0))
			->param(':user_type', (Auth::instance()->logged_in() ? 1 : 0))
			->execute();
	}

	public function insertOrderDeliveryInfo($params = [])
	{
		$sql = "insert into `orders_delivery_info`
 			(`delivery_type`, `name`, `phone`, `email`, `street`, `house`, `flat`, `comment`, `shop_id`, `order_id`, `user_type`)
 			 values (:delivery_type, :name, :phone, :email, :street, :house, :flat, :comment, :shop_id, :order_id, :user_type)";
		DB::query(Database::INSERT,$sql)
			->param(':name', Arr::get($params, 'name', ''))
			->param(':delivery_type', Arr::get($params, 'delivery_type', 0))
			->param(':phone', Arr::get($params, 'phone', ''))
			->param(':email', Arr::get($params, 'email', ''))
			->param(':street', Arr::get($params, 'street', ''))
			->param(':house', Arr::get($params, 'house', ''))
			->param(':flat', Arr::get($params, 'flat', ''))
			->param(':comment', Arr::get($params, 'comment', ''))
			->param(':shop_id', Arr::get($params, 'shop_id', 4))
			->param(':order_id', Arr::get($params, 'order_id', 0))
			->param(':user_type', (Auth::instance()->logged_in() ? 1 : 0))
			->execute();
	}

	public function setOrderStatus($params = [])
	{
		$sql="update `orders` set `status_id` = :status_id where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params, 'order_id', 0))
			->param(':status_id', Arr::get($params, 'status_id', 0))
			->execute();
	}

	public function sendSms($params)
	{
		$password = md5($this->aeroPass);
		$to = preg_replace("/[^0-9]+/i", "", Arr::get($params, 'phone', ''));
		$from = 'TeleAntenna';
		if (empty(Arr::get($params, 'text', ''))) {
			if(Arr::get($params, 'delivery_type', 0) == 0){
				$shopData = Model::factory('Shop')->getShop(0, Arr::get($params, 'shop_id', 0));
				$deliveryText = "Забрать его Вы сможете по адресу " . $shopData[0]['address'] . ".";
			} else {
				$deliveryText = "Заказ будет доставлен курьером.";
			}
			$t = "Ваш заказ № " . Arr::get($params, 'order_id', '') . " сформирован и зарезервирован Вами в течении 3 дней. " . $deliveryText . " Тел. для справок +79025051272";
		} else {
			$t = Arr::get($params, 'text', '');
		}
		$text = urlencode($t);
		$link="http://gate.smsaero.ru/send/?user=". $this->aeroLogin . "&password=".$password."&to=".$to."&text=".$text."&from=".$from;
		file_get_contents($link);
	}
}
?>