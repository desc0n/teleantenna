<ul class="typeahead dropdown-menu" style="top: 0px; left: 0px; display: <?=(!empty(Arr::get($post, 'arr', [])) ? 'block' : 'none');?>;">
  <?
  $i = 0;
  foreach (Arr::get($post, 'arr', []) as $data){
	  if($i < 10) {
	  ?>
  <li><a href="#" onclick="searchAction('<?=str_replace("'", '', str_replace('"', '', $data));?>');"><?=$data;?></a></li>
		<?
	  }
	  $i++;
  }
  ?>
</ul>