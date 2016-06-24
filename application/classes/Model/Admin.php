<?php

/**
 * Class Model_Admin
 */
class Model_Admin extends Kohana_Model {

	private  $user_id;
	public function __construct() {
		if(Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
		}
		else {
			$this->user_id = Guestid::factory()->get_id();
		}

	    DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
    }

	public function getNow() {
		$res = DB::query(Database::SELECT, 'select now() as `now` from dual')
					->execute()
					->current()
				;

		return $res['now'];
	}

	public function addGroup($post)
	{
		$add_group = Arr::get($post,'addgroup',1);
		if($add_group == 1) {
			$sql="insert into `products_group_1` (`name`) values (:name)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', str_replace('"',"'",Arr::get($post,'group_name','')));
			$query->execute();
			$sql="select last_insert_id() as `new_id` from `products_group_1` limit 0,1";
			$query=DB::query(Database::SELECT,$sql);
			$res = $query->execute()->as_array();
			$new_id = $res[0]['new_id'];
			$sql="update `products_group_1` set `parent_id` = :new_id, `status_id` = 1 where `id` = :new_id";
			$query=DB::query(Database::UPDATE,$sql);
			$query->param(':new_id', $new_id);
			$query->execute();
		} else if($add_group == 2) {
			$sql="insert into `products_group_2` (`parent_id`, `name`) values (:parent_id, :name)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', str_replace('"',"'",Arr::get($post,'group_name','')));
			$query->param(':parent_id', Arr::get($post,'parent_id',''));
			$query->execute();
		} else if($add_group == 3) {
			$sql="insert into `products_group_3` (`parent_id`, `name`) values (:parent_id, :name)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', str_replace('"',"'",Arr::get($post,'group_name','')));
			$query->param(':parent_id', Arr::get($post,'parent_id',''));
			$query->execute();
		}
	}

	public function redactGroup($post)
	{
		$redactGroup = Arr::get($post,'redactGroup',0);
		$id = Arr::get($post,'redactGroupId',0);
		if($redactGroup != 0) {
			$sql="update `products_group_$redactGroup` set `name` = :name where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $id)
				->param(':name', addslashes(Arr::get($post,'groupName','')))
				->execute();
		}
	}

	public function removeGroup($post)
	{
		$remove_group = Arr::get($post,'removegroup',0);
		$type_id = Arr::get($post,'type_id',1);
		$sql="update `products_group_$type_id` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
		->param(':id', $remove_group)
		->execute();
	}
	
	public function addProduct($post)
	{
		$add_product = Arr::get($post,'addproduct',0);
		if($add_product != 0) {
			$sql="insert into `products` (`name`, `group_1`, `group_2`, `group_3`) values (:name, :group_1, :group_2, :group_3)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', preg_replace('/[\"\']+/i','',Arr::get($post,'product_name','')));
			$query->param(':group_1', Arr::get($post,'group_1',''));
			$query->param(':group_2', Arr::get($post,'group_2',''));
			$query->param(':group_3', Arr::get($post,'group_3',''));
			$query->execute();
		}
	}
	
	public function removeProduct($post)
	{
		$remove_product = Arr::get($post,'removeproduct',0);
		$type_id = Arr::get($post,'type_id',1);
		$sql="update `products` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
		->param(':id', $remove_product)
		->execute();
	}
	
	public function searchRedactProduct($post)
	{
		
		$sql = "select * from `products` where `code` = :search_id or `name` like :search_text or `name` like :search_replace_text and `status_id` = 1";
		$query=DB::query(Database::SELECT,$sql);
		$query->param(':search_id', Arr::get($post,'redact_search',''));
		$query->param(':search_text', '%'.Arr::get($post,'redact_search','').'%');
		$query->param(':search_replace_text', '%'.preg_replace("/[^0-9a-z]+/i", "", Arr::get($post,'redact_search','')).'%');
		$product=$query->execute()->as_array();
		return $product;
	}
	
	public function setProductInfo($post)
	{
		$id = Arr::get($post,'redactproduct',0);
		$name = Arr::get($post,'name','');
		$code = Arr::get($post,'code','');
		$short_description = Arr::get($post,'short_description','');
		$description = Arr::get($post,'description','');
		$brand = Arr::get($post,'brand',0);
		$price = Arr::get($post,'price',0);
		$purchase_price = Arr::get($post,'purchase_price',0);
		$sql = "update `products` set `name` = :name, `code` = :code, `short_description` = :short_description, `description` = :description, `price` = :price, `purchase_price` = :purchase_price, `brand_id` = :brand, `status_id` = 1 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', $id)
			->param(':name', $name)
			->param(':code', $code)
			->param(':short_description', $short_description)
			->param(':description', $description)
			->param(':price', $price)
			->param(':purchase_price', $purchase_price)
			->param(':brand', $brand)
			->execute();
	}
	
	public function loadProductImg($files, $product_id)
	{
		$sql = "insert into `products_imgs` (`product_id`) values (:id)";
		$query = DB::query(Database::INSERT,$sql);
		$query->param(':id', $product_id);
		$query->execute();
		$sql = "select last_insert_id() as `new_id` from `products_imgs`";
		$query = DB::query(Database::SELECT,$sql);
		$res = $query->execute()->as_array();
		$new_id = $res[0]['new_id'];
		$file_name = $new_id.'_'.Arr::get($files['imgname'],'name','');
		$tmp_image = $files['imgname']['tmp_name'];
		$connect = ftp_connect('teleantenna25.ru');
		if(!$connect)
			return false;
		$login_result = ftp_login($connect, "load@teleantenna25.ru", "NvC2Bb7qZRJ");
		if(!$login_result)
			return false;
		if (ftp_put($connect, 'original/'.$file_name, $tmp_image, FTP_BINARY)) {
			if (ftp_put($connect, 'thumb/'.$file_name, $tmp_image, FTP_BINARY)) {
				$sql = "update `products_imgs` set `src` = :src,`status_id` = 1 where `id` = :id";
				$query=DB::query(Database::UPDATE,$sql);
				$query->param(':id', $new_id);
				$query->param(':src', $file_name);
				$query->execute();
			}
		}
		unlink($tmp_image);
		ftp_close($connect);

	}
	
	//Готовое решение с картинками
	public function picture($image_file)
	{
			$this->image_file=$image_file;
			$image_info = getimagesize($this->image_file);
			$this->image_width = $image_info[0];
			$this->image_height = $image_info[1];
			switch($image_info[2]) {
					case 1: $this->image_type = 'gif'; break;//1: IMAGETYPE_GIF
					case 2: $this->image_type = 'jpeg'; break;//2: IMAGETYPE_JPEG
					case 3: $this->image_type = 'png'; break;//3: IMAGETYPE_PNG
					case 4: $this->image_type = 'swf'; break;//4: IMAGETYPE_SWF
					case 5: $this->image_type = 'psd'; break;//5: IMAGETYPE_PSD
					case 6: $this->image_type = 'bmp'; break;//6: IMAGETYPE_BMP
					case 7: $this->image_type = 'tiffi'; break;//7: IMAGETYPE_TIFF_II (порядок байт intel)
					case 8: $this->image_type = 'tiffm'; break;//8: IMAGETYPE_TIFF_MM (порядок байт motorola)
					case 9: $this->image_type = 'jpc'; break;//9: IMAGETYPE_JPC
					case 10: $this->image_type = 'jp2'; break;//10: IMAGETYPE_JP2
					case 11: $this->image_type = 'jpx'; break;//11: IMAGETYPE_JPX
					case 12: $this->image_type = 'jb2'; break;//12: IMAGETYPE_JB2
					case 13: $this->image_type = 'swc'; break;//13: IMAGETYPE_SWC
					case 14: $this->image_type = 'iff'; break;//14: IMAGETYPE_IFF
					case 15: $this->image_type = 'wbmp'; break;//15: IMAGETYPE_WBMP
					case 16: $this->image_type = 'xbm'; break;//16: IMAGETYPE_XBM
					case 17: $this->image_type = 'ico'; break;//17: IMAGETYPE_ICO
					default: $this->image_type = ''; break;
			}
			$this->fotoimage();
	}
	
	private function fotoimage()
	{
			switch($this->image_type) {
					case 'gif': $this->image = imagecreatefromgif($this->image_file); break;
					case 'jpeg': $this->image = imagecreatefromjpeg($this->image_file); break;
					case 'png': $this->image = imagecreatefrompng($this->image_file); break;
			}
	}
	
	public function autoimageresize($new_w, $new_h)
	{
			$difference_w = 0;
			$difference_h = 0;
			if($this->image_width < $new_w && $this->image_height < $new_h) {
					$this->imageresize($this->image_width, $this->image_height);
			}
			else {
					if($this->image_width > $new_w) {
							$difference_w = $this->image_width - $new_w;
					}
					if($this->image_height > $new_h) {
							$difference_h = $this->image_height - $new_h;
					}
							if($difference_w > $difference_h) {
									$this->imageresizewidth($new_w);
							}
							elseif($difference_w < $difference_h) {
									$this->imageresizeheight($new_h);
							}
							else {
									$this->imageresize($new_w, $new_h);
							}
			}
	}
	
	public function percentimagereduce($percent)
	{
			$new_w = $this->image_width * $percent / 100;
			$new_h = $this->image_height * $percent / 100;
			$this->imageresize($new_w, $new_h);
	}
	
	public function imageresizewidth($new_w)
	{
			$new_h = $this->image_height * ($new_w / $this->image_width);
			$this->imageresize($new_w, $new_h);
	}
	
	public function imageresizeheight($new_h)
	{
			$new_w = $this->image_width * ($new_h / $this->image_height);
			$this->imageresize($new_w, $new_h);
	}
	
