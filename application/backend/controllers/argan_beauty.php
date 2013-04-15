<?php

/**
* 
*/
class Argan_beauty extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
		$query = $this->db->query("SELECT * FROM product WHERE is_delete = 0 ORDER BY `name` asc");
		$this->Products = $query->result_array();
		
		$query = $this->db->query("SELECT * FROM special_page WHERE id = 1");
		$this->special_page = $query->result();
		
		$this->load->view('argan_beauty');
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
		
		$this->db->query("delete from special_page_with_product where sp_id = ?", $this->input->post('id'));
		
		// $numrows = $this->db->affected_rows();
		$numrows = 0;
		if (is_array($this->input->post('pid1'))) {
			foreach ($this->input->post('pid1') as $value) {
				$special_page_with_product = array(
					'sp_id' => $this->input->post('id'),
					'pid'   => $value,
					'ordering'   => 1,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_product', $special_page_with_product);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if (is_array($this->input->post('pid2'))) {
			foreach ($this->input->post('pid2') as $value) {
				$special_page_with_product = array(
					'sp_id' => $this->input->post('id'),
					'pid'   => $value,
					'ordering'   => 2,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_product', $special_page_with_product);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if (is_array($this->input->post('pid3'))) {
			foreach ($this->input->post('pid3') as $value) {
				$special_page_with_product = array(
					'sp_id' => $this->input->post('id'),
					'pid'   => $value,
					'ordering'   => 3,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_product', $special_page_with_product);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if (is_array($this->input->post('pid4'))) {
			foreach ($this->input->post('pid4') as $value) {
				$special_page_with_product = array(
					'sp_id' => $this->input->post('id'),
					'pid'   => $value,
					'ordering'   => 4,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_product', $special_page_with_product);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if (is_array($this->input->post('pid5'))) {
			foreach ($this->input->post('pid5') as $value) {
				$special_page_with_product = array(
					'sp_id' => $this->input->post('id'),
					'pid'   => $value,
					'ordering'   => 5,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_product', $special_page_with_product);
				$numrows += $this->db->affected_rows();
			}
		}
		
		if (is_array($this->input->post('pid6'))) {
			foreach ($this->input->post('pid6') as $value) {
				$special_page_with_product = array(
					'sp_id' => $this->input->post('id'),
					'pid'   => $value,
					'ordering'   => 6,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('special_page_with_product', $special_page_with_product);
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
			log_message('error', 'argan_beauty->save() 交易錯誤');
		}
		
		// redirect('/webpage/success', 'refresh');
		$this->index();
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
	
}