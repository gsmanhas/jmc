<?php

/**
* 
*/
class General_model extends CI_Model
{

	// public $TblName = '';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_all_entry($tblName)
	{
		$this->db->select('*');
		$this->db->where('is_delete', 0);
		$this->db->order_by('id', 'asc');
		$query = $this->db->get($tblName);
		return $query->result();
	}
	
	public function addnew()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
	}
	
	public function publish()
	{
		
	}
	
}
