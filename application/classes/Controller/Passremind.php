<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Passremind extends Controller {

	public function action_index() {
		$template=View::factory("template");
		$content=View::factory("passremind");
		$content->error = '';
		if(isset($_POST['remind'])) {
			if (strlen(preg_replace("/[^0-9]+/i", "", $_POST['phone'])) == 11) {
				$user_id = Model::factory('Users')->searchUserByPhone($_POST);
				if (!empty($user_id)) {
					$d = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
					$l = 6;
					$pass = '';
					for ($i = 0; $i < $l; $i++) {
						$ind = rand(0, 8);
						$pass .= $d[$ind];
					}
					$params['pass'] = $pass;
					$params['user_id'] = $user_id;
					Model::factory('Users')->setNewPassword($params);
					$phone = str_replace('+', '', Arr::get($_POST, 'phone', 'empty'));
					$phone = mb_substr($phone, 0, 1) == 8 ? '7' . mb_substr($phone, 1) : $phone;
					$params['phone'] = $phone;
					$params['text'] = "Новый пароль: " . $pass;
					Model::factory('Order')->sendSms($params);
					$error = View::factory('success');
					$error->zag = "Пароль изменен!";
					$error->mess = " Новый пароль выслан на Ваш номер телефона.";
					$content->error = $error;
				} else {
					$error = View::factory('error');
					$error->zag = "Пользователь с таким номером телефона не найден!";
					$error->mess = " Проверьте номер телефона.";
					$content->error = $error;
				}
			} else {
				$error = View::factory('error');
				$error->zag = "Некорректный номер телефона!";
				$error->mess = " Укажите правильно номер телефона.";
				$content->error = $error;
			}
		}
		$template->content=$content;
		$this->response->body($template);
	}

}