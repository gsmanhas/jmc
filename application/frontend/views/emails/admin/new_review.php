<html>
    <head>
        
    </head>
    <body>
    
    	<style type="text/css">
            body { margin-top: 0; margin-right: 10px; margin-bottom: 20px; margin-left: 10px; color: #493838; }
            body, table { font: 12px/18px Helvetica, Arial, sans-serif; }
            table { min-width:680px; margin-bottom: 20px; }
            table span { color: #888; font-weight: bold; }
            p { margin-bottom: 20px; }
        </style>
        
        <table width="98%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td colspan="5" style="height:65px;">
						<div style="width:100%;height:65px;border-bottom:1px solid #f3e4e9;">
							<img src="<?php echo base_url() ?>images/global/josie-maran.gif" alt="Josie Maran" />
						</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr valign="top">
                    <td colspan="5">
	                    <p>Hi Josie Maran Administrator,</p>
	                    
	                    <p>There is a new customer review pending your approval.</p>
	                    
	                    <p>From:&nbsp;<?php echo $this->session->userdata('email') ?></p>
	                    
	                    <p>Product:&nbsp;<?php echo $this->PRODUCT_NAME ?></p>		
	
	                    <p>Review:&nbsp;<?php echo $this->DETAILS ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>