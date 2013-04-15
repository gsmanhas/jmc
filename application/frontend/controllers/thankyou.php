<?php

/**
* 
*/
class Thankyou extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function _remap()
	{				
		$this->index();
	}
	
	public function index()
	{
		
		
		if ($this->session->userdata('INVOICE_NUMBER')) {
		
			$this->session->unset_userdata('order_is_added');	
			
			$query = $this->db->query("SELECT * FROM `order` WHERE payment_method is NULL and order_date is NULL and order_state is NULL and order_no = ?", $this->session->userdata('INVOICE_NUMBER'));
			
			if($query->num_rows() > 0) {
				$this->db->query("DELETE  from `order` WHERE payment_method is NULL and order_date is NULL and order_state is NULL and order_no = ?", $this->session->userdata('INVOICE_NUMBER'));
			}
			
			if ($this->session->userdata('VoucherCode') && $this->session->userdata('Voucher_Sub_Total')) { 
				
					if ($this->session->userdata('FreeShipping2')!= '' and $this->session->userdata('FreeShipping2')!= '0') {
					
						$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$this->session->userdata('FreeShipping2')."' ");
						$shipping_method = $Query->row();
						
					}	
					
					$Voucher = $this->session->userdata('VoucherCode');					
					
					$query = $this->db->query("SELECT * FROM `order_voucher_details` WHERE code = ?", $Voucher[0]->code);
					$voucher_code_info = $query->row();					
					$v_balance = $voucher_code_info->balance - $this->session->userdata('Voucher_Sub_Total') ;
					$this->session->set_userdata('VoucherBalance', $v_balance);
					$this->db->query("UPDATE `order` SET payment_method  = '3' WHERE order_no = ?", $this->session->userdata('INVOICE_NUMBER'));
					$this->db->query("UPDATE `order_voucher_details` SET balance = '".$v_balance."' WHERE code = ?", $Voucher[0]->code);				
					$order_rel_voucher_q = $this->db->query("SELECT * FROM `order_rel_voucher` WHERE order_id = '".$this->session->userdata('INVOICE_NUMBER')."'" );					
					if($order_rel_voucher_q->num_rows() <= 0) {
						$this->db->query("INSERT INTO `order_rel_voucher` SET order_id = '".$this->session->userdata('INVOICE_NUMBER')."', voucher_id = '".$voucher_code_info->id."',  amount = '".$this->session->userdata('Voucher_Sub_Total')."', created_at = '".date('Y-m-d H:i:s')."' , updated_at = '".date('Y-m-d H:i:s')."' , is_delete = '0' " );
					}
					
					if($this->session->userdata('Amount') > 0){
						$this->db->query("UPDATE `order` SET payment_method  = '5' WHERE order_no = ?", $this->session->userdata('INVOICE_NUMBER'));
					}
					
				}
			
			
			if($this->Order[0]->email == 'Testing@sixspokemedia.com' or $this->Order[0]->email == 'TestingJMC@sixspokemedia.com' or $this->Order[0]->email == 'devteamintenss@gmail.com'){
					$this->db->query("UPDATE `order` SET payment_method  = '4' WHERE order_no = ?", $this->session->userdata('INVOICE_NUMBER'));
					
			}
			if($this->session->userdata('is_zipcode_charge_amount')) {
			 
				$this->db->query("UPDATE `order` SET charge_zip_id  = '".$this->session->userdata('is_zipcode_charge')."', charge_zip_amount  = '".$this->session->userdata('is_zipcode_charge_amount')."', charge_zip_rate  = '".$this->session->userdata('is_zipcode_rate')."'  WHERE order_no = ?", $this->session->userdata('INVOICE_NUMBER'));
				$this->session->unset_userdata('is_zipcode_charge');
				$this->session->unset_userdata('is_zipcode_rate');
			    $this->session->unset_userdata('is_zipcode_charge_amount');	
			} 
			
			
			$query = $this->db->query("SELECT * FROM `order` WHERE order_no = ?", $this->session->userdata('INVOICE_NUMBER'));

			$this->Order = $query->result();
			
			
			if (count($this->Order[0]) >= 1) {
			
			
			if ($this->session->userdata('FreeShipping2_DB')) { 
				$this->db->query("UPDATE `order` SET freeshipping_db  = '".$this->session->userdata('FreeShipping2_DB')."' WHERE id = ?", $this->Order[0]->id);
				$this->session->unset_userdata('FreeShipping2_DB');
			}
				
				/* Update voucher payment code was here*/
			
				
				$INVOICE_NUM = $this->session->userdata('INVOICE_NUMBER');
				
				
				

				if ($this->Order[0]->payment_method == 3) {
					//	For Special Status.
					$query = $this->db->query(
						"SELECT * " .
						"FROM `order_list` as ol " .
						"where order_id = ?",
						$this->Order[0]->id);

					$this->OrderList = $query->result();
					$mailer = new Mailer();
					$mailer->send_order($this->Order[0]->email);
					
				}

				//	若修改了 訂單編號, 在這的 P 會無效, 這樣會無法改變訂單的狀態, 怎辦!?
				//	我將它改為, Payment = 2 的話就將 order_state 換成 ordered
				if ($this->Order[0]->payment_method == 2) {						
					
					$order_state_db = 2;
					
					if($_POST['payment_type'] == 'echeck'){
						$order_state_db = 5;
					}  
				
					$this->db->query("UPDATE `order` SET order_state = '".$order_state_db."' WHERE id = ?", $this->Order[0]->id);
					
					
					
					// echo $this->db->last_query();
					
					//	For Paypal Email
					$query = $this->db->query(
						"SELECT * " .
						"FROM `order_list` as ol " .
						"where order_id = ?",
						$this->Order[0]->id);

					$this->OrderList = $query->result();
					// $this->INVOICE_NUMBER = $INVOICE_NUM;
					// $this->ORDER_DATE = $this->Order[0]->order_date;
					$mailer = new Mailer();
					$mailer->send_order($this->Order[0]->email);
				}
				
				
				
				
				$query = $this->db->query("SELECT *".
									"FROM `order_list` as ol " .
									"where order_id = ?",
									$this->Order[0]->id);
                $this->OrderList = array();
                foreach ($query->result() as $item) {
                    if($item->item_type == 'product' ||
                       $item->item_type == 'buy_one_get_one' ||
                       $item->item_type == 'free_gift') {
                        $query2 = $this->db->query("SELECT name, sku FROM product WHERE id=?", $item->pid);
                        $row = $query2->row();
						if($row){
                        	$item->sku = $row->sku;
                        	$item->name = $row->name;
						}
                    } else {
                        $query2 = $this->db->query("SELECT * FROM order_voucher_details WHERE id=?",$item->pid);
                        $row = $query2->row();
						if($row) {
                        	$item->sku = 'eGift Voucher';
                        	$item->name = $row->title . br(1) . "To: " . $row->to . " From: ". $row->from;
						}
                    }

                    $this->OrderList[] = $item;

                }

                if ($this->Order[0]->discount_id != 0) {
                    $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();
                }
				
				// exit;

			}
						
		}
		
		
		
		$this->load->view('thankyou');
	}
	
}
