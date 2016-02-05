<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ТелеАНТЕННА. <?=Arr::get($templateData, 'title');?></title>
    <meta name="description" content="
    Продажа спутникового и эфирного оборудования.
    Электроинструменты.
    Услуги установки антенн.
     <?=Arr::get($templateData, 'description');?>">
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.css" rel="stylesheet">
    <link href="/public/css/main.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="icon" href="/public/i/favicon.png" sizes="64x64" type="image/png">
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" id="mobile-collapsed-nav-btn" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="navbar-brand visible-xs mob-navbar" ref="#">
                    <span class="pull-left"><a href="/">ТелеАНТЕННА</a></span>
                    <span class="pull-left">
                    </span>
                    <span class="pull-left">
                    +7 (423) 290 12 72
                    </span>
                </div>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="dropdown hidden-xs">
                        <a href="/"><span class="glyphicon glyphicon-home"></span></a>
                    </li>
                    <li class="dropdown visible-xs">
                        <?foreach(Model::factory('Product')->getProductGroup(1) as $group_1_data){?>
                            <a href="/catalog/?group_1=<?=$group_1_data['id'];?>"><?=$group_1_data['name'];?></a>
                        <?}?>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Каталог <span class="glyphicon glyphicon-chevron-down"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?foreach(Model::factory('Product')->getProductGroup(1) as $group_1_data){?>
                                <li><a href="/catalog/?group_1=<?=$group_1_data['id'];?>"><?=$group_1_data['name'];?></a></li>
                            <?}?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Услуги <span class="glyphicon glyphicon-chevron-down"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?foreach(Model::factory('Service')->getServiceList(Array('group_1' => 1, 'group_2' => 0, 'group_3' => 0))  as $service_data){?>
                                <li><a href="/item/service/<?=$service_data['id'];?>"><?=$service_data['name'];?></a></li>
                            <?}?>
                        </ul>
                    </li>
                    <li class="cart hidden-xs">
                        <a href="/profile/orders/cart" class="cart-link">
                            <span class="glyphicon glyphicon-shopping-cart"></span>
                            <span class="cart-text">товаров: <span class="cart-num">0</span> шт.</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right hidden-xs">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?=(Auth::instance()->logged_in() ? Auth::instance()->get_user()->username : 'Вход');?> <span class="glyphicon glyphicon-chevron-down"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?if(!Auth::instance()->logged_in()){?>
                                <form class="form-inline form-login" role="form" action="/" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Номер телефона" name="username">
                                    </div>
                                    <div class="input-group">
                                        <input type="password" class="form-control" placeholder="Пароль" name="password">
                                    </div>
                                    <div class="input-group link-group">
                                        <button type="submit" class="btn btn-default" name="login">Войти</button>
                                    </div>
                                    <div class="input-group text-center link-group">
                                        <div><a class="link" href="/registration">Регистрация</a></div>
                                        <div><a class="link" href="/passremind">Забыли пароль?</a></div>
                                    </div>
                                </form>
                            <?} else{?>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="/profile/orders/cart"><span class="glyphicon glyphicon-folder-open"></span> Личный кабинет</a></li>
                                <?if(Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('manager')){?>
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
<div class="post-nav visible-xs"><div class="container">
        <div class="col-sm-3 b-name">
            <span class="hidden-xs">ТелеАНТЕННА</span>
            <div class="m-cart">
                <ul class="nav pull-left">
                    <li class=" visible-xs">
                        <a href="/profile/orders/cart" class="cart-link">
                            <span class="glyphicon glyphicon-shopping-cart"></span>
                            <span class="cart-text">товаров: <span class="cart-num">0</span> шт.</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle mobile-login" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?=(Auth::instance()->logged_in() ? Auth::instance()->get_user()->username : 'Вход');?><span class="glyphicon glyphicon-chevron-down"></span></a>
                        <ul class="dropdown-menu login-xs" role="menu">
                            <?if(!Auth::instance()->logged_in()){?>
                                <form class="form-inline form-login" role="form" action="/" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Номер телефона" name="username">
                                    </div>
                                    <div class="input-group">
                                        <input type="password" class="form-control" placeholder="Пароль" name="password">
                                    </div>
                                    <div class="input-group link-group">
                                        <button type="submit" class="btn btn-default" name="login">Войти</button>
                                    </div>
                                    <div><a class="link" href="/registration">Регистрация</a></div>
                                    <div><a class="link" href="/passremind">Забыли пароль?</a></div>
                                </form>
                            <?} else{?>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="/profile/orders/cart"><span class="glyphicon glyphicon-folder-open"></span> Личный кабинет</a></li>
                                <?if(Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('manager')){?>
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
            </div>
        </div>
    </div>
</div>
<div class="header-container">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 hidden-xs logo">
                <a href="/"><div></div></a>
            </div>
            <!--<div class="col-sm-1 locat-block">
            <div class="tel-main dashed">Владивосток</div>
            </div>-->
            <div class="col-sm-5">
                <div class="col-sm-12 search-block">
                    <div class="form-inline" role="form" action="/search" method="post">
                        <div class="col-sm-9 input-group">
                            <input type="text" id="mainSearchName" name="mainSearchName" class="form-control search" placeholder="Поиск" style="border: 1px solid #ddd;" autocomplete="off" autofocus>
                            <div class="col-xs-12" id="typeahead"></div>
                            <span class="input-group-btn"><button type="button" class="btn btn-default search" id="mainSearchBtn" style="border: 1px solid #ddd;"><span class="glyphicon glyphicon-search"></span></button></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 locat-block hidden-xs">
                <div class="tel-main">+7 (423) 290 12 72</div>
            </div>
        </div>
    </div>
</div>
<div class="container mainContainer">
    <?=$content;?>
</div>
<div class="footer">
    <div class="container">
        <div class="text-muted col-xs-6 col-md-2 col-lg-2 col-sm-2">© "ТелеАНТЕННА" <?=date('Y', time());?></div>
        <ul class="text-muted col-xs-6  col-md-4 col-lg-4 col-sm-4 list-unstyled">
            <li class="dropup">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Адреса магазинов</span></a>
                <ul class="dropdown-menu" role="menu">
                    <?foreach(Model::factory('Shop')->getShop() as $shop){?>
                        <li><a href="/item/shop/<?=$shop['id'];?>"><?=$shop['name'];?></a></li>
                    <?}?>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/public/js/bootstrap.js"></script>
<script src="/public/js/bootstrap3-typeahead.min.js"></script>
<script src="/public/js/cart.js?v=1"></script>
<script src="/public/js/main.js"></script>

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

</body>

</html>