<div class="col-sm-12 main-content item-content">
<div class="row">
  <div class="col-sm-12">
    <h2><?=Arr::get($product_info,'name','');?></h2>
  </div>
</div>
<div class="row item-detal">
  <div class="col-sm-12">
    <div class="item-nav">
      <span><b>Код товара: <?=Arr::get($product_info,'code','');?></b></span>
      <a href="#description">Описание</a>
      <a href="#options">Характеристики</a>
      <a href="#comments">Отзывы</a>
    </div>
  </div>
</div>
<div class="row item-detal">
    <div class="col-sm-4">
        <div class="img-container">
            <?if(count(Arr::get($product_info,'imgs',[])) == 0){?>
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
            foreach(Arr::get($product_info,'imgs',[]) as $img){
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
                  <div class="img-link-item" data-toggle="tooltip" data-placement="right" data-html="true" title="<img class='tooltip-img' src='/public/img/original/<?=$img['src'];?>' style='width:450px;'>">
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
            foreach(Arr::get($product_info,'imgs',[]) as $img){
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
        <div class="col-sm-12">
          <h2><?=Arr::get($product_info,'name','');?></h2>
        </div>
      </div>
      <div class="row">
          <div class="col-lg-4">
            <div class="item-cart-add">
              <div>
                <?=Arr::get($product_info,'price',0);?> руб.
              </div>
                <button type="button" id="addCartButton_<?=Arr::get($product_info,'id',0);?>" class="btn btn-default btn-block cart-add" value="<?=Arr::get($product_info,'id',0);?>">Купить <span class="glyphicon glyphicon-shopping-cart"></span></button>
                <a href="/profile/orders/cart" target="_self" id="addInCartButton_<?=Arr::get($product_info,'id',0);?>" class="btn btn-success btn-block cart-in" value="<?=Arr::get($product_info,'id',0);?>">В корзину <span class="glyphicon glyphicon-log-out"></span></a>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="item-stock">
                <b>Наличие:
                    <?if(count($shop_info = Model::factory('Product')->getProductNum($product_info['id']))>0){
                        foreach($shop_info as $shop_data){
                            $num = Arr::get($shop_data, 'num', 0);
                            ?>
                            <a class="shop-link" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?=Arr::get($shop_data, 'address', '');?> (<?=Arr::get($shop_data, 'num', '');?> шт.)">
                                <?=Arr::get($shop_data, 'short_name', '');?>
                                (<?=($num < 0 ? 0 : $num);?>)
                            </a>
                        <?
                        }
                    }else{?>
                        0
                    <?}?>
                </b>
            </div>
            <div class="t-rating item-rating">
                <span class="glyphicon glyphicon-star"></span>
                <span class="glyphicon glyphicon-star"></span>
                <span class="glyphicon glyphicon-star"></span>
                <span class="glyphicon glyphicon-star-empty"></span>
                <span class="glyphicon glyphicon-star-empty"></span>
            </div>
          </div>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-12" id="description">
  <h3>Описание</h3>
    <p>
      <?=Arr::get($product_info,'description','');?>
    </p>
  </div>
</div>
<div class="row">
  <div class="col-sm-12" id="options">
  <h3>Характеристики</h3>
    <dl class="dl-horizontal">
        <?foreach($productParams as $paramsData){?>
      <dt><?=$paramsData['name'];?> </dt>
        <dd><?=$paramsData['value'];?></dd>
      <?}?>
    </dl> 
  </div>
</div>
<div class="row">
  <div class="col-sm-12" id="comments">
  <h3>Комментарии</h3>
    <div class="row">
      <div class="col-sm-12">
        <!--<div class="message">
          <div class="box">
            <p>
              Текст комментария
            </p>
          </div>
        <div class="stamp">
          <p class="stamp">
            <span class="tp_user">
              Михаил
            </span>
            <span class="city">
              Уссурийск
            </span>
            <span class="date" title="27.12.2013 12:55">
              27 декабря 2013 г. 12:55
            </span>
          </p>
        </div>-->
      </div>
      </div>
    </div>
</div>
</div>