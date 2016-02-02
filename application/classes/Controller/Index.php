<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller {

	
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
		$template=View::factory("template");
		$content = View::factory("catalog");
		$content->get = $_GET;
		$content->shopArr = Model::factory('Shop')->getShop();
		$root_page="index";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

}