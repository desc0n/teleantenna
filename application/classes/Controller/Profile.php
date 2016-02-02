<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Profile extends Controller {
	
	public function action_index()
	{
		
		$template=View::factory("template");
		$content=View::factory("profile");
		$profile_menu=View::factory("profile_menu");
		$root_page="profile";
		$content->profile_menu = $profile_menu;
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

	public function action_edit()
	{
		$template=View::factory("template");
		$content=View::factory("profile_edit");
		$profile_menu=View::factory("profile_menu");
		$root_page="profile_edit";
		$content->profile_menu = $profile_menu;
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

	public function action_orders()
	{
		if(isset($_POST['newOrder'])){
			Model::factory('Order')->createOrder($_POST);
			HTTP::redirect('/profile/orders/list');
		}
		$ordersList = Model::factory('Order')->getOrdersList($_GET);
		$template=View::factory("template");
		$profile_menu = View::factory("profile_menu")
			->set('active', 'orders');
		$content=View::factory("orders")
			->set('profile_menu',$profile_menu)
			->set('action', $this->request->param('id'))
			->set('ordersList', $ordersList)
			->set('customerCartInfo', Model::factory('Cart')->getCartCustomer())
			->set('cartInfo', Model::factory('Cart')->getCart());
		$template->content=$content;
		$this->response->body($template);
	}

}