<?php

/**
* 
*/
class View_order extends MY_Controller
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
				
		$query = $this->db->query("SELECT * FROM `order` WHERE id = ? AND order_state != 5 AND order_state != 6", $id);
		$this->Order = $query->result();
		
		$query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);
		$this->OrderList = $query2->result();
		
		if (count($this->Order[0]->track_number) >= 1) {
			$this->track_number = $this->Order[0]->track_number;
		} else {
			$this->track_number = '';
		}
		
		 if ($this->Order[0]->discount_id != 0) {
                    $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();
           }
				
		
		// $this->load->view('view_order');
		$this->load->view('view_order');
	}
}