<div class="col-lg-3 col-md-3 col-sm-3 left-nav">
    <div class="b-name hidden-xs">ТелеАНТЕННА</div>
	<div class="hidden-xs">
		<?
		$r = 1;

		foreach($categories as $category){?>
			<div class="slide-trigger">
				<a class="catalog-link" href="/catalog?categoryId=<?=$category['id'];?>">
					<div class="panel-heading collapsed">
						<h4 class="panel-title catalog-menu">
							<?=$category['name'];?>
                            <img src="/public/i/right.png" class="icon-right">
						</h4>
					</div>
				</a>
				<?
				$limit = 15;
				$subCategories = $category['subCategories'];
				$subListCount = ceil(count($subCategories) / $limit);
				$rowsCount = ceil(count($subCategories) / $subListCount);

				for($i = 0; $i < count($subCategories); $i++) {
				?>
				<div class="panel-collapse collapse slide-menu" style="margin-left: <?=($i + 1);?>00%; <?=($rowsCount >= $r ? 'top: 70px' : 'bottom: 0');?>;">
					<ul class="list-group">
						<?foreach($subCategories as $key => $subCategory){
							if ($key >= ($i * $limit) && $key < (($i + 1) * $limit)) {?>
						<li class="list-group-item"><a class="catalog-link" href="/catalog?categoryId=<?=$subCategory['id'];?>"><?=$subCategory['name'];?></a></li>
							<?}
						}?>
					</ul>
				</div>
				<?}?>
			</div>
			<?$r++;
		}?>
	</div>
</div>