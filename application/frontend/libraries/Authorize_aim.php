<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Authorize_aim
{	
	function __construct()
	{		
	}
	
	public function authorize($params)
	{
		$CI =& get_instance();
		
		echo $this->input->post('email'); die;
		
		if($this->input->post('email') == "Testing@sixspokemedia.com" || $this->input->post('email') == "TestingJMC@sixspokemedia.com" || $this->input->post('email') == "devteamintenss@gmail.com") { 

		$x_Login    = '3mPZ93Dm7';
		$x_Password = '72P6HSdj4C7N37yY';
		
		$DEBUGGING = 1;
		$TESTING   = 1;
		$ERROR_RETRIES = 2;
		
		$auth_net_url = 'https://test.authorize.net/gateway/transact.dll';

		
		}else {
		$x_Login    = $CI->config->item('at_login');
		$x_Password = $CI->config->item('at_password');
		
		$DEBUGGING = $CI->config->item('at_debug');
		$TESTING   = $CI->config->item('at_test');
		$ERROR_RETRIES = 2;
		
		$auth_net_url = $CI->config->item('at_site');
	  }	
		
		$authnet_values = array (
			"x_login"				=> $x_Login,
			"x_version"				=> "3.1",
			"x_delim_char"			=> "|",
			"x_delim_data"			=> "TRUE",
			"x_type"				=> "AUTH_ONLY",
			"x_method"				=> "CC",
		 	"x_tran_key"			=> $x_Password,
		 	"x_relay_response"		=> "FALSE",
			"x_card_num"			=> $params->cc,
			"x_exp_date"			=> $params->exp,
			"x_description"			=> $params->desc,
			"x_amount"				=> $params->amount,
			"x_first_name"			=> $params->firstName,
			"x_last_name"			=> $params->lastName,
			"x_address"				=> $params->address,
			"x_city"				=> $params->city,
			"x_state"				=> $params->state,
			"x_zip"					=> $params->zip,
			"CustomerBirthMonth"	=> $params->customerMonth,
			"CustomerBirthDay"		=> $params->customerDay,
			"CustomerBirthYear"		=> $params->customerYear,
			"SpecialCode"			=> $params->specialCode,
		);
		
		$fields = "";
		foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		

		
		$ch = curl_init($auth_net_url);
		
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " ));
		// curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		$result = curl_exec($ch);
		curl_close ($ch);
		
		
		return $result;
		
	}
	
}


