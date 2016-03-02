<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller {


	private function check_role($role_type = 1)
	{
		if (Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('manager')) {
			if ($role_type == 1) {
				if (!Auth::instance()->logged_in('admin')) {
					HTTP::redirect('/admin');
				}
			} elseif ($role_type == 2) {
				if (!Auth::instance()->logged_in('manager')) {
					HTTP::redirect('/admin');
				}
			}
		} else {
			HTTP::redirect('/');
		}
	}

	public function action_index()
	{
		/** @var $adminModel Model_Admin */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		$template = View::factory("admin_template");
		$admin_menu = View::factory("admin_menu");
		$admin_content = View::factory("admin_main_page");
		$ordersList = $adminModel->getOrdersList($_GET);
		$realizationsList = $adminModel->getRealizationsList($_GET);
		$incomesList = $adminModel->getIncomesList($_GET);
		$returnsList = $adminModel->getReturnsList($_GET);
		$writeoffsList = $adminModel->getWriteoffsList($_GET);
		$admin_content->ordersList = $ordersList;
		$admin_content->ordersCount = !empty($ordersList) ? $ordersList[0]['orders_count'] : 0;
		$admin_content->realizationsList = $realizationsList;
		$admin_content->realizationsCount = !empty($realizationsList) ? $realizationsList[0]['realizations_count'] : 0;
		$admin_content->incomesList = $incomesList;
		$admin_content->incomesCount = !empty($incomesList) ? $incomesList[0]['incomes_count'] : 0;
		$admin_content->returnsList = $returnsList;
		$admin_content->returnsCount = !empty($returnsList) ? $returnsList[0]['returns_count'] : 0;
		$admin_content->writeoffsList = $writeoffsList;
		$admin_content->writeoffsCount = !empty($writeoffsList) ? $writeoffsList[0]['writeoffs_count'] : 0;
		$admin_content->limit = $adminModel->getLimit();
		$admin_content->getString = $adminModel->getGetString($_GET);
		$admin_content->get = $_GET;
		$root_page="index";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	public function action_product()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addproduct','') != ''){
			$adminModel->addProduct($_POST);
			HTTP::redirect('/admin/product/?action=products');
		}
		if(Arr::get($_POST,'removeproduct','') != ''){
			$adminModel->removeProduct($_POST);
			HTTP::redirect('/admin/product/?action=products');
		}
		if(Arr::get($_POST,'addgroup','') != ''){
			$adminModel->addGroup($_POST);
			HTTP::redirect('/admin/product/?action=groups');
		}
		if(Arr::get($_POST,'removegroup','') != ''){
			$adminModel->removeGroup($_POST);
			HTTP::redirect('/admin/product/?action=groups');
		}
		if(Arr::get($_POST,'addbrand','') != ''){
			$adminModel->addBrand($_POST);
			HTTP::redirect('/admin/product/?action=brands');
		}
		if(Arr::get($_POST,'removebrand','') != ''){
			$adminModel->removeBrand($_POST);
			HTTP::redirect('/admin/product/?action=brands');
		}
		if(Arr::get($_POST,'addshop','') != ''){
			$adminModel->addShop($_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'removeshop','') != ''){
			$adminModel->removeShop($_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'redactshop','') != ''){
			$adminModel->redactShop($_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'loadshopimg','') != ''){
			$adminModel->loadImgShop($_FILES, $_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'addcity','') != ''){
			$adminModel->addCity($_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'redactcity','') != ''){
			$adminModel->redactCity($_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'removecity','') != ''){
			$adminModel->removeCity($_POST);
			HTTP::redirect('/admin/product/?action=shops');
		}
		if(Arr::get($_POST,'redactnum','') != ''){
			$adminModel->setProductNum($_POST);
			HTTP::redirect('/admin/redactproducts/?id='.Arr::get($_POST,'redactproduct',0));
		}
		if(Arr::get($_POST,'addservice','') != ''){
			$adminModel->addService($_POST);
			HTTP::redirect('/admin/product/?action=services');
		}
		if(Arr::get($_POST,'removeservice','') != ''){
			$adminModel->removeService($_POST);
			HTTP::redirect('/admin/product/?action=services');
		}
		if(Arr::get($_POST,'addgroup','') != ''){
			$adminModel->addServicesGroup($_POST);
			HTTP::redirect('/admin/product/?action=groups');
		}
		if(Arr::get($_POST,'removegroup','') != ''){
			$adminModel->removeServicesGroup($_POST);
			HTTP::redirect('/admin/product/?action=groups');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_product");
		$root_page="product";
		$admin_menu->root_page=$root_page;
		$admin_content->get=$_GET;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	public function action_addproducts()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addproduct','') != ''){
			$adminModel->addProduct($_POST);
			HTTP::redirect('/admin/addproducts/?group_1='.Arr::get($_POST,'group_1','').'&group_2='.Arr::get($_POST,'group_2','').'&group_3='.Arr::get($_POST,'group_3',''));
		}
		if(Arr::get($_POST,'removeproduct','') != ''){
			$adminModel->removeProduct($_POST);
			HTTP::redirect('/admin/addproducts/?group_1='.Arr::get($_POST,'group_1','').'&group_2='.Arr::get($_POST,'group_2','').'&group_3='.Arr::get($_POST,'group_3',''));
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_add_products");
		$root_page="addproducts";
		$admin_menu->root_page=$root_page;
		$admin_content->get=$_GET;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	public function action_addproductsgroup()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addgroup','') != ''){
			$adminModel->addGroup($_POST);
			HTTP::redirect('/admin/addproductsgroup');
		}
		if(Arr::get($_POST,'removegroup','') != ''){
			$adminModel->removeGroup($_POST);
			HTTP::redirect('/admin/addproductsgroup');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_add_products_group");
		$root_page="addproductsgroup";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_redactproductsgroup()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'redactGroup','') != ''){
			$adminModel->redactGroup($_POST);
		}
		$getString = '?group=true';
		$getString .= Arr::get($_POST, 'groupId1', 0) != 0 ? '&group_1='.Arr::get($_POST, 'groupId1', 0) : '';
		$getString .= Arr::get($_POST, 'groupId2', 0) != 0 ? '&group_2='.Arr::get($_POST, 'groupId2', 0) : '';
		$getString .= Arr::get($_POST, 'groupId3', 0) != 0 ? '&group_3='.Arr::get($_POST, 'groupId3', 0) : '';
		HTTP::redirect('/admin/product/' . $getString);
	}

	public function action_redactproducts()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		$product_id = Arr::get($_GET,'id','');
		$filename=Arr::get($_FILES, 'imgname', '');
		$redact_search = isset($_POST['redact_search']) ? $_POST['redact_search'] : false;
		$removeimg = isset($_POST['removeimg']) ? $_POST['removeimg'] : 0;
		$search_arr = Array();
		if($redact_search){
			$search_arr = $adminModel->searchRedactProduct($_POST);
		}
		if($product_id != '' && isset($_POST['redactproduct'])){
			$adminModel->setProductInfo($_POST);
			HTTP::redirect('/admin/redactproducts/?id='.$product_id);
		}
		if($product_id != '' && isset($_POST['newProductParam'])){
			$adminModel->setNewParam($_POST);
			HTTP::redirect('/admin/redactproducts/?id='.$product_id);
		}
		if($product_id != '' && isset($_POST['removeProductParam'])){
			$adminModel->removeProductParam($_POST);
			HTTP::redirect('/admin/redactproducts/?id='.$product_id);
		}
		if ($product_id != '' && $filename!='') {
			$result=$adminModel->loadProductImg($_FILES, $product_id);
			HTTP::redirect('/admin/redactproducts/?id='.$product_id);
		}
		if ($removeimg != 0) {
			$result=$adminModel->removeProductImg($_POST);
			HTTP::redirect('/admin/redactproducts/?id='.$product_id);
		}
		$product_info = $product_id != '' ? Model::factory('Product')->getProductInfo($product_id) : [];
		$productParams = $product_id != '' ? Model::factory('Product')->getProductParams($product_id) : [];
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_redact_products");
		$root_page="redactproducts";
		$admin_menu->root_page=$root_page;
		$admin_content->redact_search=$redact_search;
		$admin_content->search_arr=$search_arr;
		$admin_content->product_id=$product_id;
		$admin_content->product_info=$product_info;
		$admin_content->productParams=$productParams;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	
	public function action_addcities()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addcity','') != ''){
			$adminModel->addCity($_POST);
			HTTP::redirect('/admin/addcities');
		}
		if(Arr::get($_POST,'removecity','') != ''){
			$adminModel->removeCity($_POST);
			HTTP::redirect('/admin/addcities');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_add_cities");
		$root_page="addcities";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	public function action_addshopes()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addshop','') != ''){
			$adminModel->addShop($_POST);
			HTTP::redirect('/admin/addshopes');
		}
		if(Arr::get($_POST,'removeshop','') != ''){
			$adminModel->removeShop($_POST);
			HTTP::redirect('/admin/addshopes');
		}
		if(Arr::get($_POST,'redactshop','') != ''){
			$adminModel->redactShop($_POST);
			HTTP::redirect('/admin/addshopes');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_add_shopes");
		$root_page="addshopes";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	
	public function action_redactproductsnum()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		$product_id = Arr::get($_GET,'id','');
		$redact_search = isset($_POST['redact_search']) ? $_POST['redact_search'] : false;
		$search_arr = Array();
		if($redact_search){
			$search_arr = $adminModel->searchRedactProduct($_POST);
		}
		if($product_id != '' && isset($_POST['redactnum'])){
			$adminModel->setProductNum($_POST);
			HTTP::redirect('/admin/redactproductsnum/?id='.$product_id);
		}
		$product_info = $product_id != '' ? Model::factory('Product')->getProductInfo($product_id) : Array();
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_redact_products_num");
		$root_page="redactproductsnum";
		$admin_menu->root_page=$root_page;
		$admin_content->redact_search=$redact_search;
		$admin_content->search_arr=$search_arr;
		$admin_content->product_id=$product_id;
		$admin_content->product_info=$product_info;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	public function action_brands()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addbrand','') != ''){
			$adminModel->addBrand($_POST);
			HTTP::redirect('/admin/brands');
		}
		if(Arr::get($_POST,'removebrand','') != ''){
			$adminModel->removeBrand($_POST);
			HTTP::redirect('/admin/brands');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_brands");
		$root_page="brands";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}
	
	public function action_realization()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);

		if(isset($_POST['newrealization'])){
			$realization_id = $adminModel->addNewRealization();
			HTTP::redirect('/admin/realization/?realization='.$realization_id);
		}

		$realization_id = Arr::get($_GET, 'realization', 0);
		if(isset($_POST['removeRealizationPosition'])){
			$adminModel->removeRealizationPosition($_POST);
			HTTP::redirect('/admin/realization/?realization='.$realization_id);
		}

		if(isset($_POST['carryOutRealization'])){
			$adminModel->carryOutRealization($_POST);
			HTTP::redirect('/admin/print_realization/?realization='.$realization_id);
		}

		if(isset($_POST['carryOutRealizationPost'])){
			$adminModel->carryOutRealizationPost($_POST);
			HTTP::redirect('/admin/print_realization/?realization='.$realization_id);
		}

		if(isset($_POST['createReturn'])){
			$newReturnId = $adminModel->createReturnFromRealization($_POST);
			HTTP::redirect('/admin/return/?return='.$newReturnId);
		}
		$adminModel->checkPrice($_GET);
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content = View::factory("realization_post")
			->set('contractorList', $adminModel->getContractorList())
			->set('realization_id', $realization_id)
			->set('realizationData', $adminModel->getRealizationData($realization_id))
			->set('document_status', $adminModel->getRealizationStatus($realization_id))
			->set('contractor_id', $adminModel->getRealizationContractor($_GET));
		$root_page="realization";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}	

	public function action_income()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		if(isset($_POST['newincome'])){
			$income_id = $adminModel->addNewIncome();
			HTTP::redirect('/admin/income/?income='.$income_id);
		}
		$income_id = Arr::get($_GET, 'income', 0);
		if(isset($_POST['removeIncomePosition'])){
			$adminModel->removeIncomePosition($_POST);
			HTTP::redirect('/admin/income/?income='.$income_id);
		}
		if(isset($_POST['carryOutIncome'])){
			$adminModel->carryOutIncome($_POST);
			HTTP::redirect('/admin/?action=incomes');
		}
		if(isset($_POST['carryOutIncomePost'])){
			$adminModel->carryOutIncomePost($_POST);
			HTTP::redirect('/admin/?action=incomes');
		}
		$template = View::factory("admin_template");
		$admin_menu = View::factory("admin_menu");
		$root_page = 'income';
		$admin_menu->root_page = $root_page;
        $admin_content = View::factory('income_post')
            ->set('income_id', $income_id)
            ->set('incomeData', $adminModel->getIncomeData($income_id))
            ->set('document_status', $adminModel->getIncomeStatus($income_id))
            ->set('contractorList', $adminModel->getContractorList())
            ->set('contractor_id', $adminModel->getIncomeContractor($_GET));
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}	

	public function action_return()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		if(isset($_POST['newreturn'])){
			$return_id = $adminModel->addNewReturn();
			HTTP::redirect('/admin/return/?return='.$return_id);
		}
		$return_id = Arr::get($_GET, 'return', 0);
		if(isset($_POST['removeReturnPosition'])){
			$adminModel->removeReturnPosition($_POST);
			HTTP::redirect('/admin/return/?return='.$return_id);
		}
		if(isset($_POST['carryOutReturn'])){
			$adminModel->carryOutReturn($_POST);
			HTTP::redirect('/admin/?action=returns');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("return");
		$root_page="return";
		$admin_menu->root_page=$root_page;
		$admin_content->return_id=$return_id;
		$admin_content->returnData=$adminModel->getReturnData($return_id);
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}	

	public function action_writeoff()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);

		if(isset($_POST['newwriteoff'])){
			$writeoff_id = $adminModel->addNewWriteoff();
			HTTP::redirect('/admin/writeoff/?writeoff='.$writeoff_id);
		}

		$writeoff_id = Arr::get($_GET, 'writeoff', 0);

		if(isset($_POST['removeWriteoffPosition'])){
			$adminModel->removeWriteoffPosition($_POST);
			HTTP::redirect('/admin/writeoff/?writeoff='.$writeoff_id);
		}

		if(isset($_POST['carryOutWriteoff'])){
			$adminModel->carryOutWriteoff($_POST);
			HTTP::redirect('/admin/?action=writeoffs');
		}
		if(isset($_POST['carryOutWriteoffPost'])){
			$adminModel->carryOutWriteoffPost($_POST);
			HTTP::redirect('/admin/?action=writeoffs');
		}

		$template = View::factory("admin_template");
		$admin_menu = View::factory("admin_menu")
            ->set('root_page', 'writeoff');
		$admin_content = View::factory("writeoff_post")
            ->set('writeoff_id', $writeoff_id)
            ->set('writeoffData', $adminModel->getWriteoffData($writeoff_id))
            ->set('document_status', $adminModel->getWriteoffStatus($writeoff_id))
            ->set('contractorList', $adminModel->getContractorList())
            ->set('contractor_id', $adminModel->getWriteoffContractor($_GET));
		$template->admin_menu = $admin_menu;
		$template->admin_content = $admin_content;
		$this->response->body($template);
	}


	public function action_cash()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		if(isset($_POST['closeCash'])){
			$adminModel->closeCash($_POST);
			HTTP::redirect('/admin/cash/?action=cashclose');
		}
		$template = View::factory("admin_template");
		$admin_menu = View::factory("admin_menu");
		$admin_content = View::factory("admin_cash");
		$cashincomesList = $adminModel->getCashincomesList($_GET);
		$cashwriteoffsList = $adminModel->getCashwriteoffsList($_GET);
		$cashreturnsList = $adminModel->getCashreturnsList($_GET);
		$cashCloseList = (Arr::get($_GET,'action', '') == 'cashclose' && Arr::get($_GET,'archive', '') == 'cashclose') ? $adminModel->getCashCloseList($_GET) : Array();
		$admin_content->cashincomesList = $cashincomesList;
		$admin_content->cashincomesCount = !empty($cashincomesList) ? $cashincomesList[0]['cashincomes_count'] : 0;
		$admin_content->cashwriteoffsList = $cashwriteoffsList;
		$admin_content->cashwriteoffsCount = !empty($cashwriteoffsList) ? $cashwriteoffsList[0]['cashwriteoffs_count'] : 0;
		$admin_content->cashreturnsList = $cashreturnsList;
		$admin_content->cashreturnsCount = !empty($cashreturnsList) ? $cashreturnsList[0]['cashreturns_count'] : 0;
		$admin_content->limit = $adminModel->getLimit();
		$admin_content->getString = $adminModel->getGetString($_GET);
		$admin_content->rootCash = $adminModel->getRootCash($_GET);
		$admin_content->cashCloseList = $cashCloseList;
		$admin_content->cashcloseCount = !empty($cashCloseList) ? $cashCloseList[0]['cashclose_count'] : 0;
		$admin_content->get = $_GET;
		$root_page="index";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_cashincome()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		if(isset($_POST['newcashincome'])){
			$cashincome_id = $adminModel->addNewCashincome();
			HTTP::redirect('/admin/cashincome/?cashincome='.$cashincome_id);
		}
		$cashincome_id = Arr::get($_GET, 'cashincome', 0);
		if(isset($_POST['removeCashincomePosition'])){
			$adminModel->removeCashincomePosition($_POST);
			HTTP::redirect('/admin/cashincome/?cashincome='.$cashincome_id);
		}
		if(isset($_POST['carryOutCashincome'])){
			$adminModel->carryOutCashincome($_POST);
			HTTP::redirect('/admin/cash/?action=cashincomes');
		}
		if(isset($_POST['carryOutCashincomePost'])){
			$adminModel->carryOutCashincomePost($_POST);
			HTTP::redirect('/admin/cashincome/?cashincome='.$cashincome_id);
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("cashincome_post");
		$root_page="cashincome";
		$admin_menu->root_page=$root_page;
		$admin_content->cashincome_id=$cashincome_id;
		$admin_content->cashincomeData=$adminModel->getCashincomeData($cashincome_id);
		$admin_content->document_status = $adminModel->getCashincomeStatus($cashincome_id);
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_cashwriteoff()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		if(isset($_POST['newcashwriteoff'])){
			$cashwriteoff_id = $adminModel->addNewCashwriteoff();
			HTTP::redirect('/admin/cashwriteoff/?cashwriteoff='.$cashwriteoff_id);
		}
		$cashwriteoff_id = Arr::get($_GET, 'cashwriteoff', 0);
		if(isset($_POST['removeCashwriteoffPosition'])){
			$adminModel->removeCashwriteoffPosition($_POST);
			HTTP::redirect('/admin/cashwriteoff/?cashwriteoff='.$cashwriteoff_id);
		}
		if(isset($_POST['carryOutCashwriteoff'])){
			$adminModel->carryOutCashwriteoff($_POST);
			HTTP::redirect('/admin/cash/?action=cashwriteoffs');
		}
		if(isset($_POST['carryOutCashwriteoffPost'])){
			$adminModel->carryOutCashwriteoffPost($_POST);
			HTTP::redirect('/admin/cashwriteoff/?cashwriteoff='.$cashwriteoff_id);
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("cashwriteoff_post");
		$root_page="cashwriteoff";
		$admin_menu->root_page=$root_page;
		$admin_content->cashwriteoff_id=$cashwriteoff_id;
		$admin_content->cashwriteoffData=$adminModel->getCashwriteoffData($cashwriteoff_id);
		$admin_content->document_status = $adminModel->getCashwriteoffStatus($cashwriteoff_id);
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_cashreturn()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		if(isset($_POST['newcashreturn'])){
			$cashreturn_id = $adminModel->addNewCashreturn();
			HTTP::redirect('/admin/cashreturn/?cashreturn='.$cashreturn_id);
		}
		$cashreturn_id = Arr::get($_GET, 'cashreturn', 0);
		if(isset($_POST['removeCashreturnPosition'])){
			$adminModel->removeCashreturnPosition($_POST);
			HTTP::redirect('/admin/cashreturn/?cashreturn='.$cashreturn_id);
		}
		if(isset($_POST['carryOutCashreturn'])){
			$adminModel->carryOutCashreturn($_POST);
			HTTP::redirect('/admin/cash/?action=cashreturns');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("cashreturn");
		$root_page="cashreturn";
		$admin_menu->root_page=$root_page;
		$admin_content->cashreturn_id=$cashreturn_id;
		$admin_content->cashreturnData=$adminModel->getCashreturnData($cashreturn_id);
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_users()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_users");
		$root_page="users";
		$admin_content->get = $_GET;
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}


	public function action_print_realization()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role(2);
		$realization_id = Arr::get($_GET, 'realization', 0);
		$template=View::factory("print_realization");
		$template->realization_id=$realization_id;
		$template->realizationData=$adminModel->getRealizationData($realization_id);
		$this->response->body($template);
	}


	public function action_order()
	{
		/** @var $adminModel Model_Admin */
		$adminModel = Model::factory('Admin');

		/** @var $orderModel Model_Order */
		$orderModel = Model::factory('Order');

		$this->check_role(2);
		$order_id = Arr::get($_GET, 'order', 0);

		if(isset($_POST['createRealization'])){
			$realizationId = $adminModel->createRealizationfromOrder($_POST);
			HTTP::redirect('/admin/realization/?realization='.$realizationId);
		}

		if(!empty(Arr::get($_POST,'canceledOrder'))){
			$adminModel->canceledOrder($_POST);
			HTTP::redirect(sprintf('/admin/order/?order=%s', Arr::get($_GET, 'order')));
		}

		if(!empty(Arr::get($_POST,'collectedOrder'))){
			$adminModel->collectedOrder($_POST);
			HTTP::redirect(sprintf('/admin/order/?order=%s', Arr::get($_GET, 'order')));
		}

		$template = View::factory('admin_template');
		$admin_menu = View::factory('admin_menu');
		$admin_content =
			View::factory("order")
				->set('order_id', $order_id)
				->set('orderData', $orderModel->getOrderData($order_id))
				->set('orderDeliveryInfo', $orderModel->getOrderDeliveryInfo(['order_id'  => $order_id]))
			;

		$root_page = 'order';
		$admin_menu->root_page = $root_page;

		$template->admin_menu = $admin_menu;
		$template->admin_content = $admin_content;
		$this->response->body($template);
	}

	public function action_addservices()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addservice','') != ''){
			$adminModel->addService($_POST);
			HTTP::redirect('/admin/addservices/?group_1='.Arr::get($_POST,'group_1','').'&group_2='.Arr::get($_POST,'group_2','').'&group_3='.Arr::get($_POST,'group_3',''));
		}
		if(Arr::get($_POST,'removeservice','') != ''){
			$adminModel->removeService($_POST);
			HTTP::redirect('/admin/addservices/?group_1='.Arr::get($_POST,'group_1','').'&group_2='.Arr::get($_POST,'group_2','').'&group_3='.Arr::get($_POST,'group_3',''));
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_add_services");
		$root_page="addservices";
		$admin_menu->root_page=$root_page;
		$admin_content->get=$_GET;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_addservicesgroup()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'addgroup','') != ''){
			$adminModel->addGroup($_POST);
			HTTP::redirect('/admin/addservicesgroup');
		}
		if(Arr::get($_POST,'removegroup','') != ''){
			$adminModel->removeGroup($_POST);
			HTTP::redirect('/admin/addservicesgroup');
		}
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_add_services_group");
		$root_page="addservicesgroup";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_redactservicesgroup()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		if(Arr::get($_POST,'redactGroup','') != ''){
			$adminModel->redactServicesGroup($_POST);
		}
		$getString = '?group=true&action=services';
		$getString .= Arr::get($_POST, 'groupId1', 0) != 0 ? '&group_1='.Arr::get($_POST, 'groupId1', 0) : '';
		$getString .= Arr::get($_POST, 'groupId2', 0) != 0 ? '&group_2='.Arr::get($_POST, 'groupId2', 0) : '';
		$getString .= Arr::get($_POST, 'groupId3', 0) != 0 ? '&group_3='.Arr::get($_POST, 'groupId3', 0) : '';
		HTTP::redirect('/admin/product/' . $getString);
	}

	public function action_redactservices()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		$service_id = Arr::get($_GET,'id','');
		$filename=Arr::get($_FILES, 'imgname', '');
		$redact_search = isset($_POST['redact_search']) ? $_POST['redact_search'] : false;
		$removeimg = isset($_POST['removeimg']) ? $_POST['removeimg'] : 0;
		$search_arr = Array();
		if($redact_search){
			$search_arr = $adminModel->searchRedactService($_POST);
		}
		if($service_id != '' && isset($_POST['redactservice'])){
			$adminModel->setServiceInfo($_POST);
			HTTP::redirect('/admin/redactservices/?id='.$service_id);
		}
		if($service_id != '' && isset($_POST['newServiceParam'])){
			$adminModel->setNewServicesParam($_POST);
			HTTP::redirect('/admin/redactservices/?id='.$service_id);
		}
		if($service_id != '' && isset($_POST['removeServiceParam'])){
			$adminModel->removeServiceParam($_POST);
			HTTP::redirect('/admin/redactservices/?id='.$service_id);
		}
		if ($service_id != '' && $filename!='') {
			$result=$adminModel->loadServiceImg($_FILES, $service_id);
			HTTP::redirect('/admin/redactservices/?id='.$service_id);
		}
		if ($removeimg != 0) {
			$result=$adminModel->removeServiceImg($_POST);
			HTTP::redirect('/admin/redactservices/?id='.$service_id);
		}
		$service_info = $service_id != '' ? Model::factory('Service')->getServiceInfo($service_id) : [];
		$serviceParams = $service_id != '' ? Model::factory('Service')->getServiceParams($service_id) : [];
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content=View::factory("admin_redact_services");
		$root_page="redactservices";
		$admin_menu->root_page=$root_page;
		$admin_content->redact_search=$redact_search;
		$admin_content->search_arr=$search_arr;
		$admin_content->service_id=$service_id;
		$admin_content->service_info=$service_info;
		$admin_content->serviceParams=$serviceParams;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_contractor()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

        /**
		 * @var $usersModel Model_Users
		 */
		$usersModel = Model::factory('Users');

		$this->check_role();
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content = View::factory("admin_contractor")
			->set('usersList', $usersModel->getUsersProfile(false, true))
			->set('get', $_GET)
			->set('username', Arr::get($_POST,'username',''))
			->set('email', Arr::get($_POST,'email',''))
			->set('error', '');
		if (isset($_POST['reg'])) {
			if (Arr::get($_POST,'username','')=="") {
				$error = View::factory('error');
				$error->zag = "Не указан логин!";
				$error->mess = " Укажите Ваш логин.";
				$admin_content->error = $error;
			} else if (Arr::get($_POST,'email','')=="") {
				$error = View::factory('error');
				$error->zag = "Не указана почта!";
				$error->mess = " Укажите Вашу почту.";
				$admin_content->error = $error;
			} else if (Arr::get($_POST,'password','')=="") {
				$error = View::factory('error');
				$error->zag = "Не указан пароль!";
				$error->mess = " Укажите Ваш пароль.";
				$admin_content->error = $error;
			} else if (Arr::get($_POST,'password','')!=Arr::get($_POST,'password2','')) {
				$error = View::factory('error');
				$error->zag = "Пароли не совпадают!";
				$error->mess = " Проверьте правильность подтверждения пароля.";
				$admin_content->error = $error;
			} else {
				$user = ORM::factory('User');
				$user->values(array(
					'username' => $_POST['username'],
					'email' => $_POST['email'],
					'password' => $_POST['password'],
					'password_confirm' => $_POST['password2'],
				));
				$some_error = false;
				try {
					$user->save();
					$user->add("roles",ORM::factory("Role",1));
				}
				catch (ORM_Validation_Exception $e) {
					$some_error = $e->errors('models');
				}
				if ($some_error) {
					$error = View::factory('error');
					$error->zag = "Ошибка регистрационных данных!";
					$error->mess = " Проверьте правильность ввода данных.";
					if (isset($some_error['username'])) {
						if ($some_error['username']=="models/user.username.unique") {
							$error->zag = "Такое имя уже есть в базе!";
							$error->mess = " Придумайте новое.";
						}
					}
					else if (isset($some_error['email'])) {
						if ($some_error['email']=="email address must be an email address") {
							$error->zag = "Некорректный формат почты!";
							$error->mess = " Проверьте правильность написания почты.";
						}
						if ($some_error['email']=="models/user.email.unique") {
							$error->zag = "Такая почта есть в базе!";
							$error->mess = " Укажите другую почту.";
						}
					}
					$admin_content->error = $error;
				} else {
					HTTP::redirect("/admin/contractor");
				}
			}
		}

        if (isset($_POST['removeuser'])) {
            $adminModel->removeContractor($_POST);
            HTTP::redirect("/admin/contractor");
        }

		$root_page="contractor";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_redactcontractor()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		$contractor_id = Arr::get($_GET,'id','');
		if($contractor_id != '' && isset($_POST['redactcontractor'])){
			Model::factory('Users')->setUsersProfile($_POST);
			HTTP::redirect('/admin/redactcontractor/?id='.$contractor_id);
		}
		$userProfile = Model::factory('Users')->getUsersProfile($contractor_id);
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content = View::factory("admin_redact_contractor")
			->set('contractor_info', !empty($userProfile) ? $userProfile[0] : [])
			->set('contractor_id', $contractor_id);
		$root_page="contractor";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_report()
	{
		/**
		 * @var $adminModel Model_Admin
		 */
		$adminModel = Model::factory('Admin');

		$this->check_role();
		$template=View::factory("admin_template");
		$admin_menu=View::factory("admin_menu");
		$admin_content = View::factory("report_list");
		$reportsList = $adminModel->getReportsList($_GET);
		$admin_content->reportsList = $reportsList[1];
		$admin_content->reportsCount = $reportsList[0];
		$admin_content->limit = $adminModel->getLimit();
		$admin_content->getString = $adminModel->getGetString($_GET);
		$admin_content->get = $_GET;
		$root_page="report";
		$admin_menu->root_page=$root_page;
		$template->admin_menu=$admin_menu;
		$template->admin_content=$admin_content;
		$this->response->body($template);
	}

	public function action_farpost()
	{
		/**@var $adminModel Model_Admin */
		$adminModel = Model::factory('Admin');

		if (isset($_POST['generatePrice'])) {
			$adminModel->generatePrice('farpost');

			HTTP::redirect('admin/farpost');
		}

		$this->check_role();

		$root_page="farpost";

		$template = View::factory('admin_template');
		$admin_content = View::factory('admin_farpost');
		$admin_menu = View::factory('admin_menu')
				->set('root_page', $root_page)
		;

		$template
			->set('admin_menu', $admin_menu)
			->set('admin_content', $admin_content)
		;

		$this->response->body($template);
	}
}