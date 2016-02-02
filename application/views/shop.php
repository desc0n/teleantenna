<div class="col-sm-12 main-content item-content">
    <div class="row">
      <div class="col-sm-12">
        <h2><?=Arr::get($shopData, 'name', '');?></h2>
      </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <?=(!empty(Arr::get($shopData, 'img', '')) ? '<img  class="img-thumbnail" src="/public/img/shopes/'.Arr::get($shopData, 'id', '').'_'.Arr::get($shopData, 'img', '').'" alt="'.Arr::get($shopData, 'name', '').'">' : '');?>
        </div>
        <div class="col-lg-5">
            <div class="col-sm-12">
                <?=(!empty(Arr::get($shopData, 'address', '')) ? '<p><strong>Адрес: </strong><div>'.Arr::get($shopData, 'address', '').'</div></p>' : '');?>
                <?=(!empty(Arr::get($shopData, 'info', '')) ? '<p><strong>Дополнительная информация: </strong><div>'.Arr::get($shopData, 'info', '').'</div></p>' : '');?>
            </div>
        </div>
    </div>
</div>