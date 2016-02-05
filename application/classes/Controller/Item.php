<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Item extends Controller {

	public function action_product()
	{
		/** @var Model_Product $productModel */
		$productModel = Model::factory('Product');

		$product_id = $this->request->param('id');

		$product_info = ($product_id != '' ? $productModel->getProductInfo($product_id) : []);
		$productParams = ($product_id != '' ? $productModel->getProductParams($product_id) : []);

		$templateData['title'] = !empty(Arr::get($product_info, 'name')) ? sprintf('%s. ', Arr::get($product_info, 'name')) : '';
		$templateData['description'] = !empty(Arr::get($product_info, 'description')) ? sprintf('%s. ', Arr::get($product_info, 'description')) : '';

		$template =
			View::factory('template')
				->set('templateData', $templateData)
		;

		$content = View::factory('item')
			->set('product_id', $product_id)
			->set('product_info', $product_info)
			->set('productParams', $productParams)
		;

		$root_page = 'item';
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

	public function action_service()
	{
		$service_id = $this->request->param('id');
		$template=View::factory("template");
		$content = View::factory("item_services")
			->set('service_id', $service_id)
			->set('service_info', ($service_id != '' ? Model::factory('Service')->getServiceInfo($service_id) : []))
			->set('serviceParams', ($service_id != '' ? Model::factory('Service')->getServiceParams($service_id) : []));
		$root_page="item";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

	public function action_shop()
	{
		$shop_id = $this->request->param('id');
		$template=View::factory("template");
		$content = View::factory("shop")
			->set('shopData', ($shop_id != '' ? Arr::get(Model::factory('Shop')->getShop(0, $shop_id), 0, []) : []));
		$root_page="shop";
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

}