<?php

/**
* 
*/
class Checkout extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	function _remap () {
		
		
		if (!$_POST) {
			
			$referrer = $this->agent->referrer();
			// echo $referrer;
			$arrRerrer = explode('/', $this->agent->referrer());
			
			foreach ($arrRerrer as $item) {
				if ($item == "signout") {
					$referrer = '';
				}
			}
			
			$this->history = $referrer;
			// $this->session->set_userdata('referrer', array(''));
		} else {
			$this->history = $this->input->post('history');
		}
		
		if($this->history == site_url().'viewcart'){
			$this->history = site_url().'membercheckout';
		}
		
		$this->index();
	}
	
	function index () {
		
		if ($this->session->userdata('logged_in') == TRUE) {			
			
			// //	for local 測試
			// redirect('membercheckout');
			
			//	Live Site.
			// header("Location: https://www.josiemarancosmetics.com/membercheckout");
			redirect('membercheckout');
		} else {
			$this->load->view('checkout');
		}
		
	} 
	
	
	
}