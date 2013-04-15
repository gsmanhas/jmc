<?php

/**
* 
*/
class PriceList extends CI_Controller
{
	
	function __construct()
	{
		
		parent::__construct();
	}
	
	public function index()
	{
		if ($_POST) {
			if ($_POST['action'] == "single_update") {
				$this->single_update();
			} else if ($_POST['action'] == "saveAll") {
				$this->saveAll();
			}
		}
		
		$result = $this->db->query("SELECT * FROM product order by `name` asc");
				
		$this->Products = $result->result_array();
		
		$this->load->view('pricelist');
	}
	
	public function submit()
	{
		$numrows = 0;
		// $ids = explode(',', $this->input->post('pid'));
		$ids = $this->input->post('pid');
		$this->db->trans_start();
		
		foreach ($ids as $id) {
			$Price_List = array(
				'retail_price' => $this->input->post('retail_'.$id),
				'price' => $this->input->post('sale_'.$id),
				'on_sale' => $this->input->post('on_sale_'.$id),
				'updated_at' => unix_to_human(time(), TRUE, 'us')
			);
			$this->db->where('id', $id);
			$this->db->update('product', $Price_List);
			$numrows += $this->db->affected_rows();
		}
		
		
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->error_message = "Price List Save All, 發生了異常的動作, 系統取消了上次的動作";
			$this->db->trans_rollback();
		} else {
			if ($numrows != 0) {
				$this->message =  $numrows . " records deleted";	
			}
		}
		
		$this->index();
	}
	
}





