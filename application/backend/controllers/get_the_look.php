<?php

/**
* 
*/
class Get_the_look extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
	
		//	IS_POSTBACK
		if ($_POST) {
			if ($_POST['action'] == "order" && $_POST['id'] != "") {	//	Save Order.
				$this->_save_order();				
			}else if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.
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
	
		$Query = $this->db->query("SELECT * FROM get_the_look WHERE is_delete = 0");
		$this->looks = $Query->result();
	
		$this->load->view('get_the_look');
	}
	
	public function addnew()
	{	
		$this->load->view('get_the_look_addnew');
	}
	
	private function _submit_validate()
	{
		// $this->form_validation->set_rules('publish', 'Publish');
		
		$this->form_validation->set_rules('title', 'title', 'required');
		
		// $this->form_validation->set_rules('download_file', 'download_file', 'required');
		
		// $this->form_validation->set_rules('face_image', 'face image', 'required');
		// // $this->form_validation->set_rules('face_group_by', 'face_group_by', 'required');
		// $this->form_validation->set_rules('face', 'face', 'required');
		// 
		// $this->form_validation->set_rules('eyes_image', 'eyes image', 'required');
		// // $this->form_validation->set_rules('face_group_by', 'face_group_by', 'required');
		// $this->form_validation->set_rules('eyes', 'eyes', 'required');
		// 
		// $this->form_validation->set_rules('lips_image', 'lips image', 'required');
		// // $this->form_validation->set_rules('face_group_by', 'face_group_by', 'required');
		// $this->form_validation->set_rules('lips', 'lips', 'required');
		// 
		// $this->form_validation->set_rules('hair_image', 'hair image', 'required');
		// // $this->form_validation->set_rules('face_group_by', 'face_group_by', 'required');
		// $this->form_validation->set_rules('hair', 'hair', 'required');
		// 
		// $this->form_validation->set_rules('skin_image', 'skin image', 'required');
		// // $this->form_validation->set_rules('face_group_by', 'face_group_by', 'required');
		// $this->form_validation->set_rules('skin', 'skin', 'required');

		return $this->form_validation->run();
	}
	
	public function save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->addnew();
			return;
		}
		
		$get_the_look = array(
			'title'               => $this->input->post('title'),
			'download_this_look'  => $this->input->post('download_file'),
			'the_look'            => $this->input->post('the_look'),
			'face_desc'           => $this->input->post('face'),
			'face_image'          => $this->input->post('face_image'),
			'eyes_desc'           => $this->input->post('eyes'),
			'eyes_image'          => $this->input->post('eyes_image'),
			'lips_desc'           => $this->input->post('lips'),
			'lips_image'          => $this->input->post('lips_image'),
			'hair_desc'           => $this->input->post('hair'),
			'hair_image'          => $this->input->post('hair_image'),
			'skin_desc'           => $this->input->post('skin'),
			'skin_image'          => $this->input->post('skin_image'),
			'publish'             => $this->input->post('publish'),
			'created_at'          => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		
		$this->db->insert('get_the_look', $get_the_look);
		
		$insert_id = $this->db->insert_id();
		
		if (is_array($this->input->post('face_group_by'))) {
			foreach ($this->input->post('face_group_by') as $value) {
				$get_the_look_face = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 1,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_face);
			}
		}
		
		if (is_array($this->input->post('eyes_group_by'))) {
			foreach ($this->input->post('eyes_group_by') as $value) {
				$get_the_look_eyes = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 2,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_eyes);
			}
		}
		
		if (is_array($this->input->post('lips_group_by'))) {
			foreach ($this->input->post('lips_group_by') as $value) {
				$get_the_look_lips = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 3,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_lips);
			}
		}
		
		if (is_array($this->input->post('hair_group_by'))) {
			foreach ($this->input->post('hair_group_by') as $value) {
				$get_the_look_hair = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 4,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_hair);
			}
		}
		
		if (is_array($this->input->post('skin_group_by'))) {
			foreach ($this->input->post('skin_group_by') as $value) {
				$get_the_look_skin = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 5,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_skin);
			}
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Catalogs->save() 交易錯誤');
		}
		
		redirect('get_the_look/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Get the look";
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
					$this->db->query('update get_the_look set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where id = ' . $id);	
						
					$numrows += $this->db->affected_rows();
					
					$this->db->query('delete from get_the_look_rel_product where look_id = ' . $id);
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
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('get_the_look', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
	}
	
	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM get_the_look WHERE is_delete = 0 and id = " . $ndx);
		$this->get_the_look = $result->result();
		$this->load->view('get_the_look_update');
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$this->db->trans_start();
				
		$get_the_look = array(
			'title'               => $this->input->post('title'),
			'download_this_look'  => $this->input->post('download_file'),
			'the_look'            => $this->input->post('the_look'),
			'face_desc'           => $this->input->post('face'),
			'face_image'          => $this->input->post('face_image'),
			'eyes_desc'           => $this->input->post('eyes'),
			'eyes_image'          => $this->input->post('eyes_image'),
			'lips_desc'           => $this->input->post('lips'),
			'lips_image'          => $this->input->post('lips_image'),
			'hair_desc'           => $this->input->post('hair'),
			'hair_image'          => $this->input->post('hair_image'),
			'skin_desc'           => $this->input->post('skin'),
			'skin_image'          => $this->input->post('skin_image'),
			'publish'             => $this->input->post('publish'),
			'updated_at'          => unix_to_human(time(), TRUE, 'us')
		);
		
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('get_the_look', $get_the_look);
		
		$insert_id = $this->input->post('id');
		
		$this->db->query('delete from get_the_look_rel_product where look_id = ' . $this->input->post('id'));
		
		if (is_array($this->input->post('face_group_by'))) {
			foreach ($this->input->post('face_group_by') as $value) {
				$get_the_look_face = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 1,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_face);
			}
		}
		
		if (is_array($this->input->post('eyes_group_by'))) {
			foreach ($this->input->post('eyes_group_by') as $value) {
				$get_the_look_eyes = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 2,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_eyes);
			}
		}
		
		if (is_array($this->input->post('lips_group_by'))) {
			foreach ($this->input->post('lips_group_by') as $value) {
				$get_the_look_lips = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 3,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_lips);
			}
		}
		
		if (is_array($this->input->post('hair_group_by'))) {
			foreach ($this->input->post('hair_group_by') as $value) {
				$get_the_look_hair = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 4,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_hair);
			}
		}
		
		if (is_array($this->input->post('skin_group_by'))) {
			foreach ($this->input->post('skin_group_by') as $value) {
				$get_the_look_skin = array(
					'pid'        => $value,
					'look_id'    => $insert_id,
					'type_id'    => 5,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('get_the_look_rel_product', $get_the_look_skin);
			}
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'get_the_look->update() 交易錯誤');
		}
		
		$this->update_message = "1 records updated";
		return true;
			
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
				$this->db->update('get_the_look', $Ordering);
				
				$numrows += $this->db->affected_rows();
				// echo "$q";
				$i++;
			}
			if ($numrows != 0) {
				$this->message =  $numrows . " records updated";	
			}
		}

	}
	
}