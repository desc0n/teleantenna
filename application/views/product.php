<div class="col-sm-12 main-content item-content">
    <div class="row">
        <div class="col-sm-12">
            <h2><?=$product['name'];?></h2>
        </div>
    </div>
    <div class="row">
        <div class="post-nav ">
            <?=$breadcrumb;?>
        </div>
    </div>
    <div class="row item-detal">
        <div class="col-sm-12">
            <div class="item-nav">
                <span><b>Код товара: <?=$product['code'];?></b></span>
                <a href="#description">Описание</a>
                <a href="#options">Характеристики</a>
            </div>
        </div>
    </div>
    <div class="row item-detal">
        <div class="col-sm-4">
            <div class="img-container">
                <?if(!$product['imgs']){?>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="thumb-1" aria-labelledby="thumb-1-tab">
                            <img src="/public/img/original/nopic.jpg" alt="">
                        </div>
                    </div>
                    <ul class="nav nav-tabs nav-tabs-bot" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#thumb-1" id="thumb-1-tab" role="tab" data-toggle="tab" aria-controls="#thumb-1" aria-expanded="true">
                                <img src="/public/img/thumb/nopic.jpg" alt="">
                            </a>
                        </li>
                    </ul>
                <?} else {?>
                    <div id="myTabContent" class="tab-content">
                        <?
                        $first = 0;
                        $i = 1;
                        foreach($product['imgs'] as $img){
                            if($first == 0){
                                $active = 'active';
                                $in = 'in';
                                $first = 1;
                            } else {
                                $in = '';
                                $active = '';
                            }
                            ?>
                            <div role="tabpanel" class="tab-pane fade <?=$active;?> <?=$in;?>" id="thumb-<?=$i;?>" aria-labelledby="thumb-<?=$i;?>-tab">
                                <div class="img-link-item" data-toggle="tooltip" data-placement="right" data-html="true" data-trigger="click" title="<img class='tooltip-img' src='/public/img/original/<?=$img['src'];?>' style='width:450px;'>">
                                    <img src="/public/img/original/<?=$img['src'];?>" alt="">
                                </div>
                            </div>
                            <?
                            $i++;
                        }
                        ?>
                    </div>
                    <ul class="nav nav-tabs nav-tabs-bot" role="tablist">
                        <?
                        $first = 0;
                        $i = 1;
                        foreach($product['imgs'] as $img){
                            if($first == 0){
                                $active = 'class="active"';
                                $first = 1;
                            } else {
                                $active = '';
                            }
                            ?>
                            <li role="presentation" <?=$active;?>>
                                <a href="#thumb-<?=$i;?>" id="thumb-<?=$i;?>-tab" role="tab" data-toggle="tab" aria-controls="#thumb-<?=$i;?>" aria-expanded="true">
                                    <img src="/public/img/thumb/<?=$img['src'];?>" alt="">
                                </a>
                            </li>
                            <?
                            $i++;
                        }
                        ?>
                    </ul>
                <?}?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-4">
                    <div class="item-cart-add">
                        <div>
                            <?=$product['price'];?> руб.
                        </div>
                        <button type="button" id="addCartButton_<?=$product['id'];?>" class="btn btn-default btn-block cart-add" value="<?=$product['id'];?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
                        <a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=$product['id'];?>" class="btn btn-success btn-block cart-in" value="<?=$product['id'];?>">В корзину <span class="glyphicon glyphicon-log-out"></span></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="item-stock">
                        <b>Наличие:
                            <?if($product['shop_info']){
                                foreach($product['shop_info'] as $shop_data){
                                    $num = (int)$shop_data['num'];
                                    ?>
                                    <div class="product-shop-info">
                                        <?=$shop_data['address'];?>: <strong><?=($num ?: 0);?> шт.</strong>
                                    </div>
                                    <?
                                }
                            }else{?>
                                Под заказ
                            <?}?>
                        </b>
                    </div>
<!--                    <div class="t-rating item-rating">-->
<!--                        <span class="glyphicon glyphicon-star"></span>-->
<!--                        <span class="glyphicon glyphicon-star"></span>-->
<!--                        <span class="glyphicon glyphicon-star"></span>-->
<!--                        <span class="glyphicon glyphicon-star-empty"></span>-->
<!--                        <span class="glyphicon glyphicon-star-empty"></span>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" id="description">
            <h3>Описание</h3>
            <p>
                <?=$product['description'];?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" id="options">
            <h3>Характеристики</h3>
            <dl class="dl-horizontal">
                <?foreach($product['params'] as $paramsData){?>
                    <dt><?=$paramsData['name'];?> </dt>
                    <dd><?=$paramsData['value'];?></dd>
                <?}?>
            </dl>
        </div>
    </div>
</div>