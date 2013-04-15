<link href="<?=base_url()?>css/style_rollover.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?=base_url()?>js/menu.js"></script>
<div class="mainWrap">
<div class="menuImgs">
		
	
	<?php
	$Query = $this->db->query("SELECT * FROM product_symbolkey WHERE publish = 1 AND is_delete = 0 ORDER BY ordering asc");
	$result = $Query->result();
	$i = 1;
	foreach ($result as $icon) {
		
		$style = "";
		
		if($i == 1) {
			$style = 'style="border-left:none"';
		}
		
		$setPedding = '';
		
		if($i > 7) {
			
			if($i%8 == 0) {
				$style = 'style="border-left:none;border-bottom:none;"';
			}else {
				$style = 'style="border-bottom:none;"';
				
			}
		}
		
		
		if($i >= 7) {
		
			if($i%7 == 0) {
			
				if($i > 7) {
					$setPedding = 'style="top:-120px;left:-160px;"';
				}else {
					$setPedding = 'style="left:-160px;"';
					}
			}else {
			   if($i%8 == 0) {
				$setPedding = 'style="top:-120px;"';
				}else {
					$setPedding = 'style="left:-160px;top:-120px;"';
				}
			}
		
		}
		

?>
		


		<div class="innerImgs" <?php echo $style; ?>  ><a href="#" onmouseover="mopen('menu-<?=$i?>')" onmouseout="mclosetime()"><img src="<?php echo $icon->large_image ?>" alt="<?php echo $icon->title ?>" ></a>
			<div style="float:left; position:relative; width:auto; height:auto;">
            <div id="menu-<?=$i?>" onmouseover="mcancelclosetime()" onmouseout="mclosetime()" style="visibility:hidden;" >
              <div class="dropdownmain" <?php echo $setPedding; ?> >
                <div class="hoverInfo"> 
					<img src="<?php echo $icon->large_image ?>" alt="<?php echo $icon->title ?>">
					<h2 style="clear:both"><?php echo $icon->title ?></h2>
					<p ><?php echo substr($icon->description, 0, 120);  ?></p>	
					<a href="#" class="view_products" id="<?php echo $icon->id ?>">View Products »</a>
				</div>
              </div>
            </div>
          </div>
		</div>
		
		
	

<?php
		$i++;
	}
?>
</div>
</div>


