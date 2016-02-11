<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Orders extends Controller {

	public function action_index()
	{
		/** @var Model_Cart $cartModel */
		$cartModel = Model::factory('Cart');

		$templateData['title'] = 'Заказы.';
		$templateData['description'] = '';

		$template =
			View::factory('template')
				->set('templateData', $templateData)
		;

		$content = View::factory("orders")
			->set('profile_menu', View::factory("profile_menu"))
			->set('customerCartInfo', $cartModel->getCartCustomer());

		$root_page = 'orders';
		$template->root_page = $root_page;
		$template->content = $content;
		$this->response->body($template);
	}
}