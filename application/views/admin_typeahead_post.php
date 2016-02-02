<ul class="typeahead dropdown-menu" style="top: 0px; left: 0px; display: <?=(!empty(Arr::get($post, 'arr', [])) ? 'block' : 'none');?>;">
  <?
  $i = 0;
  foreach (Arr::get($post, 'arr', []) as $data){
	  if($i < 10) {
	  ?>
  <li>
      <a href="#" onclick="javascript: setSearchModalItem('<?=$data[0];?>');">(<?=$data[1];?>) <?=Arr::get($data, 2, '');?></a>
  </li>
		<?
	  }
	  $i++;
  }
  ?>
</ul>