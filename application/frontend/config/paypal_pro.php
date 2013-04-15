<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['Sandbox']      = TRUE;
$config['APIVersion']   = '66.0';
$config['APIUsername']  = $config['Sandbox'] ? 'develo_1304063204_biz_api1.sixspokemedia.com' : 'PRODUCTION_USERNAME_GOES_HERE';
$config['APIPassword']  = $config['Sandbox'] ? '1304063217' : 'PRODUCTION_PASSWORD_GOES_HERE';
$config['APISignature'] = $config['Sandbox'] ? 'Aia2hyMJBvuHpPcm5GN0ggXROAXpAJ.dhUKXkkQE3tys0rp6WLRWGk13' : 'PRODUCTION_SIGNATURE_GOES_HERE';

/* End of file paypal_pro.php */
/* Location: ./system/application/config/paypal_pro.php */