<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Item extends Controller {

	public function action_product()
	{
		/** @var Model_Product $productModel */
		$productModel = Model::factory('Product');

		$productId = (int)$this->request->param('id');

		$product = $productModel->getCategoryProducts(null, $productId);

        $this->response->body(
            View::factory('template')
            ->set('templateData', [
                'title' => sprintf('%s. ', $product['name']),
                'description' => sprintf('%s. ', $product['description']),
            ])
            ->set('content', View::factory('product')->set('product', $product)->set('breadcrumb', $productModel->getProductBreadcrumbs($productId)))
        );
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