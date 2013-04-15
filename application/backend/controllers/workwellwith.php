<?php

/**
* 
*/
class Workwellwith extends CI_Controller
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
			}
		}
		
		// $result = $this->db->query("SELECT * FROM works_well_with WHERE is_delete = 0 ORDER BY id asc");
		// $this->WorksWrllWith = $result->result();
		
		$query = $this->db->query("SELECT * FROM product where is_delete = 0 order by name asc");
		$this->Products = $query->result();
		
		$this->load->view('/workswellwith');
	}
	
	public function _update()
	{
		$query = $this->db->query("SELECT * FROM product WHERE is_delete = 0 AND id = " . $this->input->post('id'));
		$this->Product = $query->result();
		
		$this->load->view('/workswellwith_update');
		
	}
	
	public function _update_save()
	{
		$numrows = 0;
		if ($this->input->post('id') != "") {
			$ids = explode(',', $this->input->post('id'));
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				$this->db->query('delete FROM works_well_with WHERE pid = ' . $this->input->post('product_id'));
				
				foreach ($ids as $id) {
					$Works_Well_With = array(
						'pid' => $this->input->post('product_id'),
						'with_id' => $id,
						'created_at' => unix_to_human(time(), TRUE, 'us')
					);
					$this->db->insert('works_well_with', $Works_Well_With);
					$numrows += $this->db->affected_rows();
				}
					
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE) {
					$this->error_message = "發生了異常的動作, 系統取消了上次的動作";
					$this->db->trans_rollback();
				} else {
					if ($numrows != 0) {
						$this->message =  $numrows . " records deleted";	
					}
				}
			}
		} else {
			$this->db->query('delete FROM works_well_with WHERE pid = ?', $this->input->post('product_id'));
			$numrows = $this->db->affected_rows();
			if ($numrows != 0) {
				$this->message =  $numrows . " records deleted";	
			}
		}
		return TRUE;
	}
	
}