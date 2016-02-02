<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cartridge extends Controller_Base {

	public function action_index()	{
		if(Model::factory('Base')->checkVisibilityPage(Model::factory('Base')->visibilityPagesId('cartridge')) == 0)
			$this->template = View::factory('denied');
		$content = View::factory('cartridge');
		$selector = View::factory('selector');
		View::set_global('title', '');
		$left_content = View::factory('search');
		$this->template->page_title="Картриджи";
		//$left_info_arr=Model::factory('Base')->get_left_info_arr('contacts');
		$content->left_title = "Поиск";
		$content->left_content = $left_content;
		$content->right_title = "";
		$content->right_content = "";
		$content->selector=$selector;
		$this->template->content = $content;
	}
	public function action_info()	{
		$content = View::factory('cartridge');
		$selector = View::factory('selector');
		$left_content = View::factory('cartridge_price');
		View::set_global('title', '');
		$this->template->page_title="Картриджи";
		$cartridge_id=Arr::get($_GET,'id','');
		$cartridge_info_arr=Model::factory('Base')->get_cartridge_info_arr($cartridge_id);
		$orgtech_arr=Model::factory('Base')->get_orgtech_arr ($cartridge_id);
		$left_content->cartridge_info=$cartridge_info_arr;
		$left_content->orgtech_arr=$orgtech_arr;
		//$left_content->left_title = "Производитель: ".$cartridge_info_arr['brand'].". Картридж: ".$cartridge_info_arr['cartridge'].". Модель оргтехники: ".$cartridge_info_arr['model'];
		$left_content->left_title = $cartridge_info_arr[0]['brand'];
		$content->left_content = $left_content;
		$content->right_title = "";
		$content->right_content = "";
		$content->selector=$selector;
		$this->template->content = $content;
	}
}
