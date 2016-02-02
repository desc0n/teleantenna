<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Input extends Controller {

	public function action_index() {
		$template=View::factory("template");
		$content=View::factory("input");
		$root_page="input";
		$content->post_username="";
		$content->post_password="";
		if ((Auth::instance()->logged_in()) &&(isset($_POST['logout']))) {
			Auth::instance()->logout();
			HTTP::redirect('/');
		}
		if (isset($_POST['username']) && isset($_POST['password'])) {
			$content->post_username=$_POST['username'];
			$content->post_password=$_POST['password'];
		}
		if ((!Auth::instance()->logged_in()) &&(isset($_POST['login']))) {
			$user = ORM::factory('User');
			$status = Auth::instance()->login($_POST['username'], $_POST['password'],true);
			/*$post_arr=http_build_query(array("username"=>$_POST['username'],"password"=>$_POST['password'],"is_login"=>1,"ref"=>"aj_neo"));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://auc.inrusauto.ru/aj_neo"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=win-1251'));
			//curl_setopt($ch, CURLOPT_PROXY, "94.126.173.227:80");
			$result = curl_exec($ch);
			if(!$result){
				$error = curl_error($ch).'('.curl_errno($ch).')';
				$site_result=$error;
			}
			else{
				$site_result = $result;
			}
			curl_close($ch);
			//HTTP::redirect('http://auc.inrusauto.ru/aj_neo');
			$template=View::factory("ajax");
			$template->content=$site_result;*/
		}
		$template->root_page=$root_page;
		$template->content=$content;
		$this->response->body($template);
	}

}