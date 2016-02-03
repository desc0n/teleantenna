<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ТелеАНТЕННА</title>
	<!-- Bootstrap -->
	<link href="/public/css/bootstrap.css" rel="stylesheet">
	<link href="/public/css/main.css" rel="stylesheet">
	<link href="/public/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="/public/js/bootstrap.js"></script>
	<script src="/public/js/admin.js?v=9"></script>
	<script src="/public/js/bootstrap3-typeahead.min.js"></script>
	<script src="/public/js/moment-with-locales.js"></script>
	<script src="/public/js/bootstrap-datetimepicker.js"></script>
	<link rel="icon" href="/public/i/favicon.png" sizes="64x64" type="image/png">
</head>
<body>
<nav class="navbar navbar-default hidden-xs" role="navigation">
	<div class="container"><div class="container-fluid">
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right hidden-xs">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?=(Auth::instance()->logged_in() ? Auth::instance()->get_user()->username : 'Вход');?> <span class="glyphicon glyphicon-chevron-down"></span></a>
						<ul class="dropdown-menu" role="menu">
							<?if(!Auth::instance()->logged_in()){?>
								<form class="form-inline form-login" role="form" action="/" method="post">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Логин" name="username">
									</div>
									<div class="input-group">
										<input type="password" class="form-control" placeholder="Пароль" name="password">
									</div>
									<button type="submit" class="btn btn-default" name="login">Войти</button>
									<div class="input-group text-center link-group">
										<a href="/registration">Регистрация</a>
									</div>
								</form>
							<?} else{?>
								<li role="presentation" class="divider"></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" href="/profile/orders/cart"><span class="glyphicon glyphicon-folder-open"></span> Личный кабинет</a></li>
								<?if(Auth::instance()->logged_in('admin')){?>
									<li role="presentation" class="divider"></li>
									<li role="presentation"><a role="menuitem" tabindex="-1" href="/admin"><span class="glyphicon glyphicon-folder-open"></span> Админка</a></li>
								<?}?>
								<li role="presentation" class="divider"></li>
								<form class="form-inline form-login" role="form" action="/" method="post">
									<button type="submit" class="btn btn-default" name="logout"><span class="glyphicon glyphicon-log-out"></span> Выход</button>
								</form>
							<?}?>
						</ul>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</div>
</nav>
<div class="post-nav visible-xs">
	<div class="container">
		<div class="col-sm-3 b-name">
			<span class="hidden-xs">ТелеАНТЕННА</span>
			<div class="m-cart">
				<ul class="nav pull-left"><li class=" visible-xs"></li></ul>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle mobile-login" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?=(Auth::instance()->logged_in() ? Auth::instance()->get_user()->username : 'Вход');?><span class="glyphicon glyphicon-chevron-down"></span></a>
						<ul class="dropdown-menu login-xs" role="menu">
							<?if(!Auth::instance()->logged_in()){?>
								<form class="form-inline form-login" role="form" action="/" method="post">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Логин" name="username">
									</div>
									<div class="input-group">
										<input type="password" class="form-control" placeholder="Пароль" name="password">
									</div>
									<button type="submit" class="btn btn-default" name="login">Войти</button>
									<div class="input-group text-center link-group">
										<a href="/registration">Регистрация</a>
									</div>
								</form>
							<?} else{?>
								<li role="presentation" class="divider"></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" href="/profile/orders/cart"><span class="glyphicon glyphicon-folder-open"></span> Личный кабинет</a></li>
								<li role="presentation" class="divider"></li>
								<?if(Auth::instance()->logged_in('admin')){?>
									<li role="presentation" class="divider"></li>
									<li role="presentation"><a role="menuitem" tabindex="-1" href="/admin"><span class="glyphicon glyphicon-folder-open"></span> Админка</a></li>
								<?}?>
								<form class="form-inline form-login" role="form" action="/" method="post">
									<button type="submit" class="btn btn-default" name="logout"><span class="glyphicon glyphicon-log-out"></span> Выход</button>
								</form>
							<?}?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="header-container">
	<div class="container">
		<div class="row">
		</div>
	</div>
</div>
<div class="post-nav ">
</div>
<div class="container mainContainer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4 col-md-3 sidebar">
				<?=$admin_menu;?>
			</div>
			<div class="col-sm-10 col-md-9 main">
				<?=$admin_content;?>
			</div>
		</div>
	</div>
</div>
<div class="footer">
	<div class="container">
		<p class="text-muted">© "ТелеАНТЕННА" 2014</p>
	</div>
</div>
<script>
	if ($(window).width() <= 800) {
		$('.img-link').tooltip({
			placement:'right'
		});
	} else {
		$('.img-link').tooltip();
	}
	$('.shop-link').tooltip();
</script>
<script type="text/javascript">
	$(function () {
		$('.datetimepicker').datetimepicker({
			locale: 'ru',
			format: 'DD.MM.YYYY'
		});
	});
</script>
</body>
</html>