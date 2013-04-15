<?php

/**
* 
*/
class Josies_bio extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		$this->josies_profolio = $this->db->query("SELECT * FROM josies_profolio WHERE is_delete = 0 ORDER BY ordering asc")->result_array();
		$this->josies_reel     = $this->db->query("SELECT * FROM josies_reel WHERE is_delete = 0 ORDER BY ordering asc")->result_array();
				
		$query = $this->db->query("SELECT * FROM special_page WHERE id = 3");
		$this->special_page = $query->result();		
		
		$this->load->view('josies_bio');
	}
	
	private function _submit_validate()
	{		
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('page_name', 'Page Name', 'required|min_length[1]|max_length[255]|unique[Webpages.page_name]');
		$this->form_validation->set_rules('page_url', 'Page URL', 'required|min_length[1]|max_length[255]|unique[Webpages.page_name]|alpha_dash');
		$this->form_validation->set_rules('page_title', 'Page Title', 'required|min_length[1]|max_length[255]');
		$this->form_validation->set_rules('page_content', 'Page Content');
		
		$this->form_validation->set_rules('meta_description', 'Meta Description');
		$this->form_validation->set_rules('meta_keyword', 'Meta Keywords');
		$this->form_validation->set_rules('meta_robots', 'Meta Robots');
		
		return $this->form_validation->run();
	}

	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		$special_page = array(
			'page_name'        => $this->input->post('page_name'),
			'page_url'         => $this->input->post('page_url'),
			'page_title'       => $this->input->post('page_title'),
			'page_content'     => $this->input->post('page_content'),
			'author'           => $this->input->post('author'),
			'meta_description' => $this->input->post('meta_description'),
			'meta_keyword'     => $this->input->post('meta_keyword'),
			'meta_robots'      => $this->input->post('meta_robots'),
			'publish'          => $this->input->post('publish'),
			// 'pid'              => $this->input->post('pid'),
			'created_at'       => unix_to_human(time(), TRUE, 'us')
		);
		
		$this->db->trans_start();
		
		$this->db->query("delete from special_page_with_bio where sp_id = ?", $this->input->post('id'));
		
		$numrows = 0;
		if (is_array($this->input->post('pro_id'))) {
			foreach ($this->input->post('pro_id') as $value) {
				$special_page_with_bio = array(
					'sp_id'      => $this->input->post('id'),
					'pro_id'     => $value,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_bio', $special_page_with_bio);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if (is_array($this->input->post('reel_id'))) {
			foreach ($this->input->post('reel_id') as $value) {
				$special_page_with_bio = array(
					'sp_id'      => $this->input->post('id'),
					'reel_id'    => $value,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_bio', $special_page_with_bio);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if ($numrows != 0) {
			$this->message =  $numrows . " records deleted";	
		}
		
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('special_page', $special_page);
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Josies_bio->save() 交易錯誤');
		}
		
		// redirect('webpage/success', 'refresh');
		$this->index();
	}
	
}