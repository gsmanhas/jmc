<?php

/**
* 
*/
class Groupby extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		//	IS_POSTBACK
		if ($_POST) {
			if ($_POST['action'] == "update" && $_POST['id'] != "") {	// Change Update Mode
				$this->_update();
				return;
			} else if ($_POST['action'] == "update_save") {	//	Save Update Data.
				if ($this->_update_save() == false) {
					return;
				}
			} else if ($_POST['action'] == "order") {	//	Save Update Data.
				$this->_sorting();
			}
		}
		
		$query = $this->db->query("SELECT * FROM product where is_delete = 0 order by name asc");
		$this->Products = $query->result();
		
		$this->load->view('groupby');
	}	
	
	public function _update()
	{
		$query = $this->db->query("SELECT * FROM product WHERE is_delete = 0 AND id = " . $this->input->post('id'));
		$this->Product = $query->result();
		
		$this->load->view('group_by_update');
		
	}
	
	public function _update_save()
	{
		
		$product_rel_catalog_sotring = $this->db->query('SELECT * FROM `product_group_by` WHERE pid = ?', $this->input->post('product_id'))->result();
		$product_id = $this->input->post('product_id');
		$this->db->query("DELETE FROM `product_group_by` WHERE pid = {$product_id} OR with_id={$product_id} ");
		$numrows = $this->db->affected_rows();

        if (is_array($this->input->post('product_group_by'))) {
			foreach ($this->input->post('product_group_by') as $value) {
				
				$bol = FALSE;
				foreach ($product_rel_catalog_sotring as $item) {
					if ($item->with_id == $value) {
						$product_group_by = array(
							'pid' => $this->input->post('product_id'),
							'with_id' => $value,
							'sorting' => $item->sorting,
							'created_at' => unix_to_human(time(), TRUE, 'us')
						);
						$bol = TRUE;
					}
				}
				
				if ($bol == FALSE) {
					$product_group_by = array(
						'pid' => $this->input->post('product_id'),
						'with_id' => $value,
						'created_at' => unix_to_human(time(), TRUE, 'us')
					);
				}
				
				$this->db->insert('product_group_by', $product_group_by);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if ($numrows != 0) {
			$this->message =  $numrows . " records deleted";	
		}
		
		return TRUE;
	}
	
	private function _sorting()
	{
		$ndx = $this->input->post('sorting');
		$hid = $this->input->post('hid');
		
		// echo count($ndx).br(1);
		// echo count($hid).br(1);
		
		$numrows = 0;
		for ($i = 0; $i < count($ndx); $i++) {
			// echo $ndx[$i].br(1);
			// echo $hid[$i].br(1);
			$this->db->query("UPDATE product_group_by SET sorting = ? WHERE id = ?", array($ndx[$i], $hid[$i]));
			// echo $this->db->last_query().br(1);
			$numrows += $this->db->affected_rows();
			
		}
		
		if ($numrows != 0) {
			$this->message =  $numrows . " records deleted";	
		}

		
	}
	
	
}