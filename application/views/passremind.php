<div class="container">
  <div class="col-sm-3 left-nav">

  </div>

  <div class="col-sm-9 main-content prof-content">
    <div class="col-sm-8">
<?=$error;?>
<h3>Восстановление пароля</h3>
<form action="/passremind" method="post">
  <p>
  <div class="row">
    <div class="text-muted">Номер мобильного телефона*:</div>
    <div class="col-sm-6">
      <input type="text" class="form-control"  name="phone" placeholder="Номер телефона" value="">
    </div>
  </div>
  </p>
  <p>
  <div class="row" style="margin-top:25px;">
    <div class="col-sm-6">
      <button type="submit" class="btn btn-block btn-default" name="remind">Получить новый пароль</button>
    </div>
  </div>
  </p>
</form>
    </div>
    <div class="col-sm-6">
      
    </div>



  </div>

  </div>