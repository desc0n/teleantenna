
<div class="container"> 
  <div class="col-sm-3 left-nav">

  </div>

  <div class="col-sm-9 main-content prof-content">
    <div class="col-sm-8">
<?=$error;?>
<h3>Регистрация</h3>
<form action="/registration" method="post">
  <?/*<p>
  <div class="row">
    <div class="text-muted">Номер мобильного телефона:</div>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="phone" placeholder="Телефон" value="<?=$phone;?>">
    </div>
  </div>
  </p>*/?>
  <p>
  <div class="row">
    <div class="text-muted">Имя</div>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="name" placeholder="Имя" value="<?=$name;?>">
    </div>
  </div>
  </p>
  <p>
  <div class="row">
    <div class="text-muted">Номер мобильного телефона*:</div>
    <div class="col-sm-6">
      <input type="text" class="form-control"  name="username" placeholder="Номер телефона" value="<?=$username;?>">
    </div>
  </div>
  </p>
  <p>
  <div class="row">
    <div class="text-muted">Пароль*:</div>
    <div class="col-sm-3">
      <input type="password" class="form-control"  name="password" placeholder="Пароль">
    </div>
    <div class="col-sm-3">
      <input type="password" class="form-control"  name="password2"  placeholder="Еще раз">
    </div>
  </div>
  </p>
  <p>
  <div class="row">
    <div class="text-muted">Эл. почта*:</div>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="email" placeholder="E-mail" value="<?=$email;?>">
    </div>
  </div>
  </p>
  <p>
  <div class="row">
    <div class="text-muted">Введите текст*:</div>
    <div class="col-sm-6 <?if(isset($err_captcha)){?><?=$err_captcha;?><?}?>">
		<input type="text" class="form-control" name="checkcode" style="width:100px;float:left;">
		<span style="float:right;"><?=$captcha;?></span>
		<p class="text-danger"><?if(isset($err_captcha_mess)){?><?=$err_captcha_mess;?><?}?></p>
    </div>
  </div>
  </p>
  <p>
  <div class="row" style="margin-top:25px;">
    <div class="col-sm-6">
      <button type="submit" class="btn btn-block btn-default" name="reg">Зарегистрироваться</button>
    </div>
  </div>
  </p>
</form>
    </div>
    <div class="col-sm-6">
      
    </div>



  </div>

  </div>