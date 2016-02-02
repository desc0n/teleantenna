<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Template {
	public $template="ajax";
	public function action_index()	{
		$html_dom=Model::factory('SimpleHtmlDom');
		$html=file_get_contents("http://www.sberbank.ru/primorskykrai/ru/quotes/currencies/");
		$data_usd="";
		if (preg_match('/class="table3_eggs4"/i',$html)) {
			$data_tr=$html_dom->str_get_html($html)->find('table[class=table3_eggs4] tr');
			if (isset($data_tr[1])) {
				$data_td=$html_dom->str_get_html($data_tr[1])->find('td');
				if (isset($data_td[4])) {
					$usd_val=$data_td[4]->innertext;
					/*$usd_val=str_replace('<td style="vertical-align:middle;font-size:16px">',"",$usd_val);
					$usd_val=str_replace("</td>","",$usd_val);
					$usd_val=str_replace(" ","",$usd_val);*/
					$usd_val=str_replace(",",".",$usd_val);
					$usd_val=$usd_val*1;
					
				}
			}
		}
		$this->template->content=$usd_val;
	}

} // End Welcome
