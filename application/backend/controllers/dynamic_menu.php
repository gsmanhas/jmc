<?php

/**
* 
*/
class Dynamic_menu extends CI_Controller
{
	
	public $nodes = '';
	public $nodes2selectbox = '';
	
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
		
		$result = $this->db->query("SELECT * FROM menus WHERE parent = -1 and is_delete = 0 ORDER BY id asc");
		$this->Menus = $result->result();
		
		$this->load->view('dynamic_menu');
	
	}
	
	public function addnew()
	{
		$this->load->view('dynamic_menu_addnew');
	}
	
	private function _submit_validate()
	{		
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		// $this->form_validation->set_rules('url', 'URL', 'required|alpha_nemeric|alpha_dash|callback_check_url');
		// $this->form_validation->set_rules('url', 'URL', 'required|callback_check_url');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('parent', 'parent', '');
		
		return $this->form_validation->run();
	}
	
	private function _update_submit_validate()
	{		
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		// $this->form_validation->set_rules('url', 'URL', 'required|alpha_nemeric|alpha_dash|callback_check_url2');
		// $this->form_validation->set_rules('url', 'URL', 'required|callback_check_url2');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('parent', 'parent', '');
		
		return $this->form_validation->run();
	}

	public function check_url()
	{
		$this->db->select('*');
		$this->db->from('menus');
		$this->db->where('url', $this->input->post('url'));
		$this->db->where('is_delete', 0);
		// $this->db->where_not_in('parent', $this->input->post('parent'));
		
		$url = $this->db->get()->result_array();
		if (count($url) >= 1) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function check_url2()
	{
		$this->db->select('*');
		$this->db->from('menus');
		$this->db->where('url', $this->input->post('url'));
		$this->db->where('is_delete', 0);
		$this->db->where_not_in('id', $this->input->post('id'));
		
		$url = $this->db->get()->result_array();
		if (count($url) >= 1) {
			$this->form_validation->set_message('check_url2', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('dynamic_menu_addnew');
			return;
		}
		
		$Menu = array(
			'title'      => $this->input->post('title'),
			'publish'    => $this->input->post('publish'),
			'url'        => $this->input->post('url'),
			'parent'     => $this->input->post('parent'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
		
		$this->db->trans_start();
		$this->db->insert('menus', $Menu);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Dynamic_menu->save() 交易錯誤');
		}
		
		redirect('dynamic_menu/success', 'refresh');
		
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Dynamic Menu";
		$this->index();
	}

	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('menus', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
	}

	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update menus set is_delete = 1, updated_at = \'' . 
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
	
	public function _update()
	{
		$this->db->select('*');
		$this->db->from('menus');
		$this->db->where('is_delete', 0);
		$this->db->where('id', $this->input->post('id'));
		$this->Menu = $this->db->get()->result();
		
		$this->load->view('dynamic_menu_update');
	}
	
	public function _update_save()
	{
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return;
		}
		
		$Menu = array(
			'title'      => $this->input->post('title'),
			'publish'    => $this->input->post('publish'),
			'url'        => $this->input->post('url'),
			'parent'     => $this->input->post('parent'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);
		
		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('menus', $Menu);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Dynamic_menu->save() 交易錯誤');
		}
		
		redirect('dynamic_menu/success', 'refresh');		
		
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
				$this->db->update('menus', $Ordering);
				
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