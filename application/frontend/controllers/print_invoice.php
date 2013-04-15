<?php

/**
* 
*/
class Print_invoice extends MY_Controller
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
		// echo $this->uri->segment(2, 0);
		
		$id = $this->uri->segment(2, 0);
        if($id == 'bulkprint') {
            $this->bulkprint();
            exit;
        }
				
		$query = $this->db->query("SELECT * FROM `order` WHERE id = ?", $id);
		$this->Order = $query->result();
		
		
		
		
		$query = $this->db->query("SELECT " .
		"id, (select state from state as s where s.id = t.state_id) as `state`," .
		"state_id, " .
		"tax_code, tax_rate " .
		"FROM " .
		"tax_codes as t " .
		"where is_delete = 0 ORDER BY `state` ASC");
		$this->continental = $query->result();
		
		$query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);
		$this->OrderList = $query2->result();
		
		$this->Voucher = null;
		
        $query = $this->db->query("SELECT * FROM order_rel_voucher WHERE order_id = ?", $this->Order[0]->order_no);
        if($query->num_rows() > 0) {		
            $this->Voucher = $query->row();
        } else {
			$query = $this->db->query("SELECT * FROM order_rel_voucher WHERE order_id = ?", $id);
			if($query->num_rows() > 0) {
				$this->Voucher = $query->row();
			}
        }

        if ($this->Order[0]->discount_id != 0) {
            $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();
        }
		
		if (count($this->Order[0]->track_number) >= 1) {
			$this->track_number = $this->Order[0]->track_number;
		} else {
			$this->track_number = '';
		}
		
		$this->load->view('printer');
		
	}

    
	
}