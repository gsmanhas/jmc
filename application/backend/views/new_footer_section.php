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

					<h1><b>Manage Footer Section</b></h1>

					<div class="other">

						<div class="float-left"></div>

						<div class="button float-right">



							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">

								<span class="ui-icon ui-icon-disk"></span>Save

							</a>

							<a href="#" id="dialog_link" class="btn ui-state-default ui-corner-all" onclick="location.href='<?php echo base_url() ?>admin.php/footer'">

								<span class="ui-icon ui-icon-minusthick"></span>Cancel

							</a>



						</div>

						<div class="clearfix"></div>

					</div>

				</div>



				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>



				<div class="hastable">

				<table>

					<tr>

						<td>	

							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/footer">

								

								<ul>

								 <?php
								 	 for($i=0; $i<=3; $i++) { 
									 $show_id = $i+1;
								 ?>
                                    <li>
										<label class="desc">Section <?=$show_id?></label>
										<input type="text" name="name<?=$show_id?>" value="<?php echo $this->Section[$i]->name ?>" id="name<?=$show_id?>" style="width:50%">
										<input type="hidden" name="id<?=$show_id?>" value="<?php echo $this->Section[$i]->id ?>" id="name<?=$show_id?>">
									</li>
								<?php } ?>	

								</ul>

								<input type="hidden" name="action" value="update_section_save" id="action">

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