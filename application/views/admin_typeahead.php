<ul class="typeahead dropdown-menu" style="top: 0px; left: 0px; display: <?=(!empty(Arr::get($post, 'arr', [])) ? 'block' : 'none');?>;">
  <?
  $i = 0;
  foreach (Arr::get($post, 'arr', []) as $data){
	  if($i < 10) {
	  ?>
  <li>
      <a href="#" onclick="javascript: setSearchModalItem(<?=$data[0];?>);"><?=$data[1];?></a>
      <?/*if (Arr::get($post, 'type', 'name') == 'name') {?>
      <a href="#" onclick="javascript: $('#goodsName_<?=Arr::get($post, 'row', -1);?>').val('<?=addslashes($data[1]);?>');$('#goodsId_<?=Arr::get($post, 'row', -1);?>').val('<?=$data[0];?>');$('#realizationId').length ? addRealisationPosition(<?=Arr::get($post, 'row', -1);?>) : ($('#incomeId').length ? addIncomePosition(<?=Arr::get($post, 'row', -1);?>) : ($('#returnId').length ? addReturnPosition(<?=Arr::get($post, 'row', -1);?>) : ($('#writeoffId').length ? addWriteoffPosition(<?=Arr::get($post, 'row', -1);?>) : false)));"><?=$data[1];?></a>
      <?} else if (Arr::get($post, 'type', 'name') == 'code') {?>
      <a href="#" onclick="javascript: $('#goodsCode_<?=Arr::get($post, 'row', -1);?>').val('<?=addslashes($data[1]);?>');$('#goodsId_<?=Arr::get($post, 'row', -1);?>').val('<?=$data[0];?>');$('#realizationId').length ? addRealisationPosition(<?=Arr::get($post, 'row', -1);?>) : ($('#incomeId').length ? addIncomePosition(<?=Arr::get($post, 'row', -1);?>) : ($('#returnId').length ? addReturnPosition(<?=Arr::get($post, 'row', -1);?>) : ($('#writeoffId').length ? addWriteoffPosition(<?=Arr::get($post, 'row', -1);?>) : false)));">(<?=$data[1];?>) <?=Arr::get($data, 2, '');?></a>
      <?}*/?>
  </li>
		<?
	  }
	  $i++;
  }
  ?>
</ul>