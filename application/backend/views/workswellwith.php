<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/works_well_with.js"></script>
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
					<h1><b>Works Well With</b></h1>
					<div class="other">
						<div class="float-left">Click on a product and then select one or more products that work well with that product.</div>
						<div class="button float-right">

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
								<th width="1%">#</th>
								<th width="25%">Product</th>
								<th>Works Well With</th>
							</tr>
							</thead>
							<tbody>
							<?php $i = 1; ?>
							<?php foreach($this->Products as $product) { ?>
							<tr>
								<td><?php echo $i ?></td>
								<td><a href="javascript:update(<?php echo $product->id ?>);"><?php echo $product->name ?></td>
								<td>
									<?php
									$query = $this->db->query(
										"SELECT " .
										"pid, (SELECT `name` from product where id = w.with_id) as 'name' " .
										"FROM works_well_with as w " .
										"where pid = " . $product->id
									);
									foreach ($query->result() as $well_with) {
										printf("<li>%s</li>", $well_with->name);
									}
									echo "&nbsp;";
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