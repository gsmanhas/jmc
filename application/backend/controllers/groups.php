<?php

/**
* 
*/
class Groups extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		$result = $this->db->query("SELECT `id`, `name`, `publish`, (SELECT count(id) FROM groups_rel_products WHERE parent = g.id) as 'count' FROM groups as g ORDER BY id asc");
		// echo $this->db->last_query();
		$this->groups = $result->result();
		
		$this->load->view('groups');
	}
	
	public function addnew()
	{
		$this->load->view('groups_addnew');
	}
	
	public function save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('groups_addnew');
			return;
		}	
		
		$numrows = 0;
		$parentId = 0;
		
		$groups = array(
			'name'       => $this->input->post('name'),
			'publish'    => $this->input->post('publish'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
		
		$this->db->trans_start();
		
		$this->db->insert('groups', $groups);
		
		$parentId = $this->db->insert_id();
		
		$numrows += $this->db->affected_rows();
		
		if (is_array($this->input->post('product_group_by'))) {
			foreach ($this->input->post('product_group_by') as $value) {
				// echo $value.br(1);
				$product_group_by = array(
					'pid'     => $value,
					'parent'  => $parentId
				);
				$this->db->insert('groups_rel_products', $product_group_by);
				$numrows += $this->db->affected_rows();
			}
		}
		
		$this->db->trans_complete();
		
		
		
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('name', 'Group Name', 'required|min_length[1]|max_length[255]|alpha_dash|callback_groupname_check');
		return $this->form_validation->run();
	}
	
	public function groupname_check($name)
	{
		$query = $this->db->query('SELECT id FROM `groups` WHERE `name` = ?', $name );
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('groupname_check', '<br>The %s already exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	
	
}