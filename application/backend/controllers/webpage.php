<?php

/**
* 
*/
class Webpage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
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
			}
		}
		
		
		$result = $this->db->query("SELECT * FROM webpages WHERE is_delete = 0 ORDER BY ordering asc");
		$this->webpages = $result->result();
		
		$this->load->view('webpage');
		
	}
	
 	public function addnew()
	{
		$this->load->view('webpage_addnew');
	}

	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM webpages WHERE is_delete = 0 and id = " . $ndx);
		$this->webpage = $result->result();
		$this->load->view('webpage_update');
	}

	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('webpage_addnew');
			return;
		}
		
		$Webpage = array(
			'page_name'        => $this->input->post('page_name'),
			'page_url'         => $this->input->post('page_url'),
			'page_title'       => $this->input->post('page_title'),
			'page_content'     => $this->input->post('page_content'),
			'author'           => $this->input->post('author'),
			'meta_description' => $this->input->post('meta_description'),
			'meta_keyword'     => $this->input->post('meta_keyword'),
			'meta_robots'      => $this->input->post('meta_robots'),
			'publish'          => $this->input->post('publish'),
			'created_at'       => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('webpages', $Webpage);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'WebPage->save() 交易錯誤');
		}
						
		redirect('webpage/success', 'refresh');
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
	
	private function _update_submit_validate()
	{		
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('page_name', 'Page Name', 'required|min_length[1]|max_length[255]');
		$this->form_validation->set_rules('page_url', 'Page URL', 'required|min_length[1]|max_length[255]|alpha_dash');
		$this->form_validation->set_rules('page_title', 'Page Title', 'required|min_length[1]|max_length[255]');
		$this->form_validation->set_rules('page_content', 'Page Content');
		
		$this->form_validation->set_rules('meta_description', 'Meta Description');
		$this->form_validation->set_rules('meta_keyword', 'Meta Keywords');
		$this->form_validation->set_rules('meta_robots', 'Meta Robots');
		
		return $this->form_validation->run();
	}

	public function _update_save()
	{
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}

		$Webpage = array(
			'page_name'        => $this->input->post('page_name'),
			'page_url'         => $this->input->post('page_url'),
			'page_title'       => $this->input->post('page_title'),
			'page_content'     => $this->input->post('page_content'),
			'author'           => $this->input->post('author'),
			'meta_description' => $this->input->post('meta_description'),
			'meta_keyword'     => $this->input->post('meta_keyword'),
			'meta_robots'      => $this->input->post('meta_robots'),
			'publish'          => $this->input->post('publish'),
			'created_at'       => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('webpages', $Webpage);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Webpage->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}

	public function _publish()
	{
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('webpages', $Publish);
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
					$this->db->query('update webpages set is_delete = 1, updated_at = \'' . 
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

	public function success()
	{
		$this->message = "Successfully Saved Web Page";
		$this->index();
	}
	
}