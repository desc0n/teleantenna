<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller {

	
	public function action_index()
	{
		$template = View::factory("template");
		$content = View::factory("search")->set('productsArr', Model::factory('Product')->getSearchResult($_POST));
		$content->post = $_POST;
		$root_page = "search";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

}