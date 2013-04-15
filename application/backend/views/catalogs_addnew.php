<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<style type="text/css">

.kcfinder_div {
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
    z-index: 999;
}

</style>
<script type="text/javascript" charset="utf-8">

    function openKCFinder(field) {
        jQuery(".kcfinder_div:visible").hide();
        var div = jQuery(field).next().next().get(0);

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
		
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});
		
		jQuery("#dialog_link").click(function(){
			location.href = '<?php echo base_url() ?>admin.php/catalogs';
		});

        if(jQuery("#catalog_type").val() == "1") {
            jQuery("li.advanced").hide();
        } else if(jQuery("#catalog_type").val() == "2") {
            jQuery("li.advanced").show();
        }

        if(jQuery("#video_type").val() == "file") {
            jQuery("li#video_url").hide();
        } else if(jQuery("#video_type").val() == "url") {
            jQuery("li#video_file").hide();
        }

        jQuery("#catalog_type").change(function() {
            if(jQuery(this).val() == "1") {
                jQuery("li.advanced").hide();
            } else if(jQuery(this).val() == "2") {
                jQuery("li.advanced").show();
            }

            if(jQuery("#video_type").val() == "file") {
                jQuery("li#video_url").hide();
            } else if(jQuery("#video_type").val() == "url") {
                jQuery("li#video_file").hide();
            }
        });

        jQuery("#video_type").change(function() {
            if(jQuery("#video_type").val() == "file") {
                jQuery("li#video_url").hide();
                jQuery("li#video_file").show();
            } else if(jQuery("#video_type").val() == "url") {
                jQuery("li#video_file").hide();
                jQuery("li#video_url").show();
            }
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
					<h1><b>Create a new Catalog</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/catalogs" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick">&nbsp;</span>Cancel
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/catalogs/save">
							<ul>
                                <li class="simple">
                                    <label class="desc">Catalog Type</label>
                                    <div>
                                        <select id="catalog_type" name="catalog_type" style="width:50%">
                                            <option value="1" <?php echo set_radio('catalog_type', '1', TRUE); ?>>Simple</option>
                                            <option value="2" <?php echo set_radio('catalog_type', '2'); ?>>Advanced</option>
                                        </select>
                                    </div>
                                </li>
								<li class="simple">
									<label class="desc">Publish</label>
									<div class="col">
										<span>
											<input type="radio" name="publish" class="field checkbox" value="1" <?php echo set_radio('publish', '1', TRUE); ?> >
											<label class="choice">Yes</label>
										</span>	
										<span>
											<input type="radio" name="publish" class="field checkbox" value="0" <?php echo set_radio('publish', '0'); ?> >
											<label class="choice">No</label>
										</span>
									</div>
								</li>
								<li class="simple">
									<label class="desc">Catalog Name</label>
									<div>
									<input style="width:50%" type="text" name="name" value="<?php echo set_value('name'); ?>" maxlength="255" tabindex="1">
									</div>
								</li>
								<li class="simple">
									<label class="desc">URL</label>
									<div>
									<input style="width:50%" type="text" name="url" value="<?php echo set_value('url') ?>" maxlength="255" tabindex="2">
									</div>
								</li>
                                <li class="advanced">
                                    <label class="desc">Top Image</label>
									<div>
									<input id="top_image" name="top_image" type="text" readonly="readonly" value="<?php echo (set_value('top_image') == "") ? "Click here to browse" : set_value('top_image'); ?>" onclick="openKCFinder(this)" style="width:50%;cursor:pointer" /><br />
									<div class="kcfinder_div"></div>
									</div>
                                </li>
                                <li class="advanced">
                                    <label class="desc">Video Type</label>
									<div>
									<select id="video_type" name="video_type"  style="width:50%" >
                                        <option value="url" <?php echo set_value('video_type') == "url" ? 'selected' : '' ?>>YouTube Video</option>
                                        <option value="file" <?php echo set_value('video_type') == "file" ? 'selected' : '' ?>>File</option>
                                    </select>
                                    <br />
									<div class="kcfinder_div"></div>
									</div>
                                </li>
                                <li class="advanced" id="video_file">
                                    <label class="desc">Video File</label>
									<div>
									<input id="video_1" name="video_file" type="text" readonly="readonly" value="<?php echo (set_value('video_file') == "") ? "Click here to browse" : set_value('video_file'); ?>" onclick="openKCFinder(this)" style="width:50%;cursor:pointer" /><br />
									<div class="kcfinder_div"></div>
									</div>
                                </li>
                                <li class="advanced" id="video_url">
                                    <label class="desc">Video Code</label>
									<div>
									<input id="video_2" style="width:50%"  name="video_url" type="text" class="field text small" value="<?php echo (set_value('video_url') == "") ? "" : set_value('video_url'); ?>" /><br />
									<div class="kcfinder_div"></div>
									</div>
                                </li>
                                <li class="advanced">
                                    <label class="desc">Video Preview Image</label>
                                    <div>
                                    <input name="video_preview" type="text" readonly="readonly" value="<?php echo (set_value("video_preview") != "") ? set_value("video_preview") : "Click here to browse"; ?>" onclick="openKCFinder(this)" style="width:50%;cursor:pointer" /><br />
                                    <div class="kcfinder_div"></div>
                                    </div>
                                </li>
                                <li class="advanced">
                                    <label class="desc">Content</label>
									<div>
									<textarea class="ckeditor" cols="20" id="content" name="content" rows="20"><?php echo (isset($_POST['content'])) ? $_POST['content'] : ""; ?></textarea>
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