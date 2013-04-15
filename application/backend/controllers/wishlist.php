<?php

/**
* 
*/
class Wishlist extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
				
		$this->db->select('u.id, u.firstname, u.lastname, u.email, u.created_at');
		$this->db->from('wishlist as w');
		$this->db->join('users as u', 'w.uid = u.id', 'left');
		$this->db->group_by('u.id');
		$this->db->where('w.is_delete', 0);
		$this->WishList = $this->db->get()->result();
		
		// $query = $this->db->query("SELECT * FROM wishlist where is_delete = 0 order by id asc");
		// $this->WishList = $query->result();
		
		$this->load->view('wishlist');
	}
	
}
