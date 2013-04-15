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
		$this->admin->is_login();
		
		//	載入後台的選單資訊
		$this->db->select('*')->from('sys_menus');
		$query = $this->db->get();
		$this->menus = $query->result();
		
		if ($this->session->userdata('ip_address') == "0.0.0.0") {
			$this->output->enable_profiler(TRUE);
		}
	}
	
	public function index()
	{

		if ($_POST) {
			if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.
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
			} else if ($_POST['action'] == "edit_tree" && $_POST['id'] != "") {
				// echo $_POST['id'];
				$this->id = $_POST['id'];
				$this->Recurrsive(0, $this->id);
				$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
				$this->load->view('admin/dynamic_menu_tree');
				return;
			} else if ($_POST['action'] == "save_tree" && $_POST['id'] != "") {
				if ($this->_savetree() == false) {
					return;
				}
				$this->id = $_POST['id'];
				$this->Recurrsive(0, $this->id);
				$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
				$this->load->view('admin/dynamic_menu_tree');
				return;
			} else if ($_POST['action'] == "update_tree" && $_POST['id'] != "" && $_POST['edit_id'] != '') {
				$this->edit_id = $_POST['edit_id'];
				$this->id = $_POST['id'];
				
				$q = Doctrine_Query::create()->select('*')
					->from('menus')
					->where('is_delete = ?', 0)
					->andWhere('id = ?', $this->edit_id)
					->orderBy('id desc');

				$this->items = $q->execute();
								
				$this->Recurrsive(0, $this->id);
				$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
				$this->load->view('admin/dynamic_menu_tree');
				return;
			} else if ($_POST['action'] == "update_tree_save" && $_POST['id'] != "" && $_POST['edit_id'] != '') {
				
				$this->edit_id = $_POST['edit_id'];
				$this->id = $_POST['id'];
				
				if ($this->_update_tree_save() == false) {
					return;	
				}
				
				$this->Recurrsive(0, $this->id);
				$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
				$this->load->view('admin/dynamic_menu_tree');
				
				return;
			} else if ($_POST['action'] == "delete_tree" && $_POST['id'] != "" && $_POST['edit_id'] != '') {
				$this->edit_id = $_POST['edit_id'];
				$this->id = $_POST['id'];
				
				$q = Doctrine_Query::create()->update('menus')->set('is_delete', '?', 1)->where('id in(' . $this->edit_id . ')');
				$numrows = $q->execute();
				if ($numrows != 0) {
					$this->message =  $numrows . " records deleted";	
				}
				$this->Recurrsive(0, $this->id);
				$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
				$this->load->view('admin/dynamic_menu_tree');			
				return;
			}
		}
		
		$result = $this->db->query("SELECT * FROM dynamic_menus WHERE is_delete = 0 ORDER BY id asc");
		$this->dynamic_menus = $result->result();
		
		$this->load->view('admin/dynamic_menu');
	
	}
	
	public function addnew()
	{
		$this->load->view('admin/dynamic_menu_addnew');
	}
	
	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('admin/dynamic_menu_addnew');
			return;
		}

		$Dynamic_menu = array(
			'title'      => $this->input->post('title'),
			'publish'    => $this->input->post('publish'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('dynamic_menus', $Dynamic_menu);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Dynamic_menu->save() 交易錯誤');
		}
		
		redirect('/admin/dynamic_menu/success', 'refresh');
	}
	
	private function _submit_validate()
	{		
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		// $this->form_validation->set_rules('url', 'URL', 'required|alpha_nemeric|alpha_dash');
		$this->form_validation->set_rules('parent', 'parent', '');
		
		return $this->form_validation->run();
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
		$this->db->update('dynamic_menus', $Publish);
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
					$this->db->query('update dynamic_menus set is_delete = 1, updated_at = \'' . 
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
	
}