<?php

/**
* 
*/
class Paypal_return extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function _remap()
	{
		$this->index();
	}
	
	public function index()
	{
		
		$sp 			  = $this->session->userdata('ShippingOptions');
		$DestinationState = $this->session->userdata('DestinationState');
		$DiscountCode     = $this->session->userdata('DiscountCode');
		
		// Load PayPal library
		$this->config->load('paypal_pro');
				
		$config = array(
			'APIUsername'  => $this->config->item('APIUsername'), 	// PayPal API username of the API caller
			'APIPassword'  => $this->config->item('APIPassword'), 	// PayPal API password of the API caller
			'APISignature' => $this->config->item('APISignature'), 	// PayPal API signature of the API caller
			'APISubject'   => '', 									// PayPal API subject (email address of 3rd party user that has granted API permission for your app)
			'APIVersion'   => $this->config->item('APIVersion')		// API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
		);
		
		$this->load->library('paypal/Paypal_pro', $config);
		
				
		$this->PayPalResult = $this->paypal_pro->GetExpressCheckoutDetails($_GET['token']);
		
		// print_r($PayPalResult);
		
		
		if(!$this->paypal_pro->APICallSuccessful($this->PayPalResult['ACK']))
		{
			$this->errors = array('Errors'=>$this->PayPalResult['ERRORS']);
			$this->load->view('paypal_return_error');
		}
		else
		{
			// Successful call.  Load view or whatever you need to do here.	
			 
			$this->DoExpressCheckoutPayment();
			
			$this->load->view('paypal_return');
		}

	}
	
	public function DoExpressCheckoutPayment()
	{
		$DECPFields = array(
			'token' => $this->PayPalResult['TOKEN'], 					// Required.  A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
			'payerid' => $this->PayPalResult['PAYERID'] 				// Required.  Unique PayPal customer id of the payer.  Returned by GetExpressCheckoutDetails, or if you used SKIPDETAILS it's returned in the URL back to your RETURNURL.
		);
		
		$Payments = array();
		$Payment = $this->PayPalResult['PAYMENTS'];
		// $PaymentOrderItems = array();
		// 
		// $Item = array();
		// array_push($PaymentOrderItems, $Item);
		// 
		// // Now we've got our OrderItems for this individual payment, 
		// // so we'll load them into the $Payment array
		$Payment['order_items'] = $this->PayPalResult['ORDERITEMS'];
		// 
		// // Now we add the current $Payment array into the $Payments array collection
		array_push($Payments, $Payment);
		
		// $Payments = $this->PayPalResult['PAYMENTS'];
		
		$UserSelectedOptions = array(
			'shippingcalculationmode' => '', 	// Describes how the options that were presented to the user were determined.  values are:  API - Callback   or   API - Flatrate.
			'insuranceoptionselected' => '', 	// The Yes/No option that you chose for insurance.
			'shippingoptionisdefault' => '', 	// Is true if the buyer chose the default shipping option.  
			'shippingoptionamount' => '', 		// The shipping amount that was chosen by the buyer.
			'shippingoptionname' => '', 		// Is true if the buyer chose the default shipping option...??  Maybe this is supposed to show the name..??
		);
		
		$PayPalRequestData = array(
			'DECPFields' => $DECPFields, 
			'Payments' => $Payments, 
			'UserSelectedOptions' => $UserSelectedOptions
		);
		
		$PayPalResult = $this->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);
		
		if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		{
			$this->errors = array('Errors'=>$PayPalResult['ERRORS']);
			$this->load->view('paypal_return_error');
		}
		else
		{
			
			print_r($PayPalResult);
			
			echo "Successful";
			// $this->load->view('paypal_return');
			// Successful call.  Load view or whatever you need to do here.	
		}
		
		
	}
	

		// $DECPFields = array(
		// 					'token' => $_GET['token'], 					// Required.  A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
		// 					'payerid' => $_GET['PayerID'], 				// Required.  Unique PayPal customer id of the payer.  Returned by GetExpressCheckoutDetails, or if you used SKIPDETAILS it's returned in the URL back to your RETURNURL.
		// 					'returnfmfdetails' => '', 					// Flag to indiciate whether you want the results returned by Fraud Management Filters or not.  1 or 0.
		// 					'giftmessage' => '', 						// The gift message entered by the buyer on the PayPal Review page.  150 char max.
		// 					'giftreceiptenable' => '', 					// Pass true if a gift receipt was selected by the buyer on the PayPal Review page. Otherwise pass false.
		// 					'giftwrapname' => '', 						// The gift wrap name only if the gift option on the PayPal Review page was selected by the buyer.
		// 					'giftwrapamount' => '', 					// The amount only if the gift option on the PayPal Review page was selected by the buyer.
		// 					'buyermarketingemail' => '', 				// The buyer email address opted in by the buyer on the PayPal Review page.
		// 					'surveyquestion' => '', 					// The survey question on the PayPal Review page.  50 char max.
		// 					'surveychoiceselected' => '',  				// The survey response selected by the buyer on the PayPal Review page.  15 char max.
		// 					'allowedpaymentmethod' => '' 				// The payment method type. Specify the value InstantPaymentOnly.
		// 				);
		// 				
		// // You can now utlize parallel payments (split payments) within Express Checkout.
		// // Here we'll gather all the payment data for each payment included in this checkout 
		// // and pass them into a $Payments array.  
		// 
		// // Keep in mind that each payment will ahve its own set of OrderItems
		// // so don't get confused along the way.	
		// 					
		// $Payments = array();
		// $Payment = array(
		// 	
		// 	// 'amt' => '30.00', 						// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
		// 	
		// 	'amt' => $this->session->userdata('Amount'),
		// 	// 'amt' => $this->cart->total(),
		// 	
		// 	'currencycode' => 'USD', 				// A three-character currency code.  Default is USD.
		// 	
		// 	// 'itemamt' => $this->CartCheckout->get_cart_total_items(), 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.  
		// 	// 'shippingamt' => $this->session->userdata('CalculateShipping'), 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
		// 	
		// 	'itemamt' => $this->cart->total(), 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.  
		// 	'shippingamt' => $sp[0]['price'], 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
		// 	
		// 	'shipdiscamt' => '0', 					// Shipping discount for this order, specified as a negative number.
		// 	// 'insuranceoptionoffered' => '', 		// If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
		// 	// 'handlingamt' => '', 					// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
		// 	// 'taxamt' => '0', 						// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order. 
		// 	
		// 	'taxamt' => $this->session->userdata('ProductTax'), 	// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order. 
		// 	
		// 	'desc' => '', 							// Description of items on the order.  127 char max.
		// 	// 'custom' => '', 						// Free-form field for your own use.  256 char max.
		// 	'invnum' => uniqid(), 						// Your own invoice or tracking number.  127 char max.
		// 	// 'notifyurl' => '', 						// URL for receiving Instant Payment Notifications
		// 	'shiptoname' => "Office Information", 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
		// 	'shiptostreet' => "33 New Montgomery St., 16th floor", 					// Required if shipping is included.  First street address.  100 char max.
		// 	// 'shiptostreet2' => 'street address 2', 					// Second street address.  100 char max.
		// 	'shiptocity' => "San Francisco", 					// Required if shipping is included.  Name of city.  40 char max.
		// 	'shiptostate' => "CA", 					// Required if shipping is included.  Name of state or province.  40 char max.
		// 	'shiptozip' => "94105", 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
		// 	'shiptocountrycode' => 'US', 				// Required if shipping is included.  Country code of shipping address.  2 char max.
		// 	'shiptophonenum' => "(415) 744-1530",  				// Phone number for shipping address.  20 char max.
		// 	'notetext' => '' 						// Note to the merchant.  255 char max.  
		// );
		// 	
		// // For order items you populate a nested array with multiple $Item arrays.  
		// // Normally you'll be looping through cart items to populate the $Item array
		// // Then push it into the $OrderItems array at the end of each loop for an entire 
		// // collection of all items in $OrderItems.
		// 			
		// $PaymentOrderItems = array();
		// // $Item = array(
		// // 			'name' => '', 								// Item name. 127 char max.
		// // 			'desc' => '', 								// Item description. 127 char max.
		// // 			'amt' => '', 								// Cost of item.
		// // 			'number' => '', 							// Item number.  127 char max.
		// // 			'qty' => '', 								// Item qty on order.  Any positive integer.
		// // 			'taxamt' => '', 							// Item sales tax
		// // 			'itemurl' => '', 							// URL for the item.
		// // 			'itemweightvalue' => '', 					// The weight value of the item.
		// // 			'itemweightunit' => '', 					// The weight unit of the item.
		// // 			'itemheightvalue' => '', 					// The height value of the item.
		// // 			'itemheightunit' => '', 					// The height unit of the item.
		// // 			'itemwidthvalue' => '', 					// The width value of the item.
		// // 			'itemwidthunit' => '', 						// The width unit of the item.
		// // 			'itemlengthvalue' => '', 					// The length value of the item.
		// // 			'itemlengthunit' => '',  					// The length unit of the item.
		// // 			'itemurl' => '', 							// The URL for the item.
		// // 			'itemcategory' => '', 						// Must be one of the following:  Digital, Physical
		// // 			'ebayitemnumber' => '', 					// Auction item number.  
		// // 			'ebayitemauctiontxnid' => '', 				// Auction transaction ID number.  
		// // 			'ebayitemorderid' => '',  					// Auction order ID number.
		// // 			'ebayitemcartid' => ''						// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
		// // 			);
		// // array_push($PaymentOrderItems, $Item);
		// 
		// foreach($this->cart->contents() as $items) {
		// 	
		// 	$product = $this->db->query("SELECT title, sku FROM product WHERE id = ?", $items['id'])->result();
		// 	
		// 	if (count($product) >= 1) {
		// 		$Item = array(
		// 			'name'   => $items['name'], 	// Item name. 127 char max.
		// 			'desc'   => $product[0]->title, 	// Item description. 127 char max.
		// 			'amt'    => $items['price'], 	// Cost of item.
		// 			'number' => $product[0]->sku, 		// Item number.  127 char max.
		// 			'qty'    => $items['qty']		// Item qty on order.  Any positive integer.
		// 		);
		// 	}						
		// 	// print_r($Item);
		// 	
		// 	array_push($PaymentOrderItems, $Item);
		// }
		// 
		// // Now we've got our OrderItems for this individual payment, 
		// // so we'll load them into the $Payment array
		// $Payment['order_items'] = $PaymentOrderItems;
		// 
		// // Now we add the current $Payment array into the $Payments array collection
		// array_push($Payments, $Payment);
		// 
		// $UserSelectedOptions = array(
		// 	'shippingcalculationmode' => '', 	// Describes how the options that were presented to the user were determined.  values are:  API - Callback   or   API - Flatrate.
		// 	'insuranceoptionselected' => '', 	// The Yes/No option that you chose for insurance.
		// 	'shippingoptionisdefault' => '', 	// Is true if the buyer chose the default shipping option.  
		// 	'shippingoptionamount' => '', 		// The shipping amount that was chosen by the buyer.
		// 	'shippingoptionname' => '', 		// Is true if the buyer chose the default shipping option...??  Maybe this is supposed to show the name..??
		// );
		// 							 
		// $PayPalRequestData = array(
		// 					'DECPFields' => $DECPFields, 
		// 					'Payments' => $Payments, 
		// 					'UserSelectedOptions' => $UserSelectedOptions
		// 				);
		// 				
		// $PayPalResult = $this->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);
		// 
		// if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		// {
		// 	$this->errors = array('Errors'=>$PayPalResult['ERRORS']);
		// 	// $this->load->view('paypal_error',$errors);
		// 	// print_r($errors);
		// 	$this->load->view('paypal_return_error');
		// }
		// else
		// {
		// 	
		// 	// echo "Successful";
		// 	// $this->load->view('paypal_return');
		// 	
		// 	
		// 	sleep(1);
		// 	
		// 	redirect("thankyou", 'refresh');
		// 	
		// 	// Successful call.  Load view or whatever you need to do here.	
		// }
	
}