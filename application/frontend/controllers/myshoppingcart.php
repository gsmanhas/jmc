<?php

/**
* 
*/
class MyShoppingCart extends MY_Controller
{		
	function __construct()
	{	
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function _remap()
	{
		if (isset($_POST['method'])) {
			$this->index();
		}else {
			$discount_type = $this->uri->segment(3);
			$this->clear_discount($discount_type);
		}
	}
	
	public function index()
	{	
	   
	   
		if (!isset($_POST['method'])) {
			show_404('page');
		}
		
		switch($_POST['method']) {
			case "add" :
				$this->ShoppingCart->add2Cart();
			break;
			case "update" :
				$this->ShoppingCart->update2Cart();
			break;
			case "remove" :
				$this->ShoppingCart->remove2cart();
			break;
			case "clear" :
				$this->ShoppingCart->ClearCart();
			break;
			case "wish" :
				$this->ShoppingCart->add2Wish();
			break;
			default:
			break;
		}
	}	
	
	public function clear_discount($type){
	
		
	   if($type == 'clearVoucher'){	
			$this->session->unset_userdata('cart_voucher');
			$this->session->unset_userdata('VoucherCode');
			$this->session->unset_userdata('Voucher_Sub_Total');
			$this->session->unset_userdata('VoucherBalance');
		}
		if($type == 'clearPromo'){	
		
			
            $DiscountCode = $this->session->userdata('DiscountCode');
            if($DiscountCode[0]->discount_type == 4 || $DiscountCode[0]->discount_type == 5) {
                foreach($this->cart->contents() as $key => $item) {
                    if(array_key_exists('type', $item) &&
                        ($item['type'] == 'buy_one_get_one' || $item['type'] == 'free_gift')) {
                        $this->ShoppingCart->remove2cart($key);
                    }
                }
            }
        
		
			$this->session->unset_userdata('cart_promo');
			$this->session->unset_userdata('DiscountCode');
			$this->session->unset_userdata('Discount_Sub_Total');
			
		}
		redirect('viewcart');
	}
}
