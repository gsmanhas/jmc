<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<style type="text/css">

#kcfinder_div {
    display: none;
    position: absolute;
    width: 670px;
    height: 400px;
    background: #e0dfde;
    border: 2px solid #3687e2;
    border-radius: 6px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    padding: 1px;
}

</style>
 
<script type="text/javascript">

function openKCFinder(field) {
    var div = document.getElementById('kcfinder_div');
    if (div.style.display == "block") {
        div.style.display = 'none';
        div.innerHTML = '';
        return;
    }
    window.KCFinder = {
        callBack: function(url) {
            window.KCFinder = null;
            field.value = url;
            div.style.display = 'none';
            div.innerHTML = '';
        }
    };
    div.innerHTML = '<iframe name="kcfinder_iframe" src="<?php echo base_url() ?>kcfinder/browse.php?type=files&dir=files/public" ' +
        'frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" />';
    div.style.display = 'block';
}

jQuery(document).ready(function(){
	
	var arrSwatcheIDs = new Array();
	var arrSwatcheIMGs = new Array();
	
   	<?php
	
	$result = $this->db->query("SELECT * FROM product_swatch WHERE is_delete = 0 ORDER BY title asc");
	$swatches = $result->result();
	
	foreach ($swatches as $swatche) {
	?>   
		arrSwatcheIDs.push(<?php echo $swatche->id ?>); 
		arrSwatcheIMGs.push('<?php echo $swatche->image; ?>');
	<?php
	}
   	?>

	jQuery("#btn_submit").click(function(){
		if (jQuery("#swatche_image").val() == "Click here to browse") {
			jQuery("#swatche_image").val('');
		}
		jQuery("#frmMain").submit();
	});
		
	jQuery("#swatche").change(function(){
		jQuery(this).each(function(index, value){
			for (var i = 0; i < arrSwatcheIDs.length; i++) {
				// console.log(arrSwatcheIDs[i]);
				if (arrSwatcheIDs[i] == jQuery(this).val()) {
					jQuery("#swatche_img").attr('src', arrSwatcheIMGs[i]);
				}
			}
		});
	})
	
	jQuery("#swatche").change();
	
});

function changeColor (obj) {
	jQuery("#swatche_img").css({
		"background-color" : jQuery(obj).val()
	});
}


</script>