	public function imageresize($new_w, $new_h)
	{
			$new_image = imagecreatetruecolor($new_w, $new_h);
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->image_width, $this->image_height);
			$this->image_width = $new_w;
			$this->image_height = $new_h;
			$this->image = $new_image;
	}
	
	public function imagesave($image_type='jpeg', $image_file=NULL, $image_compress=100, $image_permiss='')
	{
			if($image_file==NULL) {
					switch($this->image_type) {
							case 'gif': header("Content-type: image/gif"); break;
							case 'jpeg': header("Content-type: image/jpeg"); break;
							case 'png': header("Content-type: image/png"); break;
					}
			}
			switch($this->image_type) {
					case 'gif': imagegif($this->image, $image_file); break;
					case 'jpeg': imagejpeg($this->image, $image_file, $image_compress); break;
					case 'png': imagepng($this->image, $image_file); break;
			}
			if($image_permiss != '') {
					chmod($image_file, $image_permiss);
			}
	}
	
	public function imageout()
	{
			imagedestroy($this->image);
	}
	
	public function addCity($post)
	{
		$add_city = Arr::get($post,'addcity',0);
		if($add_city != 0) {
			$sql="insert into `cities` (`name`) values (:name)";
			DB::query(Database::INSERT,$sql)
			->param(':name', str_replace('"',"'",Arr::get($post,'city_name','')))
			->execute();
		}
	}
	
	public function removeCity($post)
	{
		$removecity = Arr::get($post,'removecity',0);
		$sql="update `cities` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
		->param(':id', $removecity)
		->execute();
	}

	public function redactCity($post)
	{
		$redactcity = Arr::get($post,'redactcity',0);
		$sql="update `cities` set `name` = :name where `id` = :id";
		DB::query(Database::UPDATE,$sql)
		->param(':id', $redactcity)
		->param(':name', Arr::get($post,'cityName',''))
		->execute();
	}

	public function addShop($post)
	{
		$add_shop = Arr::get($post,'addshop',0);
		if($add_shop != 0) {
			$sql="insert into `shopes` (`city_id`, `name`) values (:city_id, :name)";
			DB::query(Database::INSERT,$sql)
			->param(':name', str_replace('"',"'",Arr::get($post,'shop_name','')))
			->param(':city_id', Arr::get($post,'city',''))
			->execute();
		}
	}
	
	public function removeShop($post)
	{
		$removeshop = Arr::get($post,'removeshop',0);
		$sql="update `shopes` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', $removeshop)
			->execute();
	}
	
	public function redactShop($post)
	{
		$sql="update `shopes` set `name` = :name, `short_name` = :short_name, `address` = :address, `info` = :info, `status_id` = 1 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($post,'redactshop',0))
			->param(':name', Arr::get($post,'shop_name',''))
			->param(':short_name', Arr::get($post,'shop_short_name',''))
			->param(':address', Arr::get($post,'shop_address',''))
			->param(':info', Arr::get($post,'info',''))
			->execute();
	}

	public function loadImgShop($files, $params = [])
	{
		$file_name = 'public/img/shopes/'.Arr::get($params, 'loadshopimg', 0).'_'.Arr::get($files['imgname'],'name','');
		if (copy($files['imgname']['tmp_name'], $file_name))	{
			$sql = "update `shopes` set `img` = :imgname where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', Arr::get($params, 'loadshopimg', 0))
				->param(':imgname', Arr::get($files['imgname'],'name',''))
				->execute();
		}
	}

	public function setProductNum($post)
	{
		$product_id = Arr::get($post,'redactproduct',0);
		$shop_id = Arr::get($post,'shop',0);
		$num = Arr::get($post,'num',0);
		$sql = "update `products_num` set `num` = :num, `date` = now() where `product_id` = :product_id and `shop_id` = :shop_id";
		$res = DB::query(Database::UPDATE,$sql)
			->param(':product_id', $product_id)
			->param(':num', $num)
			->param(':shop_id', $shop_id)
			->execute();
		if($res == 0) {
			$sql = "insert into `products_num` (`num`, `date`, `product_id`, `shop_id`) values (:num, now(), :product_id, :shop_id)";
			$res = DB::query(Database::INSERT,$sql)
				->param(':product_id', $product_id)
				->param(':num', $num)
				->param(':shop_id', $shop_id)
				->execute();
		}
	}
	
	public function removeProductImg($post)
	{
		$img_id = Arr::get($post,'removeimg',0);
		$sql="delete from `products_imgs` where `id` = :id";
		DB::query(Database::DELETE,$sql)
			->param(':id', $img_id)
			->execute();
	}
	
	public function addBrand($post)
	{
		$add_brand = Arr::get($post,'addbrand',0);
		if($add_brand != 0) {
			$sql="insert into `brands` (`name`) values (:name)";
			DB::query(Database::INSERT,$sql)
			->param(':name', str_replace('"',"'",Arr::get($post,'brand_name','')))
			->execute();
		}
	}
	
	public function removeBrand($post)
	{
		$removebrand = Arr::get($post,'removebrand',0);
		$sql="update `brands` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
		->param(':id', $removebrand)
		->execute();
	}

	public function getGetString($get)
	{
		$getString = '';
		$i = 0;
		foreach($get as $key => $val){
			if(!preg_match('/page/i',$key)) {
				$terminated = $i == 0 ? '?' : '&';
				$getString .= $terminated . $key . '=' . $val;
				$i++;
			}

		}
		return $getString;
	}

	public function getLimit()
	{
		return 40;
	}
	
	public function addNewRealization()
	{
		$sql="insert into `realizations` (`user_id`, `date`) values (:user_id, now())";
			DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `realizations` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}
	
	public function getRealizationGoodsData($params)
	{
		$managerShop = Model::factory('Shop')->getManagerShop();
		$productId = Arr::get($params, 'productId', 0);
		$code = Arr::get($params, 'code', 0);
		$row = Arr::get($params, 'row', 0);
		if($row != 0)
			$sql = "select `p`.`name` as `product_name`,
			`p`.`code` as `product_code`,
			`p`.`price` as `price`,
			`r`.`status_id` as `realization_status`,
			`rg`.*,
			ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
			from `realizations` `r`
			inner join `realizations_goods` `rg`
				on `r`.`id` = `rg`.`realization_id`
			inner join `products` `p`
				on `rg`.`product_id` = `p`.`id`
			where `rg`.`id` = :row
			and `rg`.`realization_id` = :realization_id";
		else if($code != 0)
			$sql = "select `p`.`name` as `product_name`,
			`p`.`code` as `product_code`,
			`p`.`price` as `price`,
			`r`.`status_id` as `realization_status`,
			`rg`.*,
			ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
			from `realizations` `r`
			inner join `realizations_goods` `rg`
				on `r`.`id` = `rg`.`realization_id`
			inner join `products` `p`
				on `rg`.`product_id` = `p`.`id`
			where `p`.`code` = :code
			and `rg`.`realization_id` = :realization_id";
		else if($productId == 0)
			$sql = "select `p`.`name` as `product_name`,
			`p`.`code` as `product_code`,
			`p`.`price` as `price`,
			`r`.`status_id` as `realization_status`,
			`rg`.*,
			ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
			from `realizations` `r`
			inner join `realizations_goods` `rg`
				on `r`.`id` = `rg`.`realization_id`
			inner join `products` `p`
				on `rg`.`product_id` = `p`.`id`
			where `rg`.`realization_id` = :realization_id";
		else
			$sql = "select `p`.`name` as `product_name`,
			`p`.`code` as `product_code`,
			`p`.`price` as `price`,
			`r`.`status_id` as `realization_status`,
			`rg`.*,
			ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
			from `realizations` `r`
			inner join `realizations_goods` `rg`
				on `r`.`id` = `rg`.`realization_id`
			inner join `products` `p`
				on `rg`.`product_id` = `p`.`id`
			where `rg`.`realization_id` = :realization_id
			and `rg`.`product_id` = :productId";
		$res = DB::query(Database::SELECT,$sql)
			->param(':productId', Arr::get($params, 'productId', 0))
			->param(':code', Arr::get($params, 'code', 0))
			->param(':row', Arr::get($params, 'row', 0))
			->param(':realization_id', Arr::get($params, 'realizationId', 0))
			->param(':manager_shop', $managerShop)
			->execute()
			->as_array();
		return $res;
	}
		
	public function getRealizationData($realization_id)
	{
		$managerShop = Model::factory('Shop')->getManagerShop();
		$sql = "select `p`.`name` as `product_name`,
		`p`.`code` as `product_code`,
		`r`.`status_id` as `realization_status`,
		`r`.`contractor_id` as `contractor_id`,
		`rg`.*,
		ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`,
		ifnull((select `pnh`.`root_num` from `products_num_history` `pnh` where `pnh`.`product_id` = `p`.`id` and `pnh`.`document` = 'realization' and `pnh`.`document_id` = `r`.`id` limit 0,1),0) as `history_num`
		from `realizations` `r`
		inner join `realizations_goods` `rg`
			on `r`.`id` = `rg`.`realization_id`
		inner join `products` `p`
			on `rg`.`product_id` = `p`.`id`
		where `rg`.`realization_id` = :realization_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':realization_id', $realization_id)
			->param(':manager_shop', $managerShop)
			->execute()
			->as_array();
		return $res;
	}
		
	public function getRealizationStatus($realization_id)
	{
		$sql = "select `r`.`status_id` from `realizations` `r` where `r`.`id` = :realization_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':realization_id', $realization_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}
	
	public function addRealisationPosition($params)
	{
		if(!empty($params['productId'])) {
			$realizationGoodsData = $this->getRealizationGoodsData($params);
			$managerShop = Model::factory('Shop')->getManagerShop();
			$productInfo = Model::factory('Product')->getProductInfo(Arr::get($params, 'productId', 0));
			if(count($realizationGoodsData) > 0) {
				$sql="update `realizations_goods` set `product_id` = :product_id, `num` = :num, `price` = :price, `shop_id` = :manager_shop where `id` = :id";
				DB::query(Database::UPDATE,$sql)
				->param(':id', Arr::get($params, 'row', 0))
				->param(':product_id', Arr::get($params, 'productId', 0))
				->param(':manager_shop', $managerShop)
				->param(':num', Arr::get($params, 'num', 0))
				->param(':price', Arr::get($productInfo, 'price', 0))
				->execute();
				return true;
			} else {
				$sql="insert into `realizations_goods` (`realization_id`, `product_id`, `shop_id`, `price`, `num`) values (:realization_id, :product_id, :manager_shop, :price, :num)";
				DB::query(Database::INSERT,$sql)
				->param(':realization_id', Arr::get($params, 'realizationId', 0))
				->param(':manager_shop', $managerShop)
				->param(':product_id', Arr::get($params, 'productId', 0))
				->param(':num', Arr::get($params, 'num', 0))
				->param(':price', Arr::get($productInfo, 'price', 0))
				->execute();
				return true;
			}
			$this->checkPrice(['realization' => Arr::get($params, 'realizationId', 0)]);
		} else {
			return false;
		}
	}
			
	public function removeRealizationPosition($params)
	{
		if(!empty($params['removeRealizationPosition'])) {
			$sql="delete from `realizations_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
			->param(':id', Arr::get($params, 'removeRealizationPosition', 0))
			->execute();
			return true;
		} else {
			return false;
		}
	}
					
	public function carryOutRealization($params)
	{
		if(!empty($params['carryOutRealization'])) {
			$realizationData = $this->getRealizationData($params['carryOutRealization']);
			foreach($realizationData as $data){
				$sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
				values (:product_id, :shop_id,
				 (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
				:num, 'realization', :document_id, now())";
				$insert = DB::query(Database::INSERT,$sql)
					->param(':document_id', Arr::get($params, 'carryOutRealization', 0))
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', (-1 * $data['num']))
					->execute();
				$historyId = $insert[0];
				$sql="update `products_num` set `num` = (`num` - :num) where `product_id` = :product_id and `shop_id` = :shop_id";
				DB::query(Database::UPDATE,$sql)
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', $data['num'])
					->execute();
				$sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
				DB::query(Database::UPDATE,$sql)
					->param(':product_id', $data['product_id'])
					->param(':history_id', $historyId)
                    ->param(':shop_id', $data['shop_id'])
					->execute();
			}
			$sql="update `realizations` set `status_id` = 2 where `id` = :id";
				DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutRealization'])
				->execute();
			$sql="insert into `realizations_status_history` (`realization_id`, `status_id`, `user_id`, `date`) values (:realization_id, 2, :user_id, now())";
				DB::query(Database::INSERT,$sql)
				->param(':realization_id', Arr::get($params, 'carryOutRealization', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}

    public function carryOutRealizationPost($params)
    {
        if(!empty($params['carryOutRealizationPost'])) {
            $shopId = Model::factory('Shop')->getManagerShop();
            $userId = null;

            foreach (Arr::get($params, 'id', []) as $key => $data) {
                if (empty($params['id'][$key])) {
                    continue;
                }

                $userId = empty(Arr::get($params, 'realizationContractor')) ? null : Arr::get($params, 'realizationContractor');
                $count = DB::query(Database::SELECT, "
                    SELECT `id` FROM `realizations_goods` WHERE `realization_id` = :realization_id
                    AND `product_id` = :product_id
                    AND `shop_id` = :manager_shop
                ")
                    ->param(':realization_id', Arr::get($params, 'carryOutRealizationPost', 0))
                    ->param(':manager_shop', $shopId)
                    ->param(':product_id', $params['id'][$key])
                    ->execute()
                    ->count();

                if ($count == 0) {
                    $sql="insert into `realizations_goods` (`realization_id`, `product_id`, `shop_id`, `price`, `num`)
                    values (:realization_id, :product_id, :manager_shop, :price, :num)";
                    DB::query(Database::INSERT,$sql)
                        ->param(':realization_id', Arr::get($params, 'carryOutRealizationPost', 0))
                        ->param(':manager_shop', $shopId)
                        ->param(':product_id', $params['id'][$key])
                        ->param(':num', $params['num'][$key])
                        ->param(':price', $params['price'][$key])
                        ->execute();

                    $sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
                    values (:product_id, :shop_id,
                     (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
                    :num, 'realization', :document_id, now())";
                    $insert = DB::query(Database::INSERT,$sql)
                        ->param(':document_id', Arr::get($params, 'carryOutRealizationPost', 0))
                        ->param(':shop_id', $shopId)
                        ->param(':product_id', $params['id'][$key])
                        ->param(':num', (-1 * $params['num'][$key]))
                        ->execute();

                    $historyId = $insert[0];

                    $sql = "select `id` from `products_num` where `product_id` = :product_id and `shop_id` = :shop_id";
                    $checkCount = DB::query(Database::SELECT,$sql)
                        ->param(':shop_id', $shopId)
                        ->param(':product_id', $params['id'][$key])
                        ->execute()
                        ->count();

                    if($checkCount == 0){
                        $sql="insert into `products_num` (`product_id`, `shop_id`, `num`, `date`) values (:product_id, :shop_id, :num, now())";
                        DB::query(Database::INSERT,$sql)
                            ->param(':shop_id', $shopId)
                            ->param(':product_id', $params['id'][$key])
                            ->param(':num', $params['num'][$key])
                            ->execute();
                    } else {
                        $sql="update `products_num` set `num` = (`num` - :num) where `product_id` = :product_id and `shop_id` = :shop_id";
                        DB::query(Database::UPDATE,$sql)
                            ->param(':shop_id', $shopId)
                            ->param(':product_id', $params['id'][$key])
                            ->param(':num', $params['num'][$key])
                            ->execute();
                    }

                    $sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
                    DB::query(Database::UPDATE,$sql)
                        ->param(':product_id', $params['id'][$key])
                        ->param(':history_id', $historyId)
                        ->param(':shop_id', $shopId)
                        ->execute();
                }
            }

            $sql="update `realizations` set `contractor_id` = :user_id, `status_id` = 2 where `id` = :id";
            DB::query(Database::UPDATE,$sql)
                ->param(':id', $params['carryOutRealizationPost'])
                ->param(':user_id', $userId)
                ->execute();

            $sql="insert into `realizations_status_history` (`realization_id`, `status_id`, `user_id`, `date`) values (:realization_id, 2, :user_id, now())";
            DB::query(Database::INSERT,$sql)
                ->param(':realization_id', Arr::get($params, 'carryOutRealizationPost', 0))
                ->param(':user_id', $this->user_id)
                ->execute();

            return true;
        } else {
            return false;
        }
    }

	public function getRealizationsList($params)
	{
		$now = $this->getNow();

		$firstDate = empty(Arr::get($params, 'realizations_first_date')) ? $now : $params['realizations_first_date'];
		$lastDate = empty(Arr::get($params, 'realizations_last_date')) ? $now : $params['realizations_last_date'];

		$limit = $this->getLimit();

		$sqlLimit = Arr::get($params, 'archive', '') != 'realization' ? '' : "limit ".((Arr::get($params, 'realizationPage', 1) - 1)*$limit).", $limit";

		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(
				select count(`r1`.`id`)
				from `realizations` `r1`
				inner join `documents_status` `rs1`
				on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
				on `r1`.`user_id` = `u1`.`id`
				where `r1`.`date` between :firstDate and :lastDate
			) as `realizations_count`
			from `realizations` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`date` between :firstDate and :lastDate
			order by `id` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(
				select count(`r1`.`id`)
				from `realizations` `r1`
				inner join `documents_status` `rs1`
					on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
					on `r1`.`user_id` = `u1`.`id`
				where `r1`.`user_id` = :user_id
				and `r1`.`date` between :firstDate and :lastDate
			) as `realizations_count`
			from `realizations` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			and `r`.`date` between :firstDate and :lastDate
			order by `id` desc
			$sqlLimit";

		return
			DB::query(Database::SELECT,$sql)
				->parameters([
					':user_id' => $this->user_id,
					':firstDate' => Date::convertDateFromFormat($firstDate, 'Y-m-d 00:00:00'),
					':lastDate' => Date::convertDateFromFormat($lastDate, 'Y-m-d 23:59:59'),
				])
				->execute()
				->as_array()
			;
	}
		
	public function addNewIncome()
	{
		$sql="insert into `incomes` (`user_id`, `date`) values (:user_id, now())";
			DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `incomes` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}
		
	public function getIncomeData($income_id)
	{
		$managerShop = Model::factory('Shop')->getManagerShop();
		$sql = "select `p`.`name` as `product_name`,
		`p`.`code` as `product_code`,
		`r`.`status_id` as `income_status`,
		`rg`.*,
		ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
		from `incomes` `r`
		inner join `incomes_goods` `rg`
			on `r`.`id` = `rg`.`income_id`
		inner join `products` `p`
			on `rg`.`product_id` = `p`.`id`
		where `rg`.`income_id` = :income_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':income_id', $income_id)
			->param(':manager_shop', $managerShop)
			->execute()
			->as_array();
		return $res;
	}
		
		
	public function getIncomeStatus($income_id)
	{
		$sql = "select `r`.`status_id` from `incomes` `r` where `r`.`id` = :income_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':income_id', $income_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}
	
		
	public function addIncomePosition($params)
	{
		if(!empty($params['productId'])) {
			$managerShop = Model::factory('Shop')->getManagerShop();
			if($params['row'] != 0) {
				$sql="update `incomes_goods` set `product_id` = :product_id, `shop_id` = :shop_id, `comment` = :comment, `num` = :num, `price` = :price where `id` = :id";
				DB::query(Database::UPDATE,$sql)
					->param(':id', Arr::get($params, 'row', 0))
					->param(':product_id', Arr::get($params, 'productId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':num', Arr::get($params, 'num', 0))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			} else {
				$sql="insert into `incomes_goods` (`income_id`, `product_id`, `shop_id`, `comment`, `price`, `num`) values (:income_id, :product_id, :shop_id, :comment, :price, :num)";
				DB::query(Database::INSERT,$sql)
					->param(':income_id', Arr::get($params, 'incomeId', 0))
					->param(':product_id', Arr::get($params, 'productId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':num', Arr::get($params, 'num', 0))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			}
		} else {
			return false;
		}
	}
	
				
	public function removeIncomePosition($params)
	{
		if(!empty($params['removeIncomePosition'])) {
			$sql="delete from `incomes_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
			->param(':id', Arr::get($params, 'removeIncomePosition', 0))
			->execute();
			return true;
		} else {
			return false;
		}
	}
		
						
	public function carryOutIncome($params)
	{
		if(!empty($params['carryOutIncome'])) {
			$incomeData = $this->getincomeData($params['carryOutIncome']);
			foreach($incomeData as $data){
				$sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
				values (:product_id, :shop_id,
				 (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
				:num, 'income', :document_id, now())";
				$insert = DB::query(Database::INSERT,$sql)
					->param(':document_id', Arr::get($params, 'carryOutIncome', 0))
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', $data['num'])
					->execute();
				$historyId = $insert[0];
				$sql = "select `id` from `products_num` where `product_id` = :product_id and `shop_id` = :shop_id";
				$checkCount = DB::query(Database::SELECT,$sql)
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->execute()
					->count();
				if($checkCount == 0){
					$sql="insert into `products_num` (`product_id`, `shop_id`, `num`, `date`) values (:product_id, :shop_id, :num, now())";
					DB::query(Database::INSERT,$sql)
						->param(':shop_id', $data['shop_id'])
						->param(':product_id', $data['product_id'])
						->param(':num', $data['num'])
						->execute();
				} else {
					$sql="update `products_num` set `num` = (`num` + :num) where `product_id` = :product_id and `shop_id` = :shop_id";
					DB::query(Database::UPDATE,$sql)
						->param(':shop_id', $data['shop_id'])
						->param(':product_id', $data['product_id'])
						->param(':num', $data['num'])
						->execute();
				}
				$sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
				DB::query(Database::UPDATE,$sql)
					->param(':product_id', $data['product_id'])
					->param(':history_id', $historyId)
                    ->param(':shop_id', $data['shop_id'])
					->execute();
			}
			$sql="update `incomes` set `status_id` = 2 where `id` = :id";
				DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutIncome'])
				->execute();
			$sql="insert into `incomes_status_history` (`income_id`, `status_id`, `user_id`, `date`) values (:income_id, 2, :user_id, now())";
				DB::query(Database::INSERT,$sql)
				->param(':income_id', Arr::get($params, 'carryOutIncome', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function carryOutIncomePost($params)
	{
		if(!empty($params['carryOutIncomePost'])) {
			$shopId = Model::factory('Shop')->getManagerShop();
            $userId = null;
			foreach (Arr::get($params, 'id', []) as $key => $data) {
                if (empty($params['id'][$key])) {
                    continue;
                }

                $userId = empty(Arr::get($params, 'incomeContractor')) ? null : Arr::get($params, 'incomeContractor');

				$sql="insert into `incomes_goods` (`income_id`, `product_id`, `shop_id`, `comment`, `price`, `num`) values (:income_id, :product_id, :shop_id, :comment, :price, :num)";
				DB::query(Database::INSERT,$sql)
					->param(':income_id', Arr::get($params, 'carryOutIncomePost', 0))
					->param(':product_id', $params['id'][$key])
					->param(':shop_id', $shopId)
					->param(':comment', preg_replace('/[\"\']+/','',$params['comment'][$key]))
					->param(':num', $params['num'][$key])
					->param(':price', 0)
					->execute();

				$sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
				values (:product_id, :shop_id,
				 (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
				:num, 'income', :document_id, now())";
				$insert = DB::query(Database::INSERT,$sql)
					->param(':document_id', Arr::get($params, 'carryOutIncomePost', 0))
					->param(':shop_id', $shopId)
					->param(':product_id', $params['id'][$key])
					->param(':num', $params['num'][$key])
					->execute();

				$historyId = $insert[0];

				$sql = "select `id` from `products_num` where `product_id` = :product_id and `shop_id` = :shop_id";
				$checkCount = DB::query(Database::SELECT,$sql)
					->param(':shop_id', $shopId)
					->param(':product_id', $params['id'][$key])
					->execute()
					->count();

				if($checkCount == 0){
					$sql="insert into `products_num` (`product_id`, `shop_id`, `num`, `date`) values (:product_id, :shop_id, :num, now())";
					DB::query(Database::INSERT,$sql)
						->param(':shop_id', $shopId)
						->param(':product_id', $params['id'][$key])
						->param(':num', $params['num'][$key])
						->execute();
				} else {
					$sql="update `products_num` set `num` = (`num` + :num) where `product_id` = :product_id and `shop_id` = :shop_id";
					DB::query(Database::UPDATE,$sql)
						->param(':shop_id', $shopId)
						->param(':product_id', $params['id'][$key])
						->param(':num', $params['num'][$key])
						->execute();
				}

				$sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
				DB::query(Database::UPDATE,$sql)
					->param(':product_id', $params['id'][$key])
					->param(':history_id', $historyId)
                    ->param(':shop_id', $shopId)
					->execute();
			}

            $sql="update `incomes` set `contractor_id` = :user_id, `status_id` = 2 where `id` = :id";
            DB::query(Database::UPDATE,$sql)
                ->param(':id', $params['carryOutIncomePost'])
                ->param(':user_id', $userId)
                ->execute();

			$sql="insert into `incomes_status_history` (`income_id`, `status_id`, `user_id`, `date`) values (:income_id, 2, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':income_id', Arr::get($params, 'carryOutIncomePost', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function getIncomesList($params)
	{
		$now = $this->getNow();

		$firstDate = empty(Arr::get($params, 'incomes_first_date')) ? $now : $params['incomes_first_date'];
		$lastDate = empty(Arr::get($params, 'incomes_last_date')) ? $now : $params['incomes_last_date'];

		$limit = $this->getLimit();

		$sqlLimit = "limit ".((Arr::get($params, 'incomePage', 1) - 1)*$limit).", $limit";

		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(
				select count(`r1`.`id`)
				from `incomes` `r1`
				inner join `documents_status` `rs1`
					on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
					on `r1`.`user_id` = `u1`.`id`
				where `r1`.`date` between :firstDate and :lastDate
			) as `incomes_count`
			from `incomes` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`date` between :firstDate and :lastDate
			order by `r`.`date` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(
				select count(`r1`.`id`)
				from `incomes` `r1`
				inner join `documents_status` `rs1`
				on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
				on `r1`.`user_id` = `u1`.`id`
				where `r1`.`user_id` = :user_id
				and `r1`.`date` between :firstDate and :lastDate
			) as `incomes_count`
			from `incomes` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			and `r`.`date` between :firstDate and :lastDate
			order by `r`.`date` desc
			$sqlLimit";

		return DB::query(Database::SELECT,$sql)
				->parameters([
					':user_id' => $this->user_id,
					':firstDate' => Date::convertDateFromFormat($firstDate, 'Y-m-d 00:00:00'),
					':lastDate' => Date::convertDateFromFormat($lastDate, 'Y-m-d 23:59:59'),
				])
				->execute()
				->as_array()
			;
	}
					
	public function addNewReturn()
	{
		$sql="insert into `returns` (`user_id`, `date`) values (:user_id, now())";
			DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `returns` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}
		
	public function getReturnData($return_id)
	{
		$managerShop = Model::factory('Shop')->getManagerShop();
		$sql = "select `p`.`name` as `product_name`,
		`p`.`code` as `product_code`,
		`r`.`status_id` as `return_status`,
		`rg`.*,
		ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
		from `returns` `r`
		inner join `returns_goods` `rg`
			on `r`.`id` = `rg`.`return_id`
		inner join `products` `p`
			on `rg`.`product_id` = `p`.`id`
		where `rg`.`return_id` = :return_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':return_id', $return_id)
			->param(':manager_shop', $managerShop)
			->execute()
			->as_array();
		return $res;
	}
		
		
	public function getReturnStatus($return_id)
	{
		$sql = "select `r`.`status_id` from `returns` `r` where `r`.`id` = :return_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':return_id', $return_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}
	
		
	public function addReturnPosition($params)
	{
		if(!empty($params['productId'])) {
			$managerShop = Model::factory('Shop')->getManagerShop();
			if($params['row'] != 0) {
				$sql="update `returns_goods` set `product_id` = :product_id, `shop_id` = :shop_id, `comment` = :comment, `num` = :num, `price` = :price where `id` = :id";
				DB::query(Database::UPDATE,$sql)
					->param(':id', Arr::get($params, 'row', 0))
					->param(':product_id', Arr::get($params, 'productId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':num', Arr::get($params, 'num', 0))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			} else {
				$sql="insert into `returns_goods` (`return_id`, `product_id`, `shop_id`, `comment`, `price`, `num`) values (:return_id, :product_id, :shop_id, :comment, :price, :num)";
				DB::query(Database::INSERT,$sql)
					->param(':return_id', Arr::get($params, 'returnId', 0))
					->param(':product_id', Arr::get($params, 'productId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':num', Arr::get($params, 'num', 0))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			}
		} else {
			return false;
		}
	}
	
				
	public function removeReturnPosition($params)
	{
		if(!empty($params['removeReturnPosition'])) {
			$sql="delete from `returns_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
			->param(':id', Arr::get($params, 'removeReturnPosition', 0))
			->execute();
			return true;
		} else {
			return false;
		}
	}
		
						
	public function carryOutReturn($params)
	{
		if(!empty($params['carryOutReturn'])) {
			$returnData = $this->getreturnData($params['carryOutReturn']);
			foreach($returnData as $data){
				$sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
				values (:product_id, :shop_id,
				 (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
				:num, 'return', :document_id, now())";
				$insert = DB::query(Database::INSERT,$sql)
					->param(':document_id', Arr::get($params, 'carryOutReturn', 0))
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', $data['num'])
					->execute();
				$historyId = $insert[0];
				$sql = "select `id` from `products_num` where `product_id` = :product_id and `shop_id` = :shop_id";
				$checkCount = DB::query(Database::SELECT,$sql)
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->execute()
					->count();
				if($checkCount == 0){
					$sql="insert into `products_num` (`product_id`, `shop_id`, `num`, `date`) values (:product_id, :shop_id, :num, now())";
					DB::query(Database::INSERT,$sql)
						->param(':shop_id', $data['shop_id'])
						->param(':product_id', $data['product_id'])
						->param(':num', $data['num'])
						->execute();
				} else {
					$sql="update `products_num` set `num` = (`num` + :num) where `product_id` = :product_id and `shop_id` = :shop_id";
					DB::query(Database::UPDATE,$sql)
						->param(':shop_id', $data['shop_id'])
						->param(':product_id', $data['product_id'])
						->param(':num', $data['num'])
						->execute();
				}
				$sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
				DB::query(Database::UPDATE,$sql)
					->param(':product_id', $data['product_id'])
					->param(':history_id', $historyId)
                    ->param(':shop_id', $data['shop_id'])
					->execute();
			}
			$sql="update `returns` set `status_id` = 2 where `id` = :id";
				DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutReturn'])
				->execute();
			$sql="insert into `returns_status_history` (`return_id`, `status_id`, `user_id`, `date`) values (:return_id, 2, :user_id, now())";
				DB::query(Database::INSERT,$sql)
				->param(':return_id', Arr::get($params, 'carryOutReturn', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}
	
		
	public function getReturnsList($params)
	{
		$limit = $this->getLimit();
		$sqlDate =  Arr::get($params, 'archive', '') != 'return' ? "and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlCountDate =  Arr::get($params, 'archive', '') != 'return' ? "and `r1`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlLimit = "limit ".((Arr::get($params, 'returnPage', 1) - 1)*$limit).", $limit";
		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `returns` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where 1 $sqlCountDate) as `returns_count`
			from `returns` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where 1
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `returns` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where `r1`.`user_id` = :user_id $sqlCountDate) as `returns_count`
			from `returns` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		return $res;
	}
			
	public function createReturnFromRealization($params)
	{
		if(!empty($params['createReturn'])) {
			$newReturnId = $this->addNewReturn();
			$realizationData = $this->getRealizationData($params['createReturn']);
			foreach($realizationData as $data){
				$sql="insert into `returns_goods` (`return_id`, `product_id`, `shop_id`, `price`, `num`) values (:return_id, :product_id, :shop_id, :price, :num)";
				DB::query(Database::INSERT,$sql)
				->param(':return_id', $newReturnId)
				->param(':product_id', $data['product_id'])
				->param(':shop_id', $data['shop_id'])
				->param(':num', $data['num'])
				->param(':price', $data['price'])
				->execute();
			}
			return $newReturnId;
		} else {
			return false;
		}
	}

	public function addNewWriteoff()
	{
		$sql="insert into `writeoffs` (`user_id`, `date`) values (:user_id, now())";
			DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `writeoffs` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}

	public function getWriteoffData($writeoff_id)
	{
		$managerShop = Model::factory('Shop')->getManagerShop();
		$sql = "select `p`.`name` as `product_name`,
		`p`.`code` as `product_code`,
		`r`.`status_id` as `writeoff_status`,
		`rg`.*,
		ifnull((select `pn`.`num` from `products_num` `pn` where `pn`.`product_id` = `p`.`id` and `pn`.`shop_id` = :manager_shop limit 0,1),0) as `root_num`
		from `writeoffs` `r`
		inner join `writeoffs_goods` `rg`
			on `r`.`id` = `rg`.`writeoff_id`
		inner join `products` `p`
			on `rg`.`product_id` = `p`.`id`
		where `rg`.`writeoff_id` = :writeoff_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':writeoff_id', $writeoff_id)
			->param(':manager_shop', $managerShop)
			->execute()
			->as_array();
		return $res;
	}


	public function getWriteoffStatus($writeoff_id)
	{
		$sql = "select `r`.`status_id` from `writeoffs` `r` where `r`.`id` = :writeoff_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':writeoff_id', $writeoff_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}


	public function addWriteoffPosition($params)
	{
		if(!empty($params['productId'])) {
			$managerShop = Model::factory('Shop')->getManagerShop();
			if($params['row'] != 0) {
				$sql="update `writeoffs_goods` set `product_id` = :product_id, `shop_id` = :shop_id, `comment` = :comment, `num` = :num, `price` = :price where `id` = :id";
				DB::query(Database::UPDATE,$sql)
					->param(':id', Arr::get($params, 'row', 0))
					->param(':product_id', Arr::get($params, 'productId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':num', Arr::get($params, 'num', 0))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			} else {
				$sql="insert into `writeoffs_goods` (`writeoff_id`, `product_id`, `shop_id`, `comment`, `price`, `num`) values (:writeoff_id, :product_id, :shop_id, :comment, :price, :num)";
				DB::query(Database::INSERT,$sql)
					->param(':writeoff_id', Arr::get($params, 'writeoffId', 0))
					->param(':product_id', Arr::get($params, 'productId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':num', Arr::get($params, 'num', 0))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			}
		} else {
			return false;
		}
	}


	public function removeWriteoffPosition($params)
	{
		if(!empty($params['removeWriteoffPosition'])) {
			$sql="delete from `writeoffs_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
			->param(':id', Arr::get($params, 'removeWriteoffPosition', 0))
			->execute();
			return true;
		} else {
			return false;
		}
	}


	public function carryOutWriteoff($params)
	{
		if(!empty($params['carryOutWriteoff'])) {
			$writeoffData = $this->getwriteoffData($params['carryOutWriteoff']);
			foreach($writeoffData as $data){
				$sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
				values (:product_id, :shop_id,
				 (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
				:num, 'writeoff', :document_id, now())";
				$insert = DB::query(Database::INSERT,$sql)
					->param(':document_id', Arr::get($params, 'carryOutWriteoff', 0))
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', (-1 * $data['num']))
					->execute();
				$historyId = $insert[0];
				$sql = "select `id` from `products_num` where `product_id` = :product_id and `shop_id` = :shop_id";
				$checkCount = DB::query(Database::SELECT,$sql)
					->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->execute()
					->count();
				if($checkCount == 0){
					$sql="insert into `products_num` (`product_id`, `shop_id`, `num`, `date`) values (:product_id, :shop_id, :num, now())";
					DB::query(Database::INSERT,$sql)
						->param(':shop_id', $data['shop_id'])
						->param(':product_id', $data['product_id'])
						->param(':num', $data['num'])
						->execute();
				} else {
					$sql="update `products_num` set `num` = (`num` - :num) where `product_id` = :product_id and `shop_id` = :shop_id";
					DB::query(Database::UPDATE,$sql)
						->param(':shop_id', $data['shop_id'])
					->param(':product_id', $data['product_id'])
					->param(':num', $data['num'])
					->execute();
				}
				$sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
				DB::query(Database::UPDATE,$sql)
					->param(':product_id', $data['product_id'])
					->param(':history_id', $historyId)
                    ->param(':shop_id', $data['shop_id'])
					->execute();
			}
			$sql="update `writeoffs` set `status_id` = 2 where `id` = :id";
				DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutWriteoff'])
				->execute();
			$sql="insert into `writeoffs_status_history` (`writeoff_id`, `status_id`, `user_id`, `date`) values (:writeoff_id, 2, :user_id, now())";
				DB::query(Database::INSERT,$sql)
				->param(':writeoff_id', Arr::get($params, 'carryOutWriteoff', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}

	public function carryOutWriteoffPost($params)
	{
		if(!empty($params['carryOutWriteoffPost'])) {
			$shopId = Model::factory('Shop')->getManagerShop();
            $userId = null;

			foreach (Arr::get($params, 'id', []) as $key => $data) {
                if (empty($data)) {
                    continue;
                }

                $userId = empty(Arr::get($params, 'writeoffContractor')) ? null : Arr::get($params, 'writeoffContractor');

                $count = DB::query(Database::SELECT, "
                    SELECT `id` FROM `writeoffs_goods` WHERE `writeoff_id` = :writeoff_id
                    AND `product_id` = :product_id
                    AND `shop_id` = :manager_shop
                ")
                    ->param(':writeoff_id', Arr::get($params, 'carryOutWriteoffPost', 0))
                    ->param(':manager_shop', $shopId)
                    ->param(':product_id', $params['id'][$key])
                    ->execute()
                    ->count();

                if ($count == 0) {
                    $sql="insert into `writeoffs_goods` (`writeoff_id`, `product_id`, `shop_id`, `comment`, `price`, `num`) values (:writeoff_id, :product_id, :shop_id, :comment, :price, :num)";
                    DB::query(Database::INSERT,$sql)
                        ->param(':writeoff_id', Arr::get($params, 'carryOutWriteoffPost', 0))
                        ->param(':product_id', $params['id'][$key])
                        ->param(':shop_id', $shopId)
                        ->param(':comment', preg_replace('/[\"\']+/','',$params['comment'][$key]))
                        ->param(':num', $params['num'][$key])
                        ->param(':price', 0)
                        ->execute();

                    $sql="insert into `products_num_history` (`product_id`, `shop_id`, `root_num`, `num`, `document`, `document_id`, `date`)
                    values (:product_id, :shop_id,
                     (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1),
                    :num, 'writeoff', :document_id, now())";
                    $insert = DB::query(Database::INSERT,$sql)
                        ->param(':document_id', Arr::get($params, 'carryOutWriteoffPost', 0))
                        ->param(':shop_id', $shopId)
                        ->param(':product_id', $params['id'][$key])
                        ->param(':num', (-1 * $params['num'][$key]))
                        ->execute();

                    $historyId = $insert[0];

                    $sql = "select `id` from `products_num` where `product_id` = :product_id and `shop_id` = :shop_id";
                    $checkCount = DB::query(Database::SELECT,$sql)
                        ->param(':shop_id', $shopId)
                        ->param(':product_id', $params['id'][$key])
                        ->execute()
                        ->count();

                    if($checkCount == 0){
                        $sql="insert into `products_num` (`product_id`, `shop_id`, `num`, `date`) values (:product_id, :shop_id, :num, now())";
                        DB::query(Database::INSERT,$sql)
                            ->param(':shop_id', $shopId)
                            ->param(':product_id', $params['id'][$key])
                            ->param(':num', $params['num'][$key])
                            ->execute();
                    } else {
                        $sql="update `products_num` set `num` = (`num` - :num) where `product_id` = :product_id and `shop_id` = :shop_id";
                        DB::query(Database::UPDATE,$sql)
                            ->param(':shop_id', $shopId)
                        ->param(':product_id', $params['id'][$key])
                        ->param(':num', $params['num'][$key])
                        ->execute();
                    }

                    $sql="update `products_num_history` set `new_num` = (select `p`.`num` from `products_num` `p` where `p`.`product_id` = :product_id and `p`.`shop_id` = :shop_id limit 0,1) where `id` = :history_id";
                    DB::query(Database::UPDATE,$sql)
                        ->param(':product_id', $params['id'][$key])
                        ->param(':history_id', $historyId)
                        ->param(':shop_id', $shopId)
                        ->execute();
                }
			}
			$sql="update `writeoffs` set `contractor_id` = :user_id, `status_id` = 2 where `id` = :id";
				DB::query(Database::UPDATE,$sql)
				    ->param(':id', $params['carryOutWriteoffPost'])
                    ->param(':user_id', $userId)
				    ->execute();

			$sql="insert into `writeoffs_status_history` (`writeoff_id`, `status_id`, `user_id`, `date`) values (:writeoff_id, 2, :user_id, now())";
				DB::query(Database::INSERT,$sql)
				->param(':writeoff_id', Arr::get($params, 'carryOutWriteoffPost', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function getWriteoffsList($params)
	{
		$now = $this->getNow();

		$firstDate = empty(Arr::get($params, 'writeoffs_first_date')) ? $now : $params['writeoffs_first_date'];
		$lastDate = empty(Arr::get($params, 'writeoffs_last_date')) ? $now : $params['writeoffs_last_date'];

		$limit = $this->getLimit();

		$sqlLimit = "limit ".((Arr::get($params, 'writeoffPage', 1) - 1)*$limit).", $limit";

		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(
				select count(`r1`.`id`)
				from `writeoffs` `r1`
				inner join `documents_status` `rs1`
				on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
				on `r1`.`user_id` = `u1`.`id`
				where `r1`.`date` between :firstDate and :lastDate
			) as `writeoffs_count`
			from `writeoffs` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`date` between :firstDate and :lastDate
			order by `r`.`date` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(
				select count(`r1`.`id`)
				from `writeoffs` `r1`
				inner join `documents_status` `rs1`
				on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
				on `r1`.`user_id` = `u1`.`id`
				where `r1`.`user_id` = :user_id
				and `r1`.`date` between :firstDate and :lastDate
			) as `writeoffs_count`
			from `writeoffs` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			and `r`.`date` between :firstDate and :lastDate
			order by `r`.`date` desc
			$sqlLimit";

		return DB::query(Database::SELECT,$sql)
				->parameters([
					':user_id' => $this->user_id,
					':firstDate' => Date::convertDateFromFormat($firstDate, 'Y-m-d 00:00:00'),
					':lastDate' => Date::convertDateFromFormat($lastDate, 'Y-m-d 23:59:59'),
				])
				->execute()
				->as_array()
				;
	}

	public function createWriteoffFromRealization($params)
	{
		if(!empty($params['createWriteoff'])) {
			$newWriteoffId = $this->addNewWriteoff();
			$realizationData = $this->getRealizationData($params['createWriteoff']);
			foreach($realizationData as $data){
				$sql="insert into `writeoffs_goods` (`writeoff_id`, `product_id`, `shop_id`, `price`, `num`) values (:writeoff_id, :product_id, `shop_id`, :price, :num)";
				DB::query(Database::INSERT,$sql)
					->param(':writeoff_id', $newWriteoffId)
					->param(':product_id', $data['product_id'])
					->param(':shop_id', $data['shop_id'])
					->param(':num', $data['num'])
					->param(':price', $data['price'])
					->execute();
			}
			return $newWriteoffId;
		} else {
			return false;
		}
	}

	public function addNewCashincome()
	{
		$sql="insert into `cashincomes` (`user_id`, `date`) values (:user_id, now())";
		DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `cashincomes` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}

	public function getCashincomeData($cashincome_id)
	{
		$sql = "select `r`.`status_id` as `cashincome_status`,
		`rg`.*
		from `cashincomes` `r`
		inner join `cashincomes_goods` `rg`
			on `r`.`id` = `rg`.`cashincome_id`
		where `rg`.`cashincome_id` = :cashincome_id
		order by `r`.`date` desc";
		$res = DB::query(Database::SELECT,$sql)
			->param(':cashincome_id', $cashincome_id)
			->execute()
			->as_array();
		return $res;
	}


	public function getCashincomeStatus($cashincome_id)
	{
		$sql = "select `r`.`status_id` from `cashincomes` `r` where `r`.`id` = :cashincome_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':cashincome_id', $cashincome_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}


	public function addCashincomePosition($params)
	{
		if(!empty($params)) {
			$managerShop = Model::factory('Shop')->getManagerShop();
			if($params['row'] != 0) {
				$sql="update `cashincomes_goods` set `comment` = :comment, `shop_id` = :shop_id, `price` = :price where `id` = :id";
				DB::query(Database::UPDATE,$sql)
					->param(':id', Arr::get($params, 'row', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			} else {
				$sql="insert into `cashincomes_goods` (`cashincome_id`, `shop_id`, `comment`, `price`) values (:cashincome_id, :shop_id, :comment, :price)";
				DB::query(Database::INSERT,$sql)
					->param(':cashincome_id', Arr::get($params, 'cashincomeId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			}
		} else {
			return false;
		}
	}


	public function removeCashincomePosition($params)
	{
		if(!empty($params['removeCashincomePosition'])) {
			$sql="delete from `cashincomes_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
				->param(':id', Arr::get($params, 'removeCashincomePosition', 0))
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function carryOutCashincome($params)
	{
		if(!empty($params['carryOutCashincome'])) {
			$sql="update `cashincomes` set `status_id` = 2 where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutCashincome'])
				->execute();
			$sql="insert into `cashincomes_status_history` (`cashincome_id`, `status_id`, `user_id`, `date`) values (:cashincome_id, 2, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':cashincome_id', Arr::get($params, 'carryOutCashincome', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}

	public function carryOutCashincomePost($params)
	{
		if(!empty($params['carryOutCashincomePost'])) {
			$shopId = Model::factory('Shop')->getManagerShop();
			foreach (Arr::get($params, 'comment', []) as $key => $comment) {
				$sql = "insert into `cashincomes_goods` (`cashincome_id`, `shop_id`, `comment`, `price`) values (:cashincome_id, :shop_id, :comment, :price)";
				DB::query(Database::INSERT, $sql)
					->param(':cashincome_id', Arr::get($params, 'carryOutCashincomePost'))
					->param(':shop_id', $shopId)
					->param(':comment', preg_replace('/[\"\']+/', '',$comment))
					->param(':price', $params['price'][$key])
					->execute();
			}
			$sql="update `cashincomes` set `status_id` = 2 where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutCashincomePost'])
				->execute();
			$sql="insert into `cashincomes_status_history` (`cashincome_id`, `status_id`, `user_id`, `date`) values (:cashincome_id, 2, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':cashincome_id', Arr::get($params, 'carryOutCashincomePost'))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}

	public function getCashincomesList($params)
	{
		$limit = $this->getLimit();
		$sqlDate =  Arr::get($params, 'archive', '') != 'cashincome' ? "and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlCountDate =  Arr::get($params, 'archive', '') != 'cashincome' ? "and `r1`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlLimit = "limit ".((Arr::get($params, 'cashincomePage', 1) - 1)*$limit).", $limit";
		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `cashincomes` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where 1 $sqlCountDate) as `cashincomes_count`
			from `cashincomes` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where 1
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `cashincomes` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where `r1`.`user_id` = :user_id $sqlCountDate) as `cashincomes_count`
			from `cashincomes` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		return $res;
	}

	public function addNewCashwriteoff()
	{
		$sql="insert into `cashwriteoffs` (`user_id`, `date`) values (:user_id, now())";
		DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `cashwriteoffs` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}

	public function getCashwriteoffData($cashwriteoff_id)
	{
		$sql = "select `r`.`status_id` as `cashwriteoff_status`,
		`rg`.*
		from `cashwriteoffs` `r`
		inner join `cashwriteoffs_goods` `rg`
			on `r`.`id` = `rg`.`cashwriteoff_id`
		where `rg`.`cashwriteoff_id` = :cashwriteoff_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':cashwriteoff_id', $cashwriteoff_id)
			->execute()
			->as_array();
		return $res;
	}


	public function getCashwriteoffStatus($cashwriteoff_id)
	{
		$sql = "select `r`.`status_id` from `cashwriteoffs` `r` where `r`.`id` = :cashwriteoff_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':cashwriteoff_id', $cashwriteoff_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}


	public function addCashwriteoffPosition($params)
	{
		if(!empty($params)) {
			$managerShop = Model::factory('Shop')->getManagerShop();
			if($params['row'] != 0) {
				$sql="update `cashwriteoffs_goods` set `comment` = :comment, `shop_id` = :shop_id, `price` = :price where `id` = :id";
				DB::query(Database::UPDATE,$sql)
					->param(':id', Arr::get($params, 'row', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			} else {
				$sql="insert into `cashwriteoffs_goods` (`cashwriteoff_id`, `shop_id`, `comment`, `price`) values (:cashwriteoff_id, :shop_id, :comment, :price)";
				DB::query(Database::INSERT,$sql)
					->param(':cashwriteoff_id', Arr::get($params, 'cashwriteoffId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			}
		} else {
			return false;
		}
	}


	public function removeCashwriteoffPosition($params)
	{
		if(!empty($params['removeCashwriteoffPosition'])) {
			$sql="delete from `cashwriteoffs_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
				->param(':id', Arr::get($params, 'removeCashwriteoffPosition', 0))
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function carryOutCashwriteoff($params)
	{
		if(!empty($params['carryOutCashwriteoff'])) {
			$sql="update `cashwriteoffs` set `status_id` = 2 where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutCashwriteoff'])
				->execute();
			$sql="insert into `cashwriteoffs_status_history` (`cashwriteoff_id`, `status_id`, `user_id`, `date`) values (:cashwriteoff_id, 2, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':cashwriteoff_id', Arr::get($params, 'carryOutCashwriteoff', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function carryOutCashwriteoffPost($params)
	{
		if(!empty($params['carryOutCashwriteoffPost'])) {
			$shopId = Model::factory('Shop')->getManagerShop();
			foreach (Arr::get($params, 'comment', []) as $key => $comment) {
				$sql = "insert into `cashwriteoffs_goods` (`cashwriteoff_id`, `shop_id`, `comment`, `price`) values (:cashwriteoff_id, :shop_id, :comment, :price)";
				DB::query(Database::INSERT, $sql)
					->param(':cashwriteoff_id', Arr::get($params, 'carryOutCashwriteoffPost'))
					->param(':shop_id', $shopId)
					->param(':comment', preg_replace('/[\"\']+/', '',$comment))
					->param(':price', $params['price'][$key])
					->execute();
			}
			$sql="update `cashwriteoffs` set `status_id` = 2 where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutCashwriteoffPost'])
				->execute();
			$sql="insert into `cashwriteoffs_status_history` (`cashwriteoff_id`, `status_id`, `user_id`, `date`) values (:cashwriteoff_id, 2, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':cashwriteoff_id', Arr::get($params, 'carryOutCashwriteoffPost'))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}

	public function getCashwriteoffsList($params)
	{
		$limit = $this->getLimit();
		$sqlDate =  Arr::get($params, 'archive', '') != 'cashwriteoff' ? "and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlCountDate =  Arr::get($params, 'archive', '') != 'cashwriteoff' ? "and `r1`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlLimit = "limit ".((Arr::get($params, 'cashwriteoffPage', 1) - 1)*$limit).", $limit";
		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `cashwriteoffs` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where 1 $sqlCountDate) as `cashwriteoffs_count`
			from `cashwriteoffs` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where 1
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `cashwriteoffs` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where `r1`.`user_id` = :user_id $sqlCountDate) as `cashwriteoffs_count`
			from `cashwriteoffs` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		return $res;
	}

	public function addNewCashreturn()
	{
		$sql="insert into `cashreturns` (`user_id`, `date`) values (:user_id, now())";
		DB::query(Database::INSERT,$sql)
			->param(':user_id', $this->user_id)
			->execute();
		$res = DB::query(Database::SELECT,"select last_insert_id() as `id` from `cashreturns` limit 0,1")
			->execute()
			->as_array();
		return $res[0]['id'];
	}

	public function getCashreturnData($cashreturn_id)
	{
		$sql = "select `r`.`status_id` as `cashreturn_status`,
		`rg`.*
		from `cashreturns` `r`
		inner join `cashreturns_goods` `rg`
			on `r`.`id` = `rg`.`cashreturn_id`
		where `rg`.`cashreturn_id` = :cashreturn_id";
		$res = DB::query(Database::SELECT,$sql)
			->param(':cashreturn_id', $cashreturn_id)
			->execute()
			->as_array();
		return $res;
	}


	public function getCashreturnStatus($cashreturn_id)
	{
		$sql = "select `r`.`status_id` from `cashreturns` `r` where `r`.`id` = :cashreturn_id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':cashreturn_id', $cashreturn_id)
			->execute()
			->as_array();
		return count($res) > 0 ? $res[0]['status_id'] : 0;
	}


	public function addCashreturnPosition($params)
	{
		if(!empty($params)) {
			$managerShop = Model::factory('Shop')->getManagerShop();
			if($params['row'] != 0) {
				$sql="update `cashreturns_goods` set `comment` = :comment, `shop_id` = :shop_id, `price` = :price where `id` = :id";
				DB::query(Database::UPDATE,$sql)
					->param(':id', Arr::get($params, 'row', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			} else {
				$sql="insert into `cashreturns_goods` (`cashreturn_id`, `shop_id`, `comment`, `price`) values (:cashreturn_id, :shop_id, :comment, :price)";
				DB::query(Database::INSERT,$sql)
					->param(':cashreturn_id', Arr::get($params, 'cashreturnId', 0))
					->param(':shop_id', $managerShop)
					->param(':comment', preg_replace('/[\"\']+/','',Arr::get($params, 'comment', 0)))
					->param(':price', Arr::get($params, 'price', 0))
					->execute();
				return true;
			}
		} else {
			return false;
		}
	}


	public function removeCashreturnPosition($params)
	{
		if(!empty($params['removeCashreturnPosition'])) {
			$sql="delete from `cashreturns_goods` where `id` = :id";
			DB::query(Database::DELETE,$sql)
				->param(':id', Arr::get($params, 'removeCashreturnPosition', 0))
				->execute();
			return true;
		} else {
			return false;
		}
	}


	public function carryOutCashreturn($params)
	{
		if(!empty($params['carryOutCashreturn'])) {
			$sql="update `cashreturns` set `status_id` = 2 where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $params['carryOutCashreturn'])
				->execute();
			$sql="insert into `cashreturns_status_history` (`cashreturn_id`, `status_id`, `user_id`, `date`) values (:cashreturn_id, 2, :user_id, now())";
			DB::query(Database::INSERT,$sql)
				->param(':cashreturn_id', Arr::get($params, 'carryOutCashreturn', 0))
				->param(':user_id', $this->user_id)
				->execute();
			return true;
		} else {
			return false;
		}
	}

	public function getCashreturnsList($params)
	{
		$limit = $this->getLimit();
		$sqlDate =  Arr::get($params, 'archive', '') != 'cashreturn' ? "and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlCountDate =  Arr::get($params, 'archive', '') != 'cashreturn' ? "and `r1`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')" : '';
		$sqlLimit = "limit ".((Arr::get($params, 'cashreturnPage', 1) - 1)*$limit).", $limit";
		if(Auth::instance()->logged_in('admin'))
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `cashreturns` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where 1 $sqlCountDate) as `cashreturns_count`
			from `cashreturns` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where 1
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		else
			$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			`u`.`username` as `manager_name`,
			(select count(`r1`.`id`) from `cashreturns` `r1` inner join `documents_status` `rs1` on `r1`.`status_id` = `rs1`.`id` inner join `users` `u1` on `r1`.`user_id` = `u1`.`id` where `r1`.`user_id` = :user_id $sqlCountDate) as `cashreturns_count`
			from `cashreturns` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `users` `u`
				on `r`.`user_id` = `u`.`id`
			where `r`.`user_id` = :user_id
			$sqlDate
			order by `r`.`date` desc
			$sqlLimit";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		return $res;
	}

	public function getRootCash($params)
	{
		/** @var Model_Shop $shopModel */
		$shopModel = Model::factory('Shop');
		
		$summ = 0;
		$realizationSum = 0;

		$userSql = !Auth::instance()->logged_in('admin') ?  'and `sm`.`user_id` = :user_id' : null;

		$sql = "select `rg`.`price`, `rg`.`num`
			from `realizations_goods` `rg`
			inner join `realizations` `r`
				on `rg`.`realization_id` = `r`.`id`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `shopes_managers` `sm`
				on `sm`.`shop_id` = `rg`.`shop_id`
			where `r`.`status_id` = 2
			$userSql
			and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		foreach($res as $data){
			$summ += $data['price']*$data['num'];
			$realizationSum += $data['price']*$data['num'];
		}
		$sql = "select `rg`.`price`, `rg`.`num`
			from `cashincomes_goods` `rg`
			inner join `cashincomes` `r`
				on `rg`.`cashincome_id` = `r`.`id`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `shopes_managers` `sm`
				on `sm`.`shop_id` = `rg`.`shop_id`
			where `r`.`status_id` = 2
			$userSql
			and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		foreach($res as $data){
			$summ += $data['price'];
		}
		$sql = "select `rg`.`price`, `rg`.`num`
			from `cashwriteoffs_goods` `rg`
			inner join `cashwriteoffs` `r`
				on `rg`.`cashwriteoff_id` = `r`.`id`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `shopes_managers` `sm`
				on `sm`.`shop_id` = `rg`.`shop_id`
			where `r`.`status_id` = 2
			$userSql
			and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		foreach($res as $data){
			$summ -= $data['price'];
		}
		$sql = "select `rg`.`price`, `rg`.`num`
			from `cashreturns_goods` `rg`
			inner join `cashreturns` `r`
				on `rg`.`cashreturn_id` = `r`.`id`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			inner join `shopes_managers` `sm`
				on `sm`.`shop_id` = `rg`.`shop_id`
			where `r`.`status_id` = 2
			$userSql
			and `r`.`date` between date_format(now(), '%Y-%m-%d 00:00:00') and date_format(now(), '%Y-%m-%d 23:59:59')";
		$res = DB::query(Database::SELECT,$sql)
			->param(':user_id', $this->user_id)
			->execute()
			->as_array();
		foreach($res as $data){
			$summ += $data['price'];
		}

		$isset = DB::select('cc.*', [DB::expr('sum(cc.fact_cash)'), 'fact_cash'])
			->from(['cash_close', 'cc'])
			->where('cc.date', '=', DB::expr("date_format(now(), '%Y-%m-%d')"))
		;

		$isset = !Auth::instance()->logged_in('admin')
			? $isset->and_where('cc.shop_id', '=', DB::select('sm.shop_id')->from(['shopes_managers', 'sm'])->where('sm.user_id', '=', $this->user_id)->limit(1))
			: $isset
		;

		$isset = $isset
				->execute()
				->as_array()
			;
		
		$shopId = $shopModel->getManagerShop();
		
		return [$summ, Arr::get($isset, 0, []), $realizationSum, Arr::get($shopModel->getShop(0, $shopId), 0, [])];
	}

	public  function closeCash($params)
	{
		/** @var Model_Shop $shopModel */
		$shopModel = Model::factory('Shop');
		
		$shopId = $shopModel->getManagerShop();

		DB::update('cashincomes')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();
		DB::update('cashreturns')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();
		DB::update('cashwriteoffs')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();
		DB::update('incomes')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();
		DB::update('returns')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();
		DB::update('writeoffs')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();
		DB::update('realizations')->set(['status_id' => 2])->where('user_id', '=', $this->user_id)->execute();

		DB::insert('cash_close', ['doc_cash', 'fact_cash', 'real_cash', 'shop_id', 'date'])
			->values([Arr::get($params, 'docCash', 0), Arr::get($params, 'factCash', 0), Arr::get($params, 'realCash', 0), $shopId, DB::expr('now()')])
			->execute()
		;
	}

	public  function getCashCloseList($params)
	{
		/** @var Model_Shop $shopModel */
		$shopModel = Model::factory('Shop');

		$shopId = $shopModel->getManagerShop();

		$shopSql = !Auth::instance()->logged_in('admin') ? 'where `c`.`shop_id` = :shop_id' : null;

		$limit = $this->getLimit();

		$sql = "select `c`.*,
		(select count(`c`.`id`) from `cash_close` `c` $shopSql) as `cashclose_count`,
		(select `name` from `shopes` where `id` = `c`.`shop_id` limit 0,1) as `shop_name`
		from `cash_close` `c`
		$shopSql
		order by `c`.`date` desc
		limit ".((Arr::get($params, 'cashclosePage', 1) - 1)*$limit).", $limit";
		$res = DB::query(Database::SELECT,$sql)
			->parameters([
				':shop_id' => $shopId
			])
			->execute()
			->as_array();
		return $res;
	}

	public function getOrdersList($params)
	{
		$now = $this->getNow();

		$firstDate = empty(Arr::get($params, 'orders_first_date')) ? $now : $params['orders_first_date'];
		$lastDate = empty(Arr::get($params, 'orders_last_date')) ? $now : $params['orders_last_date'];

		$limit = $this->getLimit();

		$sqlLimit = Arr::get($params, 'archive', '') != 'order' ? '' : "limit ".((Arr::get($params, 'orderPage', 1) - 1)*$limit).", $limit";

		$sql = "select `r`.*,
			`rs`.`name` as `status_name`,
			(
				select count(`r1`.`id`)
				from `orders` `r1`
				inner join `documents_status` `rs1`
				on `r1`.`status_id` = `rs1`.`id`
				inner join `users` `u1`
				on `r1`.`user_id` = `u1`.`id`
				where `r1`.`date` between :firstDate and :lastDate
			) as `orders_count`
			from `orders` `r`
			inner join `documents_status` `rs`
				on `r`.`status_id` = `rs`.`id`
			where `r`.`date` between :firstDate and :lastDate
			or `r`.`status_id` = 3
			order by `r`.`date` desc
			$sqlLimit";

		return
			DB::query(Database::SELECT,$sql)
				->parameters([
					':user_id' => $this->user_id,
					':firstDate' => Date::convertDateFromFormat($firstDate, 'Y-m-d 00:00:00'),
					':lastDate' => Date::convertDateFromFormat($lastDate, 'Y-m-d 23:59:59'),
				])
				->execute()
				->as_array()
			;
	}

	public function checkOrders()
	{
		return
			DB::query(Database::SELECT, 'select `r`.`id` from `orders` `r` where `r`.`status_id` = 3')
				->execute()
				->count()
			;
	}

	public function createRealizationFromOrder($params)
	{
		if(!empty($params['createRealization'])) {
			$newRealizationId = $this->addNewRealization();
			$orderData = Model::factory('Order')->getOrderData($params['orderId']);
			foreach($orderData as $data){
				$sql="insert into `realizations_goods` (`realization_id`, `product_id`, `shop_id`, `price`, `num`) values (:realization_id, :product_id, :shop_id, :price, :num)";
				DB::query(Database::INSERT,$sql)
					->param(':realization_id', $newRealizationId)
					->param(':product_id', $data['product_id'])
					->param(':shop_id', $data['shop_id'])
					->param(':num', $data['num'])
					->param(':price', $data['price'])
					->execute();
			}
			Model::factory('Order')->setOrderStatus(['order_id' => $params['orderId'], 'status_id' => 4]);
			return $newRealizationId;
		} else {
			return false;
		}
	}

	public function setNewParam($params = [])
	{
		DB::query(Database::INSERT, "insert into `products_params` (`product_id`, `name`, `value`) values (:product_id, :name, :value)")
			->param(':product_id', Arr::get($params, 'newProductParam', 0))
			->param(':name', Arr::get($params, 'newParamsName', ''))
			->param(':value', Arr::get($params, 'newParamsValue', ''))
			->execute();
	}

	public function removeProductParam($params = [])
	{
		DB::query(Database::UPDATE, "update `products_params` set `status_id` = 0 where `id` = :id")
			->param(':id', Arr::get($params, 'removeProductParam', 0))
			->execute();
	}


	public function addServicesGroup($post)
	{
		$add_group = Arr::get($post,'addgroup',1);
		if($add_group == 1) {
			$sql="insert into `services_group_1` (`name`) values (:name)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', str_replace('"',"'",Arr::get($post,'group_name','')));
			$query->execute();
			$sql="select last_insert_id() as `new_id` from `services_group_1` limit 0,1";
			$query=DB::query(Database::SELECT,$sql);
			$res = $query->execute()->as_array();
			$new_id = $res[0]['new_id'];
			$sql="update `services_group_1` set `parent_id` = :new_id, `status_id` = 1 where `id` = :new_id";
			$query=DB::query(Database::UPDATE,$sql);
			$query->param(':new_id', $new_id);
			$query->execute();
		} else if($add_group == 2) {
			$sql="insert into `services_group_2` (`parent_id`, `name`) values (:parent_id, :name)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', str_replace('"',"'",Arr::get($post,'group_name','')));
			$query->param(':parent_id', Arr::get($post,'parent_id',''));
			$query->execute();
		} else if($add_group == 3) {
			$sql="insert into `services_group_3` (`parent_id`, `name`) values (:parent_id, :name)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', str_replace('"',"'",Arr::get($post,'group_name','')));
			$query->param(':parent_id', Arr::get($post,'parent_id',''));
			$query->execute();
		}
	}

	public function redactServicesGroup($post)
	{
		$redactGroup = Arr::get($post,'redactGroup',0);
		$id = Arr::get($post,'redactGroupId',0);
		if($redactGroup != 0) {
			$sql="update `services_group_$redactGroup` set `name` = :name where `id` = :id";
			DB::query(Database::UPDATE,$sql)
				->param(':id', $id)
				->param(':name', addslashes(Arr::get($post,'groupName','')))
				->execute();
		}
	}

	public function removeServicesGroup($post)
	{
		$remove_group = Arr::get($post,'removegroup',0);
		$type_id = Arr::get($post,'type_id',1);
		$sql="update `services_group_$type_id` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', $remove_group)
			->execute();
	}

	public function addService($post)
	{
		$add_service = Arr::get($post,'addservice',0);
		if($add_service != 0) {
			$sql="insert into `services` (`name`, `group_1`, `group_2`, `group_3`) values (:name, :group_1, :group_2, :group_3)";
			$query=DB::query(Database::INSERT,$sql);
			$query->param(':name', preg_replace('/[\"\']+/i','',Arr::get($post,'service_name','')));
			$query->param(':group_1', Arr::get($post,'group_1',''));
			$query->param(':group_2', Arr::get($post,'group_2',''));
			$query->param(':group_3', Arr::get($post,'group_3',''));
			$query->execute();
		}
	}

	public function removeService($post)
	{
		$remove_service = Arr::get($post,'removeservice',0);
		$type_id = Arr::get($post,'type_id',1);
		$sql="update `services` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', $remove_service)
			->execute();
	}

	public function searchRedactService($post)
	{

		$sql = "select * from `services` where `code` = :search_id or `name` like :search_text or `name` like :search_replace_text and `status_id` = 1";
		$query=DB::query(Database::SELECT,$sql);
		$query->param(':search_id', Arr::get($post,'redact_search',''));
		$query->param(':search_text', '%'.Arr::get($post,'redact_search','').'%');
		$query->param(':search_replace_text', '%'.preg_replace("/[^0-9a-z]+/i", "", Arr::get($post,'redact_search','')).'%');
		$service=$query->execute()->as_array();
		return $service;
	}

	public function setServiceInfo($post)
	{
		$id = Arr::get($post,'redactservice',0);
		$name = Arr::get($post,'name','');
		$code = Arr::get($post,'code','');
		$short_description = Arr::get($post,'short_description','');
		$description = Arr::get($post,'description','');
		$brand = Arr::get($post,'brand',0);
		$price = Arr::get($post,'price',0);
		$purchase_price = Arr::get($post,'purchase_price',0);
		$sql = "update `services` set `name` = :name, `code` = :code, `short_description` = :short_description, `description` = :description, `price` = :price, `purchase_price` = :purchase_price, `brand_id` = :brand, `status_id` = 1 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', $id)
			->param(':name', $name)
			->param(':code', $code)
			->param(':short_description', $short_description)
			->param(':description', $description)
			->param(':price', $price)
			->param(':purchase_price', $purchase_price)
			->param(':brand', $brand)
			->execute();
	}

	public function loadServiceImg($files, $service_id)
	{
		$sql = "insert into `services_imgs` (`service_id`) values (:id)";
		$query = DB::query(Database::INSERT,$sql);
		$query->param(':id', $service_id);
		$query->execute();
		$sql = "select last_insert_id() as `new_id` from `services_imgs`";
		$query = DB::query(Database::SELECT,$sql);
		$res = $query->execute()->as_array();
		$new_id = $res[0]['new_id'];
		$file_name = 'public/img/original/'.$new_id.'_'.Arr::get($files['imgname'],'name','');
		if (copy($files['imgname']['tmp_name'], $file_name))	{
			$new_image = $this->picture($files['imgname']['tmp_name']);
			$this->imageresizewidth(70);
			$this->imagesave('jpeg', 'public/img/thumb/'.$new_id.'_'.Arr::get($files['imgname'],'name',''));
			$sql = "update `services_imgs` set `src` = :src,`status_id` = 1 where `id` = :id";
			$query=DB::query(Database::UPDATE,$sql);
			$query->param(':id', $new_id);
			$query->param(':src', $new_id.'_'.Arr::get($files['imgname'],'name',''));
			$query->execute();
		}
	}

	public function removeServiceImg($post)
	{
		$img_id = Arr::get($post,'removeimg',0);
		$sql="delete from `services_imgs` where `id` = :id";
		DB::query(Database::DELETE,$sql)
			->param(':id', $img_id)
			->execute();
	}

	public function setNewServicesParam($params = [])
	{
		DB::query(Database::INSERT, "insert into `services_params` (`service_id`, `name`, `value`) values (:service_id, :name, :value)")
			->param(':service_id', Arr::get($params, 'newServiceParam', 0))
			->param(':name', Arr::get($params, 'newParamsName', ''))
			->param(':value', Arr::get($params, 'newParamsValue', ''))
			->execute();
	}

	public function removeServiceParam($params = [])
	{
		DB::query(Database::UPDATE, "update `services_params` set `status_id` = 0 where `id` = :id")
			->param(':id', Arr::get($params, 'removeServiceParam', 0))
			->execute();
	}

	public function getContractorList($params = [])
	{
		if (!empty(Arr::get($params, 'id', 0)))
			$sql = "select `p`.*, `u`.`username` from `profile` `p` inner join `users` `u` on `p`.`user_id` = `u`.`id` where `u`.`id` = :id";
		else
			$sql = "select `p`.*, `u`.`username` from `profile` `p` inner join `users` `u` on `p`.`user_id` = `u`.`id` order by `p`.`name`";
		return DB::query(Database::SELECT,$sql)
			->param(':id', Arr::get($params,'id',0))
			->execute()
			->as_array();
	}

	public function getRealizationContractor($params = [])
	{
		$sql="select `contractor_id` from `realizations` where `id` = :id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':id', Arr::get($params, 'realization', 0))
			->execute()
			->as_array();
		return !empty($res) ? $res[0]['contractor_id'] : 0;
	}

	public function setRealizationContractor($params = [])
	{
		$sql="update `realizations` set `contractor_id` = :user_id where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params, 'realizationId', 0))
			->param(':user_id', empty(Arr::get($params, 'user_id', 0)) ? null : Arr::get($params, 'user_id', 0))
			->execute();
		return true;
	}

	public function getIncomeContractor($params = [])
	{
		$sql="select `contractor_id` from `incomes` where `id` = :id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':id', Arr::get($params, 'income', 0))
			->execute()
			->as_array();
		return !empty($res) ? $res[0]['contractor_id'] : 0;
	}

	public function setIncomeContractor($params = [])
	{
		$sql="update `incomes` set `contractor_id` = :user_id where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params, 'incomeId', 0))
			->param(':user_id', empty(Arr::get($params, 'user_id', 0)) ? null : Arr::get($params, 'user_id', 0))
			->execute();
		return true;
	}

	public function getWriteoffContractor($params = [])
	{
		$sql="select `contractor_id` from `writeoffs` where `id` = :id limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':id', Arr::get($params, 'writeoff', 0))
			->execute()
			->as_array();
		return !empty($res) ? $res[0]['contractor_id'] : 0;
	}

	public function setWriteoffContractor($params = [])
	{
		$sql="update `writeoffs` set `contractor_id` = :user_id where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params, 'writeoffId', 0))
			->param(':user_id', empty(Arr::get($params, 'user_id', 0)) ? null : Arr::get($params, 'user_id', 0))
			->execute();
		return true;
	}

	public function checkPrice($params = [])
	{
		$sql="select `p`.*,
		`r`.`status_id` as `document_status`
		from `realizations` `r`
		inner join `profile` `p`
			on `p`.`user_id` = `r`.`contractor_id`
		where `r`.`id` = :id
		limit 0,1";
		$res = DB::query(Database::SELECT,$sql)
			->param(':id', Arr::get($params, 'realization', 0))
			->execute()
			->as_array();
		if (!empty($res) && $res[0]['document_status'] == 1) {
			$discount = $res[0]['discount'];
			if (!empty($discount)) {
				$k = (100 - $discount) / 100;
			} else {
				$k = 1;
			}
			DB::query(Database::UPDATE,
				"update `realizations_goods` `rg` set `rg`.`price` =
					(select (`p`.`price` * $k) from `products` `p` where `p`.`id` = `rg`.`product_id` limit 0,1)
					 where `rg`.`realization_id` = :id")
				->param(':id', Arr::get($params, 'realization', 0))
				->execute();
		}
	}

	public function getReportsList($params)
	{
		$i = 1;
		$page = Arr::get($params, 'reportPage', 1);

		$now = $this->getNow();

		$firstDate = empty(Arr::get($params, 'reports_first_date')) ? $now : $params['reports_first_date'];
		$lastDate = empty(Arr::get($params, 'reports_last_date')) ? $now : $params['reports_last_date'];
		$shopSql = empty(Arr::get($params, 'shop_id')) ? '' : ' and `s`.`id` = :shop_id';

		$dates[0] = 0;
		$res = DB::query(Database::SELECT,"
			select date_format(now(), '%Y-%m-%d') as `now`,
			date_format(`r1`.`date`, '%Y-%m-%d') as `date`
			from `products_num_history` `r1`
			where `r1`.`date` between :firstDate and :lastDate
			group by date_format(`r1`.`date`, '%Y-%m-%d')
			order by `r1`.`date` desc")
				->parameters([
					':firstDate' => Date::convertDateFromFormat($firstDate, 'Y-m-d 00:00:00'),
					':lastDate' => Date::convertDateFromFormat($lastDate, 'Y-m-d 23:59:59'),
				])
				->execute()
				->as_array()
			;

		foreach ($res as $row) {
			$dates[$i] = ceil((strtotime($row['now']) - strtotime($row['date']))/ 86400);
			$i++;
		}

		$sql = "select `r`.*,
            if (
                `r`.`document` = 'realization', (
                    ifnull(
                        (
                            select `pr`.`name` from `realizations` `real`
                            inner join `profile` `pr`
                                on `pr`.`user_id` = `real`.`contractor_id`
                            where `real`.`id` = `r`.`document_id`
                            limit 0,1
                        )
                    , 'Розничный покупатель'
                    )
                ), if (
                    `r`.`document` = 'income', (
                        ifnull(
                            (
                                select `pr`.`name` from `incomes` `inc`
                                inner join `profile` `pr`
                                    on `pr`.`user_id` = `inc`.`contractor_id`
                                where `inc`.`id` = `r`.`document_id`
                                limit 0,1
                            )
                        , 'Ответственный не указан'
                        )
                    ),  if (
                        `r`.`document` = 'writeoff', (
                            ifnull(
                                (
                                    select `pr`.`name` from `writeoffs` `wr`
                                    inner join `profile` `pr`
                                        on `pr`.`user_id` = `wr`.`contractor_id`
                                    where `wr`.`id` = `r`.`document_id`
                                    limit 0,1
                                )
                            , 'Ответственный не указан'
                            )
                        ), ''
                    )
                )
            ) as `contractor`,
			`p`.`name` as `product_name`
			from `products_num_history` `r`
			inner join `products` `p`
				on `p`.`id` = `r`.`product_id`
			inner join `shopes` `s`
				on `s`.`id` = `r`.`shop_id`
			where `r`.`date` between :firstDate and :lastDate
			$shopSql
			order by `r`.`date` desc, `r`.`document_id` desc";

		$res = DB::query(Database::SELECT,$sql)
				->parameters([
					':firstDate' => Date::convertDateFromFormat($firstDate, 'Y-m-d 00:00:00'),
					':lastDate' => Date::convertDateFromFormat($lastDate, 'Y-m-d 23:59:59'),
					':shop_id' => Arr::get($params, 'shop_id')
				])
				->execute()
				->as_array()
			;

		return [(count($dates) - 1), $res];
	}

    public function removeContractor($params = [])
    {
        DB::query(Database::DELETE, 'delete from `profile` where `user_id` = :id')
            ->param(':id', Arr::get($params, 'removeuser'))
            ->execute();

        DB::query(Database::DELETE, 'delete from `users` where `id` = :id')
            ->param(':id', Arr::get($params, 'removeuser'))
            ->execute();
    }

	public function canceledOrder($params = [])
	{
		DB::query(Database::UPDATE, 'update `orders` set `status_id` = 6 where `id` = :id')
			->param(':id', Arr::get($params, 'orderId'))
			->execute();
	}

	public function collectedOrder($params = [])
	{
		/** @var $orderModel Model_Order */
		$orderModel = Model::factory('Order');

		DB::query(Database::UPDATE, 'update `orders` set `status_id` = 5 where `id` = :id')
			->param(':id', Arr::get($params, 'order_id'))
			->execute();

		$orderDeliveryInfo = $orderModel->getOrderDeliveryInfo($params);

		$orderModel->sendSms([
			'phone' => Arr::get($orderDeliveryInfo, 'phone'),
			'text'  => sprintf('Ваш заказ № %d собран. Тел. для справок +79025051272', Arr::get($params, 'order_id')),
		]);
	}

	public function checkAvailability($params = [])
	{
		/** @var Model_Cart $cartModel */
		$cartModel = Model::factory('Cart');

		/** @var Model_Product $productModel */
		$productModel = Model::factory('Product');

		$cartInfo = $cartModel->getCart();

		$emptyParts = [];

		foreach($cartInfo as $i => $cartData) {
			$numData = $productModel->getProductNum($cartData['product_id'], Arr::get($params, 'selectedShop'));
			if (Arr::get($numData, 'num', 0) == 0) {
				$emptyParts[] = $cartData['product_name'];
			}
		}

		$result = json_encode($emptyParts);

		return $result;
	}

	public function generatePrice($type)
	{
		if ($type == 'farpost') {
			$file = 'public/prices/farpost/farpost.csv';

			$tmp_file = fopen($file, 'w');
			fwrite($tmp_file, '');
			fclose($tmp_file);

			$objPHPExcel = Model::factory('Excel_PHPExcel_IOFactory')->load($file);

			$i = 1;
			$objPHPExcel
				->getActiveSheet()
				->setCellValue('A'.$i, 'Наименование')
				->setCellValue('B'.$i, 'Описание')
				->setCellValue('C'.$i, 'Цена')
				->setCellValue('D'.$i, 'Фото')
			;

			$sql = "select `p`.*,
			(select `src` from `products_imgs` `pi` where `pi`.`product_id` = `p`.`id` and  `pi`.`status_id` = 1 limit 0,1) as `product_img`,
			(select sum(`num`) from `products_num` where `product_id` = `p`.`id`) as `num`
			from `products` `p`
			where  `p`.`status_id` = 1";

			$res = DB::query(Database::SELECT,$sql)
				->execute()
				->as_array()
			;

			foreach ($res as $row) {
				$row['name'] = preg_replace("/[\t\n\r\f\v]+/i", '', $row['name']);
				$row['description'] = preg_replace("/[\t\n\r\f\v]+/i", '', $row['description']);

				if (!empty($row['num']) && !empty($row['price'])) {
					$i++;

					$objPHPExcel->getActiveSheet()
							->setCellValue('A'.$i, $row['name'])
							->setCellValue('B'.$i, $row['description'])
							->setCellValue('C'.$i, $row['price'])
							->setCellValue('D'.$i, sprintf('http://teleantenna25.ru/public/img/original/%s', $row['product_img']))
					;
				}
			}

			Model::factory('Excel_PHPExcel_IOFactory')->createWriter($objPHPExcel, 'CSV')
					->setDelimiter(';')
					->setEnclosure('')
					->setSheetIndex(0)
					->save($file)
			;
		}
	}
}
?>