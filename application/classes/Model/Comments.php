<?php
class Model_Comments extends Kohana_Model {
	public function get_comments_arr() {
		$comments_arr=Array();
		$sql="select * from `comments` order by `id` desc";
		$query=DB::query(Database::SELECT,$sql);
		$res=$query->execute()->as_array();
		foreach ($res as $row) {
			$comments_arr[]=$row;
		}
		return $comments_arr;
	}
	public function set_comment($name,$email,$city,$text) {
		$comments_arr=Array();
		mysql_query ("SET time_zone = '+11:00'");
		$sql="insert into `comments` (`text`,`email`,`name`,`city`,`date`) values (:text,:email,:name,:city,now())";
		$query=DB::query(Database::INSERT,$sql);
		$query->param(':text', addslashes($text));
		$query->param(':email', addslashes($email));
		$query->param(':name', addslashes($name));
		$query->param(':city', addslashes($city));
		$query->execute();
	}
}
?>