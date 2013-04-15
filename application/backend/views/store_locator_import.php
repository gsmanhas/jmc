<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function(){
		
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});
		
		jQuery("#dialog_link").click(function(){
			location.href = '<?php echo base_url() ?>admin.php/store_locator';
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
					<h1><b>Create a new Store Locator</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/store_locator" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick">&nbsp;</span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>
                <?php if(isset($this->error)): ?>
                <div class="response-msg error ui-corner-all">
                    <span>
                        <?php echo $this->error ?>
                    </span>
                </div>
                <?php endif; ?>
                <?php if(isset($this->info)): ?>
                <div class="response-msg success ui-corner-all">
                    <span>
                        <?php echo $this->info ?>
                    </span>
                </div>
                <?php endif; ?>

				<div class="hastable">
				<table>
					<tr>
						<td>
							<form id="frmMain" name="frmMain" method="post"
                                  action="<?php echo base_url() ?>admin.php/store_locator/import" enctype="multipart/form-data">
							<ul>
								<li>
									<label class="desc">File</label>
									<div>
									<input type="file" name="userfile" />
									</div>
								</li>
							</ul>
								
								<input type="hidden" name="action" value="addnew" id="action">
								<input type="hidden" name="id" value="-1" id="id">
							</form>
						</td>
					</tr>
				</table>	
				</div>
				
			</div>			
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<?php $this->load->view('base/footer') ?>
	</div>
</body>
</html>