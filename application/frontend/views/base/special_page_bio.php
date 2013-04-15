<div id="reelwrapper">
	<h3>Josie Speaks From the Heart</h3>
	<?php if (count($this->special_page_with_reel) >= 1) { ?>
	<div id="preview">
		<a class="reel-popup" href="http://www.youtube.com/embed/-yRwwYQ8LZw" rel="shadowbox[];width=425;height=344;">
			<img src="<?php echo $this->special_page_with_reel[0]->middle_img ?>" alt="<?php echo $this->special_page_with_reel[0]->title ?>" large="<?php echo $this->special_page_with_reel[0]->image ?>" />
		</a>
		<div style="width:380px;float:left;margin-left:9px;">
			<h5><?php echo $this->special_page_with_reel[0]->title ?></h5>
			<p><?php echo $this->special_page_with_reel[0]->at_date ?></p>
		</div>
		<div style="width:80px;float:right;">
			<a class="reel-popup" href="http://www.youtube.com/embed/-yRwwYQ8LZw" rel="shadowbox[];width=425;height=344;">[+] play</a>
		</div>
	</div>

    <div id="slider">
        <div class="navigationbar"> 
            <ul class="navigation" <?php echo ($this->reel_count <= 1) ? "style='display:none'" : "" ?> >
			<?php for ($i = 0 ; $i <= $this->reel_count; $i++) { ?>
			<li><a href="#p<?php echo ($s = ($i) + 1) ?>">&nbsp;</a></li>
			<?php } ?>
            </ul>
		</div>
		<div class="scroll">
			<!--
<div class="scrollContainer" <?php echo (count($this->special_page_with_reel) <= 1) ? "style='display:none;'" : "style='width:".($i * 488)."px;'" ?>>
				<?php $j = 0; $k = 1; ?>
				<?php foreach ($this->special_page_with_reel as $reel): ?>
				<?php echo ($j % 4 == 0) ? '<div class="panel" id="'.$k.'">' : "" ?>
					<div>
						<img src="<?php echo $reel->thumb_img ?>" alt="<?php echo $reel->title ?>" onclick="load_image('<?php echo $reel->middle_img ?>', '<?php echo $reel->image ?>')" />
	                	<h6><?php echo $reel->title ?></h6>
	                	<p><?php echo $reel->at_date ?></p>
                	</div>
				<?php $j++; ?>
				<?php echo ($j % 4 == 0) ? '</div>' : "" ?>
				<?php ($j % 4 == 0) ? $k++ : "" ?>
				<?php endforeach ?>
			</div>
-->
		</div>
    </div>
	<?php } ?>
</div>


<?php foreach ($this->special_page_with_portfolio as $img): ?>
<a class="portfolio-popup" href="<?php echo $img->image ?>" rel="shadowbox[portfolio]" style="display:none"><?php echo $img->title ?></a>	
<?php endforeach ?>

<div id="portfoliowrapper">
	<h3>Josie's Modeling Portfolio</h3>
	<?php if (count($this->special_page_with_portfolio) >= 1) { ?>
	<div id="preview">
		<a id="portfolio-popup" href="#">
			<img id="previewimg" src="<?php echo $this->special_page_with_portfolio[0]->middle_img ?>" alt="<?php echo $this->special_page_with_portfolio[0]->title ?>" large="<?php echo $this->special_page_with_portfolio[0]->image ?>" />
		</a>
		<div class="desc" style="width:380px;float:left;margin-left:9px;">
			<h5><?php echo $this->special_page_with_portfolio[0]->title ?></h5>
			<p><?php echo $this->special_page_with_portfolio[0]->at_date ?></p>
		</div>
	
		<div style="width:80px;float:right;">
			<a href="#" id="enlarge">[+] enlarge</a>
		</div>
	</div>

    <div id="slider">
        <div class="navigationbar" style="">  
            <ul class="navigation" <?php echo ($this->portfolio_count <= 1) ? "style='display:none'" : "" ?> >
			<?php for ($i = 0 ; $i <= $this->portfolio_count; $i++) { ?>
			<li><a class="<?php echo ($i == 0) ? 'selected' : '' ?>" href="#p<?php echo ($s = ($i) + 1) ?>">&nbsp;</a></li>
			<?php } ?>
            </ul>
		</div>
		<div class="scroll">
			<div class="scrollContainer" <?php echo (count($this->special_page_with_portfolio) <= 1) ? "style='display:none;'" : "style='width:".($i * 488)."px;'" ?>>
				<?php $j = 0; $k = 1; ?>
				<?php foreach ($this->special_page_with_portfolio as $portfolio): ?>
				<?php echo ($j % 4 == 0) ? '<div class="panel" id="p'.$k.'">' : "" ?>
					<div>
	                	<img src="<?php echo $portfolio->thumb_img ?>" alt="<?php echo $portfolio->title ?>" onclick="load_image('<?php echo $portfolio->middle_img ?>', '<?php echo $portfolio->image ?>', '<?php echo $j ?>', '<?php echo $portfolio->title ?>', '<?php echo $portfolio->at_date ?>')" />
	                	<h6><?php echo $portfolio->title ?></h6>
	                	<p><?php echo $portfolio->at_date ?></p>
                	</div>
				<?php $j++; ?>
				<?php echo ($j % 4 == 0) ? '</div>' : "" ?>
				<?php ($j % 4 == 0) ? $k++ : "" ?>
				<?php endforeach ?>
			</div>
		</div>
    </div>
	<?php } ?>
</div>
</div>