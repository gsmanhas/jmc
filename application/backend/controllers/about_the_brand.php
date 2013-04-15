<?php

/**
* 
*/
class About_the_brand extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$query = $this->db->query("SELECT * FROM special_page WHERE id = 5");
		$this->special_page = $query->result();
		
		$this->load->view('about_the_brand');
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
			'updated_at'       => unix_to_human(time(), TRUE, 'us')
		);
		
		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('special_page', $special_page);
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'about the brand ->save() 交易錯誤');
		}
		
		$this->message = "Successfully Saved";
		
		$this->index();
	}
	
}