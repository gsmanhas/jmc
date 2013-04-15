<?php

/**
* 
*/
class Josies_profolio extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if ($_POST) {
			if ($_POST['action'] == "order" && $_POST['id'] != "") {	//	Save Order.
				$this->_save_order();				
			} else if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.
				$this->_remove($_POST['id']);
			} else if ($_POST['action'] == "publish" && $_POST['id'] != "") {	// Publish or unPublish
				$this->_publish($_POST['id']);
			} else if ($_POST['action'] == "update" && $_POST['id'] != "") {	// Change Update Mode
				$this->_update($_POST['id']);
				return;
			} else if ($_POST['action'] == "update_save" && $_POST['id'] != "") {	//	Save Update Data.
				if ($this->_update_save() == false) {
					return;
				}
			}
		}
	
		$result = $this->db->query("SELECT * FROM josies_profolio WHERE is_delete = 0 ORDER BY ordering asc");
		$this->josies_profolio = $result->result();
			
		$this->load->view('josies_profolio');
	}
	
	public function allpublish()
	{
		$this->db->query("UPDATE  josies_profolio SET publish = 1  WHERE is_delete = 0 ");
		redirect('press');
	}
	
	public function addnew()
	{
		$this->load->view('josies_profolio_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('image', 'image', 'required');
		$this->form_validation->set_rules('thumb_image', 'thumb image', 'required');
		$this->form_validation->set_rules('at_date', 'at Date');
		return $this->form_validation->run();
	}
	
	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->addnew();
			return;
		}
		
		$josies_reel = array(
			'image'        => $this->input->post('image'),
			'thumb_img'    => $this->input->post('thumb_image'),
			'middle_img'   => $this->input->post('middle_img'),
			'title'        => $this->input->post('title'),
			'at_date'      => $this->input->post('at_date'),
			'publish'      => $this->input->post('publish'),
			'created_at'   => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('josies_profolio', $josies_reel);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'josies_profolio->save() 交易錯誤');
		}
		
		redirect('josies_profolio/success', 'refresh');
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$josies_profolio = array(
			'image'        => $this->input->post('image'),
			'thumb_img'    => $this->input->post('thumb_image'),
			'middle_img'   => $this->input->post('middle_img'),
			'title'        => $this->input->post('title'),
			'at_date'      => $this->input->post('at_date'),
			'publish'      => $this->input->post('publish'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('josies_profolio', $josies_profolio);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'josies_profolio->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}
	
	public function success()
	{
		$this->message = "Josies profolio Saved Successfully";
		$this->index();
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update josies_profolio set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where id = ' . $id);	
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
		}
	}

	public function _save_order()
	{
		$numrows = 0;
		if (count($_POST['lists']) != count($_POST['order'])) {
			$this->message = "Oops 排序資料不同步";
		} else {
			$lists  = $_POST['lists'];
			$orders = $_POST['order'];
			$i = 0;
			foreach ($lists as $list) {
				$Ordering = array('ordering' => $orders[$i]);
				$this->db->where('id', $list);
				$this->db->update('josies_profolio', $Ordering);
				
				$numrows += $this->db->affected_rows();
				// echo "$q";
				$i++;
			}
			if ($numrows != 0) {
				$this->message =  $numrows . " records updated";	
			}
		}

	}
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('josies_profolio', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
		
	}
	
	public function _update($ndx)
	{		
		$result = $this->db->query("SELECT * FROM josies_profolio WHERE is_delete = 0 and id = " . $ndx);
		$this->josies_profolio = $result->result();
		$this->load->view('josies_profolio_update');
	}
}