<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>

<script type="text/javascript">

jQuery(document).ready(function(){
	jQuery("#btn_submit").click(function(){

		var ids = [];
		jQuery(':checkbox').each(function(index, value){
			if (jQuery(this).attr('checked') == true) {
				if (jQuery(this).val() != 0)
					ids.push(jQuery(this).val());
			}
		});
		
		jQuery("#action").attr('value', 'update_save');
		jQuery("#id").val(ids);
		jQuery("#frmMain").submit();
	});
});



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
					<h1><b>Works Well With</b></h1>
					<div class="other">
						<div class="float-left">Select one or more products below.</div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/workwellwith" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/workwellwith">
					
					<table border="0" cellspacing="5" cellpadding="5">
					<thead>
						<tr>
							<th colspan="4" style="text-align:left;"><?php echo $this->Product[0]->name ?></th>
						</tr>
					</thead>
					
						<?php
						$query = $this->db->query(
							"SELECT * FROM product WHERE is_delete = 0 AND id != " . $this->Product[0]->id .
							" order by name asc"
							);
						
						$Products = $query->result_array();

						$query = $this->db->query(
							"SELECT with_id FROM works_well_with WHERE pid = " . $this->Product[0]->id
						);
						
						$Wroks_well_with = $query->result();
						
						
						
						for ($i = 0; $i < count($Products); $i++) {
							$Checked = '';
							if (count($Wroks_well_with) >= 1) {
								foreach ($Wroks_well_with as $works) {
									if ($works->with_id == $Products[$i]['id']) {
										$Checked = "checked=\"checked\"";
									}
								}
							}
							
							if ($i % 2 == 0) {
								echo "<tr>";
							}
						?>
							<td width="3%"><input type="checkbox" name="" value="<?php echo $Products[$i]['id']; ?>" id="" <?php echo $Checked; ?>></td>
							<td width="47%"><?php echo $Products[$i]['name'] ?></td>
						<?php

								if (($i + 1) == count($Products)) {
									if ($i % 2 == 0) {
										echo "<td colspan=\"4\">&nbsp;</td>";
									}
								}

								if ($i % 2 == 1) {
									echo "</tr>";
								}

							}

						?>
					</table>
					
					<input type="hidden" name="action" value="update_save" id="action">
					<input type="hidden" name="id" value="-1" id="id">
					<input type="hidden" name="product_id" value="<?php echo $this->Product[0]->id ?>" id="product_id">
					
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