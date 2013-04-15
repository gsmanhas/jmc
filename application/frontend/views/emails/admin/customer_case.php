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
	                    
	                    <p>There is a new customer inquiry. Here's the summary:</p>
	                    
	                    <p>The type of case:<br><?php echo $this->SUBJECT ?></p>
	                    
	                    <p>Customer Name:<br><?php echo $this->input->post('first_name') ?>&nbsp;<?php echo $this->input->post('last_name') ?></p>
	                    
	                    <p>Email:<br><?php echo $this->input->post('email') ?></p>
	                    
	                    <p>Comments:<br><?php echo $this->DETAILS ?></p>
	                    
	                    <p>Does the customer use Josie Maran Cosmetics?:<br><?php echo $this->USE_JMC_COSMETICS ?></p>
	                    
	                    <p>Is the customer a registered member?:<br><?php echo $this->IS_REGISTER ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>