<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Payfaq extends Controller {

	public function action_index() {
		$template=View::factory("template");
		$content=View::factory("payfaq");
		$root_page="payfaq";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

}