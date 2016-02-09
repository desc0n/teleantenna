<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Catalog extends Controller {

	
	public function action_index()
	{
		/** @var Model_Product $productModel */
		$productModel = Model::factory('Product');

		$templateData['title'] = 'Каталог.';
		$templateData['description'] = '';

		$template =
			View::factory('template')
				->set('templateData', $templateData)
		;

		$content = View::factory("catalog");
		$content->get = $_GET;
		$group_1 = Arr::get($_GET,'group_1',0);
		$content->group_1 = $group_1;
		$group_2 = Arr::get($_GET,'group_2',0);
		$content->group_2 = $group_2;

		$group_2_parent_id = Arr::get(Arr::get($productModel->getProductGroup(2, '', $group_2), 0, []), 'parent_id', 0);
		$content->group_2_parent_id = $group_2_parent_id;

		$group_3 = Arr::get($_GET,'group_3',0);
		$content->group_3 = $group_3;

		$group_3_parent_id = Arr::get(Arr::get($productModel->getProductGroup(3, '', $group_3), 0, []), 'parent_id', 0);
		$content->group_3_parent_id = $group_3_parent_id;

		$content->group_1_name = $group_1 != 0
			? Arr::get(Arr::get($productModel->getProductGroup(1, '', $group_1), 0, []), 'name', '')
			: Arr::get(Arr::get($productModel->getProductGroup(1, '', $group_2_parent_id), 0, []), 'name', '');

		$content->group_2_name = $group_2 != 0
			? Arr::get(Arr::get($productModel->getProductGroup(2, '', $group_2), 0, []), 'name', '')
			: Arr::get(Arr::get($productModel->getProductGroup(2, '', $group_3_parent_id), 0, []), 'name', '');
		$root_page = "catalog";

		$template->root_page = $root_page;
		$template->content = $content;
		$this->response->body($template);
	}

	public function action_services()
	{
		$template = View::factory("template");
		$content = View::factory("catalog_services");
		$content->get = $_GET;
		$group_1 = Arr::get($_GET,'group_1',0);
		$content->group_1 = $group_1;
		$group_2 = Arr::get($_GET,'group_2',0);
		$content->group_2 = $group_2;
		$group_2_parent_id = Arr::get(Arr::get(Model::factory('Service')->getServiceGroup(2, '', $group_2), 0, []), 'parent_id', 0);
		$content->group_2_parent_id = $group_2_parent_id;
		$group_3 = Arr::get($_GET,'group_3',0);
		$content->group_3 = $group_3;
		$group_3_parent_id = Arr::get(Arr::get(Model::factory('Service')->getServiceGroup(3, '', $group_3), 0, []), 'parent_id', 0);
		$content->group_3_parent_id = $group_3_parent_id;
		$content->group_1_name = $group_1 != 0
			? Arr::get(Arr::get(Model::factory('Service')->getServiceGroup(1, '', $group_1), 0, []), 'name', '')
			: Arr::get(Arr::get(Model::factory('Service')->getServiceGroup(1, '', $group_2_parent_id), 0, []), 'name', '');
		$content->group_2_name = $group_2 != 0
			? Arr::get(Arr::get(Model::factory('Service')->getServiceGroup(2, '', $group_2), 0, []), 'name', '')
			: Arr::get(Arr::get(Model::factory('Service')->getServiceGroup(2, '', $group_3_parent_id), 0, []), 'name', '');
		$root_page = "catalog";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

}