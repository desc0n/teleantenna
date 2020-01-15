<?php

/**
 * Class Model_Product
 */
class Model_Product extends Kohana_Model {

	private  $user_id;

	private $devLimit = '';

	public function __construct() {
		if(Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
		}
		else {
			$this->user_id = Guestid::factory()->get_id();
		}

		$this->devLimit = preg_match('/\.lan/i', $_SERVER['SERVER_NAME']) ? 'limit 0, 6' : '';
        DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
    }

    /**
     * @param int $categoryId
     * @return array
     */
	public function getProductCategory($categoryId)
	{
		return DB::select()
            ->from('products__categories')
            ->where('id', '=', $categoryId)
            ->limit(1)
            ->execute()
            ->current()
        ;
	}

    /**
     * @param null|int $parentId
     * @return array
     */
	public function getProductCategoriesList($parentId = null)
	{
		$list = [];

		$categoryProducts = DB::select()
            ->from('products__categories')
            ->where('show', '=', 1)
            ->and_where('parent_id', $parentId ? '=' : 'IS', $parentId)
            ->execute()
            ->as_array()
        ;

		foreach ($categoryProducts as $categoryProduct) {
		    $list[] = [
		        'id' => (int)$categoryProduct['id'],
                'name' => str_replace(['"', "'"], '', $categoryProduct['name']),
                'imgSrc' => $categoryProduct['img_src'],
                'parentId' => $categoryProduct['parent_id'] ? (int)$categoryProduct['parent_id'] : null,
                'isPopular' => (bool)$categoryProduct['is_popular'],
                'subCategories' => $this->getProductCategoriesList((int)$categoryProduct['id']),
            ];
        }

        return $list;
	}

    /**
     * @param int $productCategoryId
     * @param array $changeValues
     */
    public function patchProductCategory($productCategoryId, $changeValues = [])
    {
        foreach ($changeValues as $key => $value) {
            switch ($key) {
                case 'productCategoryName':
                    DB::update('products__categories')
                        ->set(['name' => $value])
                        ->where('id', '=', $productCategoryId)
                        ->execute();
                    break;
                case 'show':
                    DB::update('products__categories')
                        ->set(['show' => $value])
                        ->where('id', '=', $productCategoryId)
                        ->execute();
                    break;
                case 'is_popular':
                    DB::update('products__categories')
                        ->set(['is_popular' => $value])
                        ->where('id', '=', $productCategoryId)
                        ->execute();
                    break;
            }
        }
    }

    /**
     * @param int $productCategoryId
     */
    public function removeProductCategory($productCategoryId)
    {
        DB::delete('products__categories')
            ->where('id', '=', $productCategoryId)
            ->execute();
    }

    /**
     * @param int $parentProductCategoryId
     * @param string $newProductCategoryName
     * @return int
     */
    public function addProductCategory($newProductCategoryName, $parentProductCategoryId)
    {
        $res = DB::insert('products__categories', ['name', 'parent_id', 'show'])
            ->values([$newProductCategoryName, $parentProductCategoryId, 1])
            ->execute();

        return $res[0];
    }

    /**
     * @param $categoryId
     * @param array $parentsCategories
     * @return array
     */
    public function getParentsCategories($categoryId, $parentsCategories = [])
    {
        $categoryProducts = DB::select()
            ->from('products__categories')
            ->where('show', '=', 1)
            ->and_where('id', '=', $categoryId)
            ->execute()
            ->as_array()
        ;

        foreach ($categoryProducts as $categoryProduct) {
            $parentId = (int)$categoryProduct['parent_id'];
            if(!$parentId || in_array($parentId, $parentsCategories)) continue;
            $parentsCategories[] = $parentId;

            foreach ($this->getParentsCategories($parentId, $parentsCategories) as $parentsCategory) {
                if(in_array($parentsCategory, $parentsCategories)) continue;
                $parentsCategories[] = $parentsCategory;
            }
        }

        return $parentsCategories;
    }

