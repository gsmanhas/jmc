<div style="clear:both; padding-top:10px; text-align:left">
	
		<?php 
			
			$current_url = current_url();
			$current_url = str_replace(site_url(), "", $current_url);
		    
			$result = $this->db->query("SELECT * FROM footer_text where url = '%".$current_url."%' and is_active = 'Y'");
				if($result->num_rows() > 0) { 
				$current_url_text = $result->row();
				echo $current_url_text->text;
			 } 
		?>
		
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:5px;" >
		   <tr><td align="left" valign="top" colspan="3" height="10" ></td></tr>
			<tr>
				<?php
					$result = $this->db->query("SELECT * FROM footer_section order by id asc");
					$this->Section = $result->result();
					for($i=0; $i<=3; $i++) {
					
					$result = $this->db->query("SELECT * FROM footer_links where section_id = '".$this->Section[$i]->id."' and is_active = 'Y' order by position asc");
					$this->FooterLinks = $result->result();
					
				?>
				<td align="left" valign="top" width="24%">
					<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:0px;" >
						<tr>
							<td align="left" valign="top" style="font-size:14px; color:#977778; border-bottom:1px solid #000000; padding-bottom:6px;"><strong><?php echo $this->Section[$i]->name; ?></strong></td>
						</tr>
						<tr><td align="left" valign="top" height="5" ></td></tr>
						<?php foreach($this->FooterLinks as $footer_link) { ?>
						<tr>
							<td align="left" valign="top" ><a href="<?php echo $footer_link->url; ?>" ><?php echo $footer_link->name; ?></a></td>
						</tr>
						<?php } ?>
					</table>
				</td>
				<?php if($i < 3) { ?>
				<td align="left" valign="top" width="1%">&nbsp;</td>				
				<?php } } ?>
				
				
			</tr>
		</table>
	</div>