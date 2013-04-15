<?php

/**
* 
*/
class Viewcart extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function _remap()
	{
		
        if($this->input->post('method') == 'clearPromo') {
            $DiscountCode = $this->session->userdata('DiscountCode');
            //var_dump($DiscountCode);exit;
            if($DiscountCode[0]->discount_type == 4 || $DiscountCode[0]->discount_type == 5) {
                foreach($this->cart->contents() as $key => $item) {
                    if(array_key_exists('type', $item) &&
                        ($item['type'] == 'buy_one_get_one' || $item['type'] == 'free_gift')) {
                        $this->ShoppingCart->remove2cart($key);
                    }
                }
            }
        }
		/**
		很重要 ...........
		每次進入都先清除以下的 SESSION 中的資料
		*/
		$this->session->unset_userdata('FreeShipping');
		$this->session->unset_userdata('FreeShipping2');
		$this->session->unset_userdata('FreeShipping2_DB');
		$this->session->unset_userdata('ProductTax');
		$this->session->unset_userdata('ShippingOptions');
		$this->session->unset_userdata('CalculateShipping');
		// $this->session->unset_userdata('DestinationState');
		$this->session->unset_userdata('DiscountCode');
		$this->session->unset_userdata('Discount_Sub_Total');
		$this->session->unset_userdata('Amount');
		
		
		if ($this->cart->total_items() <= 0) {
			$this->load->view('continueshopping');
		} else {
			
			switch ($this->input->post('method')) {
				case "clearPromo" :
					$this->session->unset_userdata('FreeShipping');
					$this->session->unset_userdata('FreeShipping2');
					$this->session->unset_userdata('FreeShipping2_DB');
					$this->session->unset_userdata('DiscountCode');
					$this->session->unset_userdata('Discount_Sub_Total');
                    if($this->input->post('vouchercodeInput') != "") {
                        $this->ShoppingCart->FreeShipping($this->input->post('shipping_method'));
                        $this->ShoppingCart->Voucher($this->input->post('vouchercodeInput'));
                    }
					$this->index();
				break;
                case "clearVoucher" :
					$this->session->unset_userdata('VoucherCode');
					$this->session->unset_userdata('Voucher_Sub_Total');
					$this->session->unset_userdata('VoucherBalance');
                    if($this->input->post('discountcodeInput') != "") {
                        $this->ShoppingCart->Promo($this->input->post('discountcodeInput'));
                    }
					$this->index();
				break;
                case 'voucher':
					
                    $this->ShoppingCart->ShippingOptions($this->input->post('shipping_method'));
                    $this->ShoppingCart->DestinationState($this->input->post('state'));
                    $this->ShoppingCart->FreeShipping($this->input->post('shipping_method'));
                    $this->ShoppingCart->Voucher($this->input->post('vouchercodeInput'));
                    if($this->input->post('discountcodeInput') != "") {
                        $this->ShoppingCart->Promo($this->input->post('discountcodeInput'));
                    }
                    $this->index();
                break;
				case "promo" :
				case "changeState" :
                    $this->ShoppingCart->ShippingOptions($this->input->post('shipping_method'));
					$this->ShoppingCart->DestinationState($this->input->post('state'));
                    if($this->input->post('vouchercodeInput') != "") {
                        $this->ShoppingCart->FreeShipping($this->input->post('shipping_method'));
                        $this->ShoppingCart->Voucher($this->input->post('vouchercodeInput'));
                    }
                    $this->ShoppingCart->Promo($this->input->post('discountcodeInput'));
					$this->index();
				break;
				
				case "checkout" :
					if ($this->input->post('zipcodeInput') != '' && $this->input->post('shipping_method') != -1 && $this->input->post('state') != -1) {
                        $this->input->post('country') ? $country_id = $this->input->post('country') : $country_id = 1;
                        
						$this->ShoppingCart->ShippingOptions($this->input->post('shipping_method'));
						$this->ShoppingCart->DestinationCountry($this->input->post('country'));
						$this->ShoppingCart->DestinationState($this->input->post('state'));
						$this->ShoppingCart->Promo($this->input->post('discountcodeInput'));
						$this->ShoppingCart->Voucher($this->input->post('vouchercodeInput'));

						$this->FreeShipping         = $this->ShoppingCart->FreeShipping($this->input->post('shipping_method'));
						$this->ListShippingMethod   = $this->ShoppingCart->ListShippingMethod();
						$this->ListDestinationState = $this->ShoppingCart
                                                           ->ListDestinationState($country_id);
						$this->CalculateShipping    = $this->ShoppingCart->CalculateShipping();
						$this->ProductTax           = $this->ShoppingCart->ProductTax();
						
						$this->Sum                  = $this->ShoppingCart->Sum();

						$this->session->set_userdata('CalculateShipping', $this->ShoppingCart->CalculateShipping());
						$this->session->set_userdata('ProductTax', $this->ShoppingCart->ProductTax());
						$this->session->set_userdata('Amount', $this->ShoppingCart->Sum());
						
						// secure_redirect('checkout');
						//print_r($this->session->userdata('VoucherCode')); die;
						//	沒有 SSL
						redirect('checkout');
						
						// header("Location: https://www.josiemarancosmetics.com/checkout");
						
					} else {
						$this->ErrorMessage = "Please select shipping destination and/or shipping option.";
						$this->index();
					}
				break;
				default :
					$this->index();
				break;
			}
		}
	}
	
	public function index()
	{
	  
		if($this->session->userdata('discount_amount_threshold')) {
		
			  if($this->cart->total() < $this->session->userdata('discount_amount_threshold') ) {	
					
						foreach($this->cart->contents() as $key => $item) {
							if(array_key_exists('type', $item) &&
								($item['type'] == 'buy_one_get_one' || $item['type'] == 'free_gift')) {
								$this->ShoppingCart->remove2cart($key);
							}
						}
				
				  }	
           
		}	
	
	
        $this->session->userdata('cart_country') ? $country_id = $this->session->userdata('cart_country') : $country_id = 1;

		$this->FreeShipping         = $this->ShoppingCart->FreeShipping($this->input->post('shipping_method'));
		$this->ListShippingMethod   = $this->ShoppingCart->ListShippingMethod();
        $this->OnlineShipping       = $this->ShoppingCart->OnlineShippingMethod();
		$this->ListDestinationState = $this->ShoppingCart->ListDestinationState($country_id);
		$this->CalculateShipping    = $this->ShoppingCart->CalculateShipping();
		
		
		if($this->input->post('zipcodeInput')){
			$this->session->set_userdata('ship_zipcode', $this->input->post('zipcodeInput'));
		}else {
		    if($_POST){
			  $this->session->unset_userdata('ship_zipcode');
			}
		}
		
				
		$this->ProductTax           = $this->ShoppingCart->ProductTax();
		
		
		$this->Sum                  = $this->ShoppingCart->Sum();

        $usps_states_array = array(79, 80, 2, 21, 4, 17, 20, 31, 70, 50);

        if($country_id != 1
               || array_search($this->input->post("state"), $usps_states_array) !== false
               || $this->input->post("pobox")) {

            $new_shipping_list = array();

            foreach($this->ListShippingMethod as $shipping) {
                if(preg_match("/usps/mi", $shipping->name)) {
                    $new_shipping_list[] = $shipping;
                }
                
            }

            $this->ListShippingMethod = $new_shipping_list;
        }
		
		// $this->session->set_userdata('ListShippingMethod', $this->ListShippingMethod);
		// $this->session->set_userdata('ListDestinationState', $this->ListDestinationState);
		
		$this->session->set_userdata('CalculateShipping', $this->ShoppingCart->CalculateShipping());
		$this->session->set_userdata('ProductTax', $this->ShoppingCart->ProductTax());
						
		$this->session->set_userdata('Amount', $this->ShoppingCart->Sum());
		
		// $this->Promo_FreeShiiping = $this->session->userdata('FreeShipping');
		$this->Promo_FreeShipping = $this->session->userdata('FreeShipping');
		
		// echo $this->CalculateShipping;		
		
		$this->load->view('viewcart');
	}	
	
		
	
}