    /**
     * @param $categoryId
     * @param array $childCategories
     * @return array
     */
    public function getChildCategories($categoryId, $childCategories = [])
    {
        $categoryProducts = DB::select()
            ->from('products__categories')
            ->where('show', '=', 1)
            ->and_where('parent_id', '=', $categoryId)
            ->execute()
            ->as_array()
        ;

        foreach ($categoryProducts as $categoryProduct) {
            $childCategoryId = (int)$categoryProduct['id'];
            if(in_array($childCategoryId, $childCategories)) continue;
            $childCategories[] = $childCategoryId;

            foreach ($this->getChildCategories($childCategoryId, $childCategories) as $childCategory) {
                if(in_array($childCategory, $childCategories)) continue;
                $childCategories[] = $childCategory;
            }
        }

        return $childCategories;
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return int
     */
    public function addProduct($name, $categoryId)
    {
        $res = DB::insert('products', ['name', 'category_id'])
            ->values([str_replace(['"', "'"],'', $name), $categoryId])
            ->execute();

        return $res[0];
    }

    /**
     * @param int $productId
     * @param array $changeValues
     */
    public function patchProduct($productId, $changeValues = [])
    {
        $params = [];
        foreach ($changeValues as $key => $value) {
            switch ($key) {
                case 'name':
                    $params['name'] = $value;
                    break;
                case 'status_id':
                    $params['status_id'] = (int)$value;
                    break;
                case 'is_popular':
                    $params['is_popular'] = (int)$value;
                    break;
            }
        }

        if($params) {
            DB::update('products')
                ->set($params)
                ->where('id', '=', $productId)
                ->execute();
        }
    }

	public function getProductGroup($type_id, $parent_id = '', $id = 0, $params = [])
	{
		$sortSql = Arr::get($params, 'sortSql');

		if($id != 0)
			$sql = sprintf('select * from `products_group_%s` where `id` = :id and `status_id` = 1 %s %s', $type_id, $sortSql, $this->devLimit);
		else if($parent_id == '')
			$sql = sprintf('select * from `products_group_%s` where `status_id` = 1 %s %s', $type_id, $sortSql, $this->devLimit);
		else
			$sql = sprintf('select * from `products_group_%s` where `parent_id` = :parent_id and `status_id` = 1 %s %s', $type_id, $sortSql, $this->devLimit);

		return DB::query(Database::SELECT,$sql)
				->param(':parent_id', $parent_id)
				->param(':id', $id)
				->execute()
				->as_array()
			;
	}
	
	public function getProduct($type_id = 0, $group_arr = [], $id = 0, $params = [])
	{
		$sortSql = Arr::get($params, 'sortSql', 'order by `group_1`, `group_2`, `group_3`, `brand_name`, `code`');

		if($id != 0) {
			$sql = sprintf("select *,
                REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
				(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select `g2`.`parent_id` from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_parent_id`,
				(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`,
				(select `g3`.`parent_id` from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_parent_id`
				from `products`
				where `id` = :id
				and `status_id` = 1
				%s
				limit 0,1", $sortSql);
		} else {
			if ($type_id != 0) {
				$sql = sprintf("select *,
                REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
				(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select `g2`.`parent_id` from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_parent_id`,
				(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`,
				(select `g3`.`parent_id` from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_parent_id`
				from `products`
				where `group_1` = :group_sql_1
				and `group_2` = :group_sql_2
				and `group_3` = :group_sql_3
				and `status_id` = 1
				%s
				%s", $sortSql, $this->devLimit);
			} else {
				$sql = sprintf("select *,
                REPLACE(REPLACE(`name`, '\"', ''), \"'\", '') as `name`,
				(select REPLACE(REPLACE(`b`.`name`, '\"', ''), \"'\", '') from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
				(select REPLACE(REPLACE(`g1`.`name`, '\"', ''), \"'\", '') from `products_group_1` `g1` where `g1`.`id` = `group_1` limit 0,1) as `group_1_name`,
				(select REPLACE(REPLACE(`g2`.`name`, '\"', ''), \"'\", '') from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_name`,
				(select `g2`.`parent_id` from `products_group_2` `g2` where `g2`.`id` = `group_2` limit 0,1) as `group_2_parent_id`,
				(select REPLACE(REPLACE(`g3`.`name`, '\"', ''), \"'\", '') from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_name`,
				(select `g3`.`parent_id` from `products_group_3` `g3` where `g3`.`id` = `group_3` limit 0,1) as `group_3_parent_id`
				from `products`
				where `status_id` = 1
				%s
				%s", $sortSql, $this->devLimit);
			}
		}

		$product = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':group_sql_1', Arr::get($group_arr,1,0))
			->param(':group_sql_2', Arr::get($group_arr,2,0))
			->param(':group_sql_3', Arr::get($group_arr,3,0))
			->execute()
			->as_array();

		return $product;
	}

    /**
     * @param int $categoryId
     * @param int $productId
     * @param bool $withChild
     * @return array
     */
	public function getCategoryProducts($categoryId = null, $productId = null, $withChild = true)
	{
	    if(!$categoryId && !$productId) return [];

        $products =
            DB::select(
                'p.*',
                [DB::expr("REPLACE(REPLACE(p.name, '\"', ''), \"'\", '')"), 'name'],
                [DB::expr("REPLACE(REPLACE(b.name, '\"', ''), \"'\", '')"), 'brand_name'],
                [DB::expr("REPLACE(REPLACE(c.name, '\"', ''), \"'\", '')"), 'category_name'],
                [
                    DB::select('pi.src')
                        ->from(['products_imgs', 'pi'])
                        ->where('pi.product_id', '=', DB::expr('p.id'))
                        ->and_where('pi.status_id', '=', 1)
                        ->limit(1),
                    'product_img'
                ]
            )
            ->from(['products', 'p'])
            ->join(['brands', 'b'], 'LEFT')
            ->on('b.id', '=', 'p.brand_id')
            ->join(['products__categories', 'c'], 'LEFT')
            ->on('c.id', '=', 'p.category_id')
            ->where('p.status_id', '=', 1)
        ;

        if($categoryId) {
            $categories = [$categoryId];
            if($withChild) $categories = array_merge($categories, $this->getChildCategories($categoryId));
            $products = $products->and_where('p.category_id', 'IN', $categories);
        }

        if($productId) $products = $products->and_where('p.id', '=', $productId);

        $products = $products
            ->order_by('p.category_id')
            ->order_by('p.brand_id')
			->execute()
			->as_array();

        foreach ($products as $i => $product) {
            $products[$i]['shop_info'] = [];
            $products[$i]['params'] = [];
            $products[$i]['imgs'] = [];
            $res = DB::select('s.name', 's.address', ['pn.num', 'num'])
				->from(['products_num', 'pn'])
                ->join(['shopes', 's'])
                ->on('s.id', '=', 'pn.shop_id')
                ->where('pn.product_id', '=', $product['id'])
				->and_where('s.status_id', '=', 1)
				->and_where('pn.num', '>', 0)
                ->execute()
                ->as_array();

            foreach($res as $row){
                $products[$i]['shop_info'][] = $row;
            }

            $res = DB::select()
                ->from('products_params')
                ->where('product_id', '=', $product['id'])
                ->and_where('status_id', '=', 1)
                ->execute()
                ->as_array();

            foreach($res as $row){
                $products[$i]['params'][] = $row;
            }

            $res = DB::select()
                ->from('products_imgs')
                ->where('product_id', '=', $product['id'])
                ->and_where('status_id', '=', 1)
                ->execute()
                ->as_array()
            ;

            foreach($res as $row){
                $products[$i]['imgs'][] = $row;
            }
        }
        
        return $categoryId ? $products : $products[0];
	}

    /**
     * @param int $categoryId
     * @return array
     */
	public function getAdminCategoryProducts($categoryId)
	{
        return DB::select(
                'p.*',
                [DB::expr("REPLACE(REPLACE(p.name, '\"', ''), \"'\", '')"), 'name']
            )
            ->from(['products', 'p'])
            ->where('p.status_id', '=', 1)
            ->and_where('p.category_id', '=', $categoryId)
            ->order_by('p.id')
			->execute()
			->as_array();
	}

    /**
     * @return array
     */
	public function getPopularProducts()
	{
        return DB::select(
                'p.*',
                [DB::expr("REPLACE(REPLACE(p.name, '\"', ''), \"'\", '')"), 'name'],
                [
                    DB::select('pi.src')
                        ->from(['products_imgs', 'pi'])
                        ->where('pi.product_id', '=', DB::expr('p.id'))
                        ->and_where('pi.status_id', '=', 1)
                        ->limit(1),
                    'product_img'
                ]
            )
            ->from(['products', 'p'])
            ->where('p.status_id', '=', 1)
            ->and_where('p.is_popular', '=', 1)
            ->order_by('p.id')
			->execute()
			->as_array();
	}

    /**
     * @param int $productId
     * @return string
     */
	public function getProductBreadcrumbs($productId)
    {
        $product = $this->getCategoryProducts(null, $productId);
        $breadcrumbs = '<ol class="breadcrumb"><li><a href="/">Главная</a></li>';
        $categoryId = (int)$product['category_id'];
        $categories = [$categoryId];
        $categories = array_merge($categories, $this->getParentsCategories($categoryId));

        $res = DB::select()
            ->from('products__categories')
            ->where('id', 'IN', $categories)
            ->and_where('show', '=', 1)
            ->execute()
            ->as_array();

        foreach ($res as $category) {
            $breadcrumbs .= '<li><a href="/catalog/?categoryId='. $category['id'] .'">' . $category['name'] . '</a></li>';
        }

        $breadcrumbs .= '</ol>';

        return $breadcrumbs;
    }

	public function getProductList($params = Array())
	{
		if(Arr::get($params, 'group_1', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `group_1` and `g1`.`status_id` = 1 limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `group_2` and `g2`.`status_id` = 1 limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `group_3` and `g3`.`status_id` = 1 limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where `p`.`group_1` = :group_1
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`, `p`.`code`
			$this->devLimit";
		else if(Arr::get($params, 'group_2', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where `p`.`group_2` = :group_2
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`, `p`.`code`
			$this->devLimit";
		else if(Arr::get($params, 'group_3', 0) != 0)
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where `p`.`group_3` = :group_3
			and  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`, `p`.`code`
			$this->devLimit";
		else 
			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1 limit 0,1) as `brand_name`,
			(select `g1`.`name` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` limit 0,1) as `group_1_name`,
			(select `g2`.`name` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` limit 0,1) as `group_2_name`,
			(select `g3`.`name` from `products_group_3` `g3` where `g3`.`id` = `p`.`group_3` limit 0,1) as `group_3_name`,
			ifnull(
				(select `g1`.`id` from `products_group_1` `g1` where `g1`.`id` = `p`.`group_1` and `g1`.`status_id` = 0 limit 0,1),
				ifnull(
					(select `g2`.`id` from `products_group_2` `g2` where `g2`.`id` = `p`.`group_2` and `g2`.`status_id` = 0 limit 0,1),
					ifnull(
						(SELECT `g3`.`id` FROM `products_group_3` `g3` WHERE `g3`.`id` = `p`.`group_3` AND `g3`.`status_id` = 0 limit 0,1),
						0
					)
				)
			) as `check_status`
			from `products` `p`
			where  `p`.`status_id` = 1
			order by `group_2_name`,`brand_name`, `p`.`code`
			$this->devLimit";
		$product_list = DB::query(Database::SELECT,$sql)
			->param(':group_1', Arr::get($params,'group_1',0))
			->param(':group_2', Arr::get($params,'group_2',0))
			->param(':group_3', Arr::get($params,'group_3',0))
			->execute()
			->as_array();
		return $product_list;
	}
	
	public function getProductInfo($id, $shop_id = 0)
	{
		$main_data = $this->getProduct($type_id = 0, $group_arr = [], $id);
		$product_info = count($main_data) > 0 ? $main_data[0] : [];
		$product_info['imgs'] = $this->getProductImgs($id);
		$product_info['num'] = $this->getProductNum($id, $shop_id);
		return $product_info;
	}

	public function getProductImgs($id)
	{
		$product_imgs = [];
		$res = DB::select()
            ->from('products_imgs')
            ->where('product_id', '=', $id)
            ->and_where('status_id', '=', 1)
		    ->execute()
            ->as_array()
        ;

		foreach($res as $row){
			$product_imgs[$row['id']] = $row;
		}
		return $product_imgs;
	}
	
	public function getProductNum($id, $shop_id = 0, $ordersNum = false)
	{
		$product_num = [];

		$ordersSql =
			$ordersNum
			? '-
				ifnull(
					(
						select `og`.`num`
						from `orders_goods` `og`
						inner join `orders` `o`
							on `o`.`id` = `og`.`order_id`
						where `og`.`product_id` = :id
						and `og`.`shop_id` = `s`.`id`
						and `o`.`status_id` in (4,5)
						limit 0,1
					), 0)'
			: ''
		;

		if($shop_id != 0) {
			$sql = "select `s`.*,
				(
					ifnull(
						(
							select `num`
							from `products_num`
							where `product_id` = :id
							and `shop_id` = :shop_id
							limit 0,1
						),0)
					$ordersSql
				) as `num`
				from `shopes` `s`
				where `s`.`id` = :shop_id
				and `s`.`status_id` = 1
				limit 0,1";
		} else {
			$sql = "select `s`.*,
				(ifnull(
					(
						select `num`
						from `products_num`
						where `product_id` = :id
						and `shop_id` = `s`.`id`
						limit 0,1
					),0)
					$ordersSql
				) as `num`
				from `shopes` `s`
				where `s`.`status_id` = 1";
		}

		$res = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':shop_id', $shop_id)
			->execute()
			->as_array();

		foreach($res as $row){
			$product_num[$row['id']] = $row;
		}

		return $shop_id == 0 ? $product_num : (count($res) === 0 ? [] : $res[0]);
	}

	public function getOrdersProductNum($id, $shop_id = 0)
	{
		$product_num = [];

		$sql = "";

		$res = DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->param(':shop_id', $shop_id)
			->execute()
			->as_array();
		foreach($res as $row){
			$product_num[$row['id']] = $row;
		}
		return $shop_id == 0 ? $product_num : (count($res) === 0 ? [] : $res[0]);
	}

	public function getBrands($id = '')
	{
		if($id == '')
			$sql = "select * from `brands` where `status_id` = 1";
		else
			$sql = "select * from `brands` where `id` = :id and `status_id` = 1";
		return DB::query(Database::SELECT,$sql)
			->param(':id', $id)
			->execute()
			->as_array();
	}
	
	public function getAssort($params = [])
	{
		$searchText = !empty(Arr::get($params, 'searchText', '')) ? Arr::get($params, 'searchText', '') : 'emptyText';
		$products = $this->getProduct();
		foreach($products as $i => $product){
			$shopesNum = $this->getProductNum($product['id'], Model::factory('Shop')->getManagerShop());
			$products[$i]['root_num'] = $shopesNum[Model::factory('Shop')->getManagerShop()]['num'];
			if (preg_match("/" . $searchText . "/i", $product['name']))
				$products[$i]['product_name'] = str_replace('"', "'", $product['name']);
			else
				unset($products[$i]);
		}
		return json_encode($products);
	}


	public function getSearchResult($params)
	{
		$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select `b`.`name` from `brands` `b` where `b`.`id` = `brand_id` and `b`.`status_id` = 1) as `brand_name`
			from `products` `p`
			where `p`.`name` like :searchName and `p`.`status_id` = 1 order by `brand_name`, `p`.`code`";
		return DB::query(Database::SELECT,$sql)
			->param(':searchName', '%'.Arr::get($params,'mainSearchName','').'%')
			->execute()
			->as_array();
	}

	public function getProductParams($productId)
	{
		return DB::query(Database::SELECT, "select * from `products_params` where `product_id` = :product_id and `status_id` = 1")
			->param(':product_id', $productId)
			->execute()
			->as_array();
	}

    /**
     * @param array $files
     * @param int $categoryId
     * @return string
     */
    public function loadCategoryImg($files, $categoryId)
    {
        $imageName = preg_replace("/[^0-9a-z.]+/i", "0", $files['name']);
        $file_name = 'public/i/categories/original/' . $categoryId . '_' . $imageName;

        if (copy($files['tmp_name'], $file_name))	{
            $image = Image::factory($file_name);
            $image
                ->resize(500, NULL)
                ->save($file_name,100)
            ;

            $thumb_file_name = 'public/i/categories/thumb/' . $categoryId . '_' . $imageName;

            if (copy($files['tmp_name'], $thumb_file_name))	{
                $thumb_image = Image::factory($thumb_file_name);
                $thumb_image
                    ->resize(300, NULL)
                    ->save($thumb_file_name,100)
                ;

                DB::update('products__categories')
                    ->set(['img_src' => $imageName])
                    ->where('id', '=', $categoryId)
                    ->execute();

                return $imageName;
            }
        }

        return null;
    }
}