</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Create a new Product</b></h1>
					<div class="other">
						<div class="float-left">Creating a new or managing an existing item is easy. Move your mouse over each part of the page <br>to get an instant explanation to help guide you through.</div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/products" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/products/save">
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td class="fieldlabel">Publish</td>
								<td colspan="3">
									<span>
										<input type="radio" name="publish" class="field checkbox" value="1" <?php echo set_radio('publish', '1', TRUE); ?> >
										<label class="choice">Yes</label>
										<input type="radio" name="publish" class="field checkbox" value="0" <?php echo set_radio('publish', '0'); ?> >
										<label class="choice">No</label>
									</span>
								</td>
							</tr>
							<tr>
								<td class="fieldlabel">Name</td>
								<td><input type="text" name="name" class="field text small" value="<?php echo set_value('name'); ?>" maxlength="255" style="width:100%"></td>
								<td class="fieldlabel">Title <span style="font-weight:normal;">(generic product name)</span></td>
								<td><input type="text" name="title" class="field text small" value="<?php echo set_value('title'); ?>" maxlength="255" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Small Image</td>
								<td>
									<input id="small_image" name="small_image" type="text" readonly="readonly" value="<?php echo (set_value('small_image') == "") ? "Click here to browse" : set_value('small_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div"></div>	
								</td>
								<td class="fieldlabel">Large Image</td>
								<td>
									<input id="large_image" name="large_image" type="text" readonly="readonly" value="<?php echo (set_value('large_image') == "") ? "Click here to browse" : set_value('large_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div"></div>
								</td>
							</tr>
							<tr>
								<td class="fieldlabel">
                                    SKU
                                    <br/>
                                    <br/>
                                    Item Number
                                </td>
								<td>
                                    <input type="text" name="sku" class="field text small" value="<?php echo set_value('sku'); ?>" maxlength="255" style="width:100%">
                                    <br/>
                                    <br/>
                                    <input type="text" name="item_number" class="field text small" value="<?php echo set_value('item_number'); ?>" maxlength="255" style="width:100%">
								</td>
								<td class="fieldlabel">URL</td>
								<td><input type="text" name="url" class="field text small" value="<?php echo set_value('url'); ?>" maxlength="255" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Regular Price</td>
								<td><input type="text" name="retail_price" class="field text small" value="<?php echo set_value('retail_price'); ?>" maxlength="255" style="width:100%"></td>
								<td class="fieldlabel">Discounted Price</td>
								<td><input type="text" name="price" class="field text small" value="<?php echo set_value('price'); ?>" maxlength="255" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">On Sale?</td>
								<td>
									<?php
										$checked = '';
										if (isset($_POST['on_sale'])) {
											$checked = "checked=\"checked\"";
										}
									?>
									<input type="checkbox" name="on_sale" value="1" id="on_sale" <?php echo $checked; ?>>
								</td>
								<td class="fieldlabel">Video Path</td>
								<td><input type="text" name="video_path" value="<?php echo set_value('video_path') ?>" id="video_path" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel" valign="top">Catalogs</td>
								<td>
									<select name="category_id[]" id="category_id" multiple size="8" style="width:100%">
									<?php
									
									// $q = Doctrine_Query::create()->select('*')->from('product_categories')->where('is_delete = 0')->orderBy('name asc');
									// $categories = $q->execute();
									
									$result = $this->db->query("SELECT * FROM product_catalogs WHERE is_delete = 0 ORDER BY name asc");
									$categories = $result->result();
									
									$is_selected = '';
									foreach ($categories as $categorie) {
										foreach ($_POST['category_id'] as $selected) {
											if ($selected == $categorie->id) {
												$is_selected = 'selected=\"selected\"';
											}
										}
										printf("<option value=\"%s\" %s>%s</option>", $categorie->id, $is_selected, $categorie->name);
										$is_selected = '';
									}
									?>
									</select>
								</td>
								<td class="fieldlabel" valign="top">Symbol Key</td>
								<td>
									<select name="symbolkey_id[]" id="symbolkey_id" multiple size="8" style="width:100%">
									<?php
									$is_selected = '';
									
									// $q = Doctrine_Query::create()->select('*')->from('product_symbolkey')->where('is_delete = 0')->orderBy('title asc');
									// $symbols = $q->execute();
									
									$result = $this->db->query("SELECT * FROM product_symbolkey WHERE is_delete = 0 ORDER BY title asc");
									$symbols = $result->result();
									
									foreach ($symbols as $symbol) {
										foreach ($_POST['symbolkey_id'] as $selected) {
											if ($selected == $symbol->id) {
												$is_selected = 'selected=\"selected\"';
											}
										}
										printf("<option value=\"%s\" %s>%s</option>", $symbol->id, $is_selected, $symbol->title);
										$is_selected = '';
									}
									?>			
									</select>
								</td>
							</tr>
							<tr>
								<td class="fieldlabel" valign="top" style="height:100px">Swatch</td>
								<td colspan="3">

										<table border="0" cellspacing="5" cellpadding="5" style="width:100%; margin-bottom:0px">
											<tr>
												<td rowspan="4" style="width:120px">
													<select name="swatche" id="swatche" size="10" style="float:left">
													<?php
													$is_selected = '';
													
													// $q = Doctrine_Query::create()->select('*')->from('product_swatche')->where('is_delete = 0')->orderBy('title asc');
													// $swatches = $q->execute();
													
													$result = $this->db->query("SELECT * FROM product_swatch WHERE is_delete = 0 ORDER BY title asc");
													$swatches = $result->result();
													
													if ($_POST['swatche'] == 0) {
														printf("<option value=\"%s\" %s>%s</option>", 0, "selected=\"selected\"", "none");
													} else {
														printf("<option value=\"%s\" %s>%s</option>", 0, "", "none");
													}
													foreach ($swatches as $swatche) {
														if ($swatche->id == $_POST['swatche']) {
															printf("<option value=\"%s\" selected=\"selected\">%s</option>", $swatche->id, $swatche->title);
														} else {
															printf("<option value=\"%s\">%s</option>", $swatche->id, $swatche->title);
														}
													}
													?>
													</select>
												</td>
											</tr>
											<tr>
												<td rowspan="3" style="width:100px">
													<img id="swatche_img" src="" 
														style="background:#cccccc; margin-bottom: 14px;margin-left: 10px;margin-top: 30px;">
												</td>
												<td class="fieldlabel">Name</td>
												<td><input type="text" name="swatche_name" value="<?php echo set_value('swatche_name'); ?>" id="swatche_name"></td>
											</tr>
											<tr>
												<td class="fieldlabel">Description</td>
												<td><input type="text" name="swatche_title" value="<?php echo set_value('swatche_title'); ?>" id="swatche_title"></td>
											</tr>
											<tr>
												<td class="fieldlabel">Color (Hex Value)</td>
												<td><input type="text" name="color" value="<?php echo (isset($_POST['color'])) ? $_POST['color'] : "#FFFFFF"; ?>" id="color" size="8" onblur="changeColor(this);"></td>
											</tr>
										</table>

								</td>
							</tr>

							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>Description on Product List Page<br><br></div>
									<textarea class="ckeditor" cols="20" id="list_desc" name="list_desc" rows="20"><?php echo (isset($_POST['list_desc'])) ? $_POST['list_desc'] : ""; ?></textarea><br>
								</td>
							</tr>

							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>Description on Product Details Page<br><br></div>
									<textarea class="ckeditor" cols="80" id="description" name="description" rows="20"><?php echo (isset($_POST['description'])) ? $_POST['description'] : ""; ?></textarea><br>
								</td>
							</tr>

							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>Tips<br><br></div>
									<textarea class="ckeditor" cols="20" id="tips" name="tips" rows="20"><?php echo (isset($_POST['tips'])) ? $_POST['tips'] : ""; ?></textarea><br>
								</td>
							</tr>
							
							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>How to Use<br><br></div>
									<textarea class="ckeditor" cols="20" id="how_to_use" name="how_to_use" rows="20"><?php echo (isset($_POST['how_to_use'])) ? $_POST['how_to_use'] : ""; ?></textarea><br>
								</td>
							</tr>
							
							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>Ingredient<br><br></div>
									<textarea class="ckeditor" cols="20" id="ingredient" name="ingredient" rows="20"><?php echo (isset($_POST['ingredient'])) ? $_POST['ingredient'] : ""; ?></textarea><br>
								</td>
							</tr>
							
						</table>					
						<input type="hidden" name="action" value="addnew" id="action">
						<input type="hidden" name="id" value="-1" id="id">
					</form>
				</div>
				
			</div>			
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<?php $this->load->view('base/footer') ?>
	</div>
</body>
</html>