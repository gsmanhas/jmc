<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title><?php echo $this->webpage[0]->page_title ?></title>
	<meta name="Keywords" content="<?php echo $this->webpage[0]->meta_keyword ?>" />
	<meta name="Description" content="<?php echo $this->webpage[0]->meta_description ?>" />
	<meta name="author" content="<?php echo $this->webpage[0]->author ?>">
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" charset="utf-8">
		var base_url = '/';
	</script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="">
	
	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $this->load->view('base/utilities') ?>
	</div>
	
	<div id="main">
		<div id="topnav">
			<?php $this->load->view('base/menu') ?>
		</div>
		<div id="pagetitle"><h1><?php echo $this->webpage[0]->page_name ?></h1></div>
		<?php 
			
			$cms_content = explodewidgets($this->webpage[0]->page_content);
			echo $cms_content;
		 ?>
		
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>

<?php

   //$this->ShoppingCart = new SystemCart();
   
   
   
	function GetBetween($content1,$start){
	
		$return = array();
		$r = explode($start, $content1);	
		
		if(count($r) > 0) {
		$counter = 1;
		foreach($r as $row):	
		
			if(isset($r[$counter]) && $r[$counter]!="") {
				$return[] = $r[$counter];
			}
			
		$counter = $counter+2;
		endforeach;
	  }	
		return $return;
	}
	
	function explodewidgets($content){
	
		$widgets_ids = GetBetween($content, '##');
		
		$SORT_BY = 'sorting';	
		$DESC_OR_ASC = "ASC";
		
		if(count($widgets_ids) > 0){
			foreach($widgets_ids as $row){
					
					$widgets_body = "";
					$widgets_key = "##".$row."##";
					
					
					
					 /*  $catalogInfo = $this->db->query("SELECT * from product_rel_catalog WHERE pid = '".$row."' limit 0,1 ");					 
					 $this->Catalog = $catalogInfo->result();
					
				 if(count($this->Catalog) > 0){	 
					 					
					$result = $this->db->query(
					'SELECT p.* , ' .
					'(SELECT sorting FROM product_rel_catalog as s WHERE s.pid = p.id AND cid = ' . $this->Catalog[0]->id . ') as \'sorting\',' .
					// 'IFNULL((SELECT rate FROM product_review WHERE pid = p.id), 0) as \'rating\' ' .
					'IFNULL((SELECT ROUND(SUM(rate), 2) FROM product_review WHERE pid = p.id AND is_delete = 0 AND publish = 1), 0) as \'rating\'' .
					'FROM product as p WHERE id in(' .
					'SELECT prc.pid as \'pid\' FROM product_catalogs as pc ' .
					'join product_rel_catalog prc ' .
					'on prc.cid = pc.id ' .
					'where prc.show_it = 1 and pc.id = ' . $row .'' . ') and p.publish = 1 and p.is_delete = 0 ORDER BY `'. $SORT_BY .'` ' . $DESC_OR_ASC
				);
					
					$this->products = $query->result();
					if($this->products){
					
						foreach ($this->products as $product) {
								
								$this->product_id = $product->id;
								$this->product_url = $product->url;
								
								$Query = $this->db->query(
									"SELECT with_id FROM product_group_by WHERE pid = ?", $product->id
								);
								$this->shade = $Query->result();
								
								$product->in_stock -= (int)$this->ShoppingCart->get_cart_item_qty($product->id);
								
								$widgets_body = '<div class="buybuttons" style="width:92px;height:26px;float:left;margin-right:3px;">';
								
								if ($product->in_stock <= 0 && $product->can_pre_order == 1) {
								 	$widgets_body .= '<a href="#" class="addtocart" cid="'.$this->Catalog[0]->id.'" pid="'.$product->id.'">Add to Cart</a>';
								}else if ($product->in_stock <= 0 && $product->can_pre_order == 0 && count($this->shade) <= 0) { 
									$widgets_body .= '<a href="#" class="outofstock">Out of Stock</a>';
								}else { 
									if (count($this->shade) >= 1) {
										
										if ($this->product_id == 122 || $this->product_id == 123) {
											$widgets_body .= '<a href="javascript:showShade('.$product->id.')" class="selectashade">Select a Scent</a>';
										} else if ($this->product_id == 119 || $this->product_id == 120 || $this->product_id == 121) { 
											$widgets_body .= '<a href="javascript:showShade('.$product->id.')" class="selectashade">Select a Size</a>';
										}else {
											$widgets_body .= '<a href="javascript:showShade('.$product->id.')" class="selectashade">Select a Shade</a>';
										}
										
									}else { 										
										$widgets_body .= '<a href="#" class="addtocart" cid="'.$this->Catalog[0]->id.'" pid="'.$product->id.'">Add to Cart</a>';
									}
									
									
								}
								
								$widgets_body .= '</div>';
								
								
								$widgets_body .= '<div class="socialbuttons" style="width:119px;height:26px;float:left"><fb:like href="'.base_url().'shop/'.strtolower($this->Catalog[0]->url).'/'.strtolower($product->url).'" layout="button_count"></fb:like></div>';
							
						}
						
					}
					
				}	  */
					
					
					 $content = str_replace($widgets_key, $widgets_body, $content);
			}
		}
		
		return $content;
	}
?>
