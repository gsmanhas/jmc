<?php
class Zipcodes extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		$this->load->model('zipcode_model');
		$this->ShoppingCart = new SystemCart();
	}
	
	
	public function _remap()
	{
	   if($this->uri->segment(2) == 'with_state'){
			$this->with_state();
		}else {
		   $this->index();
		}
	}
	
	public function index()
    {
		
		$ship_zipcode = $this->uri->segment(2, 0);		
		$is_show_taxrate = $this->zipcode_model->zipcde_wise_tax($ship_zipcode);			
		$this->session->set_userdata('ship_zipcode', $ship_zipcode);
		
		$this->ShoppingCart->DestinationState($this->session->userdata('cart_state'));
		$this->FreeShipping         = $this->ShoppingCart->FreeShipping($this->session->userdata('cart_shipping_method'));
		
		if($this->session->userdata('cart_promo') != "") {
				$this->ShoppingCart->Promo($this->session->userdata('cart_promo'));
		}
		
		if($this->session->userdata('cart_voucher') != "") {
				$this->ShoppingCart->Voucher($this->session->userdata('cart_voucher'));
		}
		
		
		$this->session->set_userdata('CalculateShipping', $this->ShoppingCart->CalculateShipping());
		$total_amount = $this->ShoppingCart->Sum();
		$is_show_taxrate = 'no';
		$total_amount = $total_amount.'|||'.$is_show_taxrate;
	    echo $total_amount; 
	   		
        
    }
	
	public function with_state()
    {
		$ship_zipcode = $this->uri->segment(3);
		$ship_zipcode_arr = explode("_", $ship_zipcode);
		if($ship_zipcode_arr[2] == 'yes'){
			$this->ShoppingCart->DestinationState($ship_zipcode_arr[1]);
			$this->session->set_userdata('ship_zipcode', $ship_zipcode_arr[0]);
			$is_show_taxrate = $this->zipcode_model->zipcde_wise_tax($ship_zipcode_arr[0]);
		}else {
			$this->ShoppingCart->DestinationState($this->session->userdata('cart_state'));			
			$is_show_taxrate = 'no';			
		}
		
		$this->FreeShipping         = $this->ShoppingCart->FreeShipping($this->session->userdata('cart_shipping_method'));
		
		if($this->session->userdata('cart_promo') != "") {
				$this->ShoppingCart->Promo($this->session->userdata('cart_promo'));
		}
		
		if($this->session->userdata('cart_voucher') != "") {
				$this->ShoppingCart->Voucher($this->session->userdata('cart_voucher'));
		}
		
		
		$this->session->set_userdata('CalculateShipping', $this->ShoppingCart->CalculateShipping());
	    $is_show_taxrate = 'no';
	    $total_amount = $this->ShoppingCart->Sum();
		$total_amount = $total_amount.'|||'.$is_show_taxrate;
	    echo $total_amount; 
	   		
        
    }
	
	
}

?>
