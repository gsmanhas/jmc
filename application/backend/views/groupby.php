<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/group_by.js"></script>

</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Swatch Group</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="javascript:orderSave()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-bookmark"></span>Save Order
							</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<?php
				if (isset($this->message) && !empty($this->message)) {
				?>
				<div class="response-msg success ui-corner-all">
					<span>Success message</span><?php echo $this->message; ?>
				</div>
				<?php
				}
				?>
				
				<?php
				if (isset($this->update_message) && !empty($this->update_message)) {
				?>
				<div class="response-msg inf ui-corner-all">
					<span>Information message</span><?php echo $this->update_message; ?>
				</div>
				<?php					
				}
				?>
				
				<?php
				if (isset($this->error_message) && !empty($this->error_message)) {
				?>
				<div class="response-msg error ui-corner-all">
					<span>Error message</span><?php echo $this->error_message; ?>
				</div>
				<?php
				}
				?>
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">					
					<form id="frmMain" name="frmMain" method="post" action="">
						<table>
							<thead>
							<tr>
								<th width="1%"></th>
								<th width="25%">Product</th>
								<th>Products grouped by color</th>
							</tr>
							</thead>
							<tbody>
							<?php $i = 1; ?>
							<?php foreach($this->Products as $product) { ?>
							<tr>
								<td style="vertical-align:top"><?php echo $i ?></td>
								<td style="vertical-align:top"><a href="javascript:update(<?php echo $product->id ?>);"><?php echo $product->name ?></td>
								<td>
									<?php
									$query = $this->db->query(
										"SELECT " .
										"id, pid, w.with_id, sorting, (SELECT `name` from product where id = w.with_id AND is_delete = 0) as 'name' " .
										"FROM product_group_by as w " .
										"where pid = " . $product->id . " ORDER BY sorting ASC"
									);
									
									// echo $this->db->last_query();
									
									if (count($query->result()) <= 0) {
										echo "&nbsp;";
									} else {
										foreach ($query->result() as $well_with) {
											printf("<div style=\"width:500px;margin-top:2px\">");
											printf("<span style='margin-right:10px'><input type='text' id='%s' name='sorting[]' size='2' value='%s' onfocus='this.select()'></span>", $well_with->with_id, $well_with->sorting);
											printf("<span style=''>%s</span>", $well_with->name);
											printf("</div>");
											printf("<input type='hidden' name='hid[]' value='%s' >", $well_with->id);										
										}
										
									}
									
									// echo "&nbsp;";
									?>
								</td>
							</tr>
							<?php
								$i++;
							}
							?>
							</tbody>
						</table>
						
					<input type="hidden" name="action" value="" id="action">
					<input type="hidden" name="id" value="0" id="id">
					
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