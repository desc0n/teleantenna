<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller
{
	public function action_index()
	{
		if (!Auth::instance()->logged_in() && isset($_POST['login'])) {
			$user = ORM::factory('User');
			$status = Auth::instance()->login($_POST['username'], $_POST['password'],true); 
			if ($status) {
				HTTP::redirect('/');
			}
		}
		if (Auth::instance()->logged_in() && isset($_POST['logout'])) {
			Auth::instance()->logout();
		}
		if (!Auth::instance()->logged_in())
			Guestid::factory()->get_id();

		$templateData['title'] = 'Главная.';
		$templateData['description'] = '';

		$template =
			View::factory('template')
				->set('templateData', $templateData)
		;

		$content = View::factory('index');
		$template->root_page = 'index';
		$template->content = $content;
		$this->response->body($template);
	}


	public function action_shop_list()
	{
		$templateData['title'] = 'Список магазинов.';
		$templateData['description'] = '';

		$template =
			View::factory('template')
				->set('templateData', $templateData)
		;

		$content = View::factory("shop_list");
		$content->shopArr = Model::factory('Shop')->getShop();
		$template->root_page = 'shop_list';
		$template->content = $content;
		$this->response->body($template);
	}

}