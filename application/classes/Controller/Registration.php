<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Registration extends Controller {

	public function action_index() {
		$template=View::factory("template");
		$content=View::factory("registration");
		$captcha = Captcha::instance();
		$root_page="registration";
		$template->root_page=$root_page;
		$username=Arr::get($_POST,'username','');
		$email=Arr::get($_POST,'email','');
		$phone=Arr::get($_POST,'username','');
		$name=Arr::get($_POST,'name','');
		$content->username = $username;
		$content->email = $email;
		$content->phone = $phone;
		$content->name = $name;
		$content->error = "";
		$content->captcha = $captcha;
		if(!Auth::instance()->logged_in()) {
			if (isset($_POST['reg'])) {
				if (Arr::get($_POST,'username','')=="") {
					$error = View::factory('error');
					$error->zag = "Не указан логин!";
					$error->mess = " Укажите Ваш логин.";
					$content->error = $error;
				} else if (Arr::get($_POST,'email','')=="") {
					$error = View::factory('error');
					$error->zag = "Не указана почта!";
					$error->mess = " Укажите Вашу почту.";
					$content->error = $error;
				} else if (Arr::get($_POST,'password','')=="") {
					$error = View::factory('error');
					$error->zag = "Не указан пароль!";
					$error->mess = " Укажите Ваш пароль.";
					$content->error = $error;
				} else if (Arr::get($_POST,'password','')!=Arr::get($_POST,'password2','')) {
					$error = View::factory('error');
					$error->zag = "Пароли не совпадают!";
					$error->mess = " Проверьте правильность подтверждения пароля.";
					$content->error = $error;
				} else if (!Captcha::valid($_POST['checkcode'])) {
					$error = View::factory('error');
					$error->zag = "Контрольный текст не совпадает!";
					$error->mess = " Укажите правильно контрольный текст.";
					$content->error = $error;
				} else if (strlen(preg_replace("/[^0-9]+/i", "", $_POST['username'])) != 11) {
					$error = View::factory('error');
					$error->zag = "Некорректный номер телефона!";
					$error->mess = " Укажите правильно номер телефона.";
					$content->error = $error;
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
						$content->error = $error;
					}
					else {
						Auth::instance()->login($_POST['username'], $_POST['password'],true);
						Model::factory("Users")->addNewUser($_POST);
						$to = $_POST['email'];
						$subj_tpl = View::factory('register_subject');
						$body_tpl = View::factory('register_body');
						$subject = $subj_tpl->render();
						$from = 'site@teleantenna25.ru';
						$body_tpl->login = $_POST['username'];
						$body_tpl->password = $_POST['password'];
						$message = $body_tpl->render();
						$bound="0";
						$header="From: Teleantenna25.ru<site@teleantenna25.ru>\r\n";
						$header.="Subject: $subject\n";
						$header.="Mime-Version: 1.0\n";
						$header.="Content-Type: multipart/mixed; boundary=\"$bound\"";
						$body="\n\n--$bound\n";
						$body.="Content-type: text/html; charset=\"utf-8\"\n";
						$body.="Content-Transfer-Encoding: quoted-printable\n\n";
						$body.="$message";
						$result = false;
						if (mail($to, $subject, $body, $header)) {
							$result = true;
						}
						if($result){
							$site_result = $result;
							$content = View::factory('alert_success');
							$content->zag = "Вы успешно зарегистрированы! ";
							$content->mess = "";
						}
					}
				}
			}
		}
		$template->content=$content;
		$this->response->body($template);
	}

}