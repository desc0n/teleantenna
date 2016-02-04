<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Orders extends Controller {

	public function action_index()
	{
		$template=View::factory("template");

		$content=View::factory("orders")
			->set('profile_menu', View::factory("profile_menu"))
			->set('customerCartInfo', Model::factory('Cart')->getCartCustomer());

		$root_page="orders";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}
}