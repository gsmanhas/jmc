<?php

/**
* 
*/
class Dynamic_menu_tree extends CI_Controller
{
	public $nodes = '';
	public $nodes2selectbox = '';
	
	function __construct()
	{
		// parent::Controller();
		parent::__construct();
		$this->admin->is_login();
		
		$this->load->helper(array('url', 'html', 'form', 'date'));
		$this->load->library('parser');		
		$this->load->library('form_validation');
		
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
		
	}
	
	public function edit($params)
	{
		$numrows = '';
		if ($_POST) {
			if ($_POST['action'] == "save_tree" && $_POST['id'] != "") {
				if ($this->_savetree() == false) {
					return;
				} else {
					$this->success();
				}
			} else if ($_POST['action'] == "delete_tree" && $_POST['id'] != "" && $_POST['edit_id'] != '') {
				$this->edit_id = $_POST['edit_id'];
				$this->id = $_POST['id'];
				$ids = explode(',', $this->edit_id);
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
				
				if ($numrows != 0) {
					$this->message =  $numrows . " records deleted";	
				}
			} else if ($_POST['action'] == "update_tree" && $_POST['id'] != "" && $_POST['edit_id'] != '') {
				$this->edit_id = $_POST['edit_id'];
				$this->id = $_POST['id'];
				
				$result = $this->db->query("SELECT * FROM menus WHERE is_delete = 0 and id = " . $this->edit_id);
				$this->items = $result->result();
				
			} else if ($_POST['action'] == "update_tree_save" && $_POST['id'] != "" && $_POST['edit_id'] != '') {

				$this->edit_id = $_POST['edit_id'];
				$this->id = $_POST['id'];

				if ($this->_update_tree_save() == false) {
					return;	
				}
			}
		}
		
		if (is_numeric($params)) {
			$this->id = $params;
			$this->Recurrsive(0, $this->id);
			$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
			$this->load->view('admin/dynamic_menu_tree');
		}
	}
	
	public function Recurrsive($n, $dynamic_id = 0)
	{
		
		$result = $this->db->query("SELECT * FROM menus WHERE is_delete = 0 and parent = " . $n . " and dynamic_id = " . $dynamic_id . " order by id asc");
		$records = $result->result();
		
		if (count($records) > 0) {
			if ($this->nodes == '') {
				$this->nodes .= "<ul id=\"nav\" class=\"treeview\">";
			} else {
				$this->nodes .= "<ul>";
			}
			
			foreach ($records as $record) {
				$this->nodes .= "<li><a href='javascript:edit_tree($record->id)' >" . $record->title . "</a>";
				$this->Recurrsive($record->id, $dynamic_id);
			}
			$this->nodes .= "</li></ul>";
		}
	}
	
	public function Recurrsive2SelectBox($n, $pid = 0, $count = 0, $dynamic_id = 0)
	{
	
		$result = $this->db->query("SELECT * FROM menus WHERE is_delete = 0 and parent = " . $n . " and dynamic_id = " . $dynamic_id . " order by id asc");
		$records = $result->result();
		$count += 1;
		if (count($records) > 0) {
			foreach ($records as $record) {
				if ($record->parent != $pid) {
					$this->nodes2selectbox .= "<option value=\"" . $record->id . "\">" . nbs(10 * $count) . $record->title . "</option>";	
				} else {
					$this->nodes2selectbox .= "<option value=\"" . $record->id . "\">" . $record->title . "</option>";
				}
				$this->Recurrsive2SelectBox($record->id, $record->parent, $count, $dynamic_id);
			}
		}
	}

	private function _tree_submit_validate()
	{		
		$this->form_validation->set_rules('title', 'Text', 'required');
		// $this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required|alpha_nemeric|alpha_dash|callback_url_check');
		$this->form_validation->set_rules('parent', 'parent', '');
		$this->form_validation->set_rules('enabled', 'enabled', '');
		
		return $this->form_validation->run();
	}

	private function _tree_submit_update_validate()
	{		
		$this->form_validation->set_rules('title', 'Text', 'required');
		// $this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required|alpha_nemeric|alpha_dash|callback_url_check');
		$this->form_validation->set_rules('parent', 'parent', '');
		$this->form_validation->set_rules('enabled', 'enabled', '');
		
		return $this->form_validation->run();
	}

	public function url_check($url)
	{
				
		// $query = $this->db->query('SELECT id FROM menus WHERE url = ? and is_delete = 0 and id != ?', $url, $_POST['edit_id']);
		$this->db->select('id');
		$this->db->from('menus');
		$this->db->where('url', $url);
		$this->db->where_not_in('id', $POST['edit_id']);
		$this->db->where('is_delete', 0);
		$result = $this->db->get()->result();
		
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('url_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function _savetree()
	{
		if ($this->_tree_submit_validate() === FALSE) {
			$this->id = $_POST['id'];
			$this->Recurrsive(0, $this->id);
			$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
			$this->load->view('admin/dynamic_menu_tree');
			return false;
		}

		$Menus = array(
			'enabled'    => $this->input->post('enabled'),
			'title'      => $this->input->post('title'),
			'url'        => $this->input->post('url'),
			'parent'     => $this->input->post('parent'),
			'dynamic_id' => $this->input->post('id'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('menus', $Menus);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'dynamic_meny_tree->save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		
		return true;
	}

	public function _update_tree_save()
	{
		if ($this->_tree_submit_update_validate() === FALSE) {
			$this->Recurrsive(0, $this->id);
			$this->Recurrsive2SelectBox(0, 0, 0, $this->id);
			$this->load->view('admin/dynamic_menu_tree');
			return false;
		}

		$Menus = array(
			'enabled'    => $this->input->post('enabled'),
			'title'      => $this->input->post('title'),
			'url'        => $this->input->post('url'),
			'parent'     => $this->input->post('parent'),
			'dynamic_id' => $this->input->post('dynamic_id'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->edit_id);
		$this->db->update('menus', $Menus);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Menus->_update_tree_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;

	}
	
	public function success()
	{
		$this->message = "Successfully Saved Dynamic Menu";
		// $this->index();
	}

}