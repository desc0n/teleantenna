<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

	public function action_add_realisation_position()
	{
		$this->response->body(Model::factory("Admin")->addRealisationPosition($_POST['postData']));
	}
		
	public function action_add_income_position()
	{
		$this->response->body(Model::factory("Admin")->addIncomePosition($_POST['postData']));
	}

	public function action_get_cart_num()
	{
		$this->response->body(Model::factory("Cart")->getCartNum());
	}

	public function action_get_cart()
	{
		$this->response->body(json_encode(Model::factory("Cart")->getCart()));
	}

	public function action_set_in_cart()
	{
		$this->response->body(Model::factory("Cart")->setInCart($_POST));
	}

	public function action_set_cart_position_num()
	{
		$this->response->body(Model::factory("Cart")->setCartPositionNum($_POST));
	}

	public function action_remove_from_cart()
	{
		$this->response->body(Model::factory("Cart")->removeCartPosition($_POST));
	}

	public function action_remove_all_cart()
	{
		$this->response->body(Model::factory("Cart")->removeAllCartPositions($_POST));
	}

	public function action_add_return_position()
	{
		$this->response->body(Model::factory("Admin")->addReturnPosition($_POST['postData']));
	}

	public function action_add_writeoff_position()
	{
		$this->response->body(Model::factory("Admin")->addWriteoffPosition($_POST['postData']));
	}

	public function action_add_cashincome_position()
	{
		$this->response->body(Model::factory("Admin")->addCashincomePosition($_POST['postData']));
	}

	public function action_add_cashwriteoff_position()
	{
		$this->response->body(Model::factory("Admin")->addCashwriteoffPosition($_POST['postData']));
	}

	public function action_add_cashreturn_position()
	{
		$this->response->body(Model::factory("Admin")->addCashreturnPosition($_POST['postData']));
	}

	public function action_getcomtab()	{
		$content=View::factory("comments_table");
		$comments_arr=Model::factory("Comments")->get_comments_arr();
		$content->comments_arr=$comments_arr;
		$this->response->body($content);
	}
	public function action_setcomtab()	{
		$comments_arr=Model::factory("Comments")->set_comment($_POST['name'],$_POST['email'],$_POST['city'],$_POST['text']);
		$this->response->body("success");
	}

	public function action_get_users()
	{
		$this->response->body(json_encode(Model::factory("Users")->getUsers()));
	}

	public function action_get_user_roles()
	{
		$this->response->body(Model::factory("Users")->getUserRoles($_POST));
	}

	public function action_change_user_role()
	{
		$this->response->body(Model::factory("Users")->changeUserRoles($_POST));
	}

	public function action_change_user_shop()
	{
		$this->response->body(Model::factory("Users")->changeUserShop($_POST));
	}

	public function action_set_cart_customer()
	{
		$this->response->body(Model::factory("Cart")->setInCartCustomer($_POST['postData']));
	}

	public function action_set_cart_shop()
	{
		$this->response->body(Model::factory("Cart")->setCartShop($_POST));
	}

	public function action_get_assort()
	{
		$params = !empty(Arr::get($_GET, 'searchText', null)) ? $_GET : $_POST;
		$this->response->body(Model::factory("Product")->getAssort($params));
	}

	public function action_get_main_assort()
	{
		/** @var Model_Cart $cartModel */
		$cartModel = Model::factory('Cart');

		$params = !empty(Arr::get($_GET, 'searchText', null)) ? $_GET : $_POST;

		$data = Arr::get($params, 'type') == 'code'
				? $cartModel->findMainAssortByCode($params)
				: $cartModel->getMainAssort($params);

		$this->response->body($data);
	}

	public function action_get_group_products()
	{
		$this->response->body(View::factory('group_products')->set('params', $_POST));
	}
	
	public function action_get_typeahead()
	{
		$this->response->body(View::factory('typeahead')->set('post', $_POST));
	}

	public function action_get_admin_typeahead()
	{
		$view = Arr::get($_POST, 'viewType', null) == 'post' ? View::factory('admin_typeahead_post') : View::factory('admin_typeahead');
		$this->response->body($view->set('post', $_POST));
	}

	public function action_set_realization_contractor()
	{
		$this->response->body(Model::factory("Admin")->setRealizationContractor($_POST));
	}

	public function action_get_product()
	{
        $managerShop = Arr::get($_POST, 'type') == 'manager' ? Model::factory('Shop')->getManagerShop() : 0;
		$this->response->body(json_encode(Model::factory("Product")->getProductInfo(Arr::get($_POST, 'id', 0), $managerShop)));
	}

	public function action_check_orders()
	{
		/** @var $adminModel Model_Admin */
		$adminModel = Model::factory('Admin');

		$this->response->body($adminModel->checkOrders());
	}

	public function action_check_availability()
	{
		/** @var $adminModel Model_Admin */
		$adminModel = Model::factory('Admin');

		$this->response->body($adminModel->checkAvailability($_POST));
	}
}
