<?php



/**

* 

*/

class Footer extends CI_Controller

{

	

	function __construct()

	{

		parent::__construct();

	}

	

	public function index()

	{

		

		if ($_POST) {

			
			 if ($_POST['action'] == "update_section_save" ) {	

				$this->update_save_section();

			}else if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.

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

		

		$result = $this->db->query("SELECT * FROM footer_links order by id desc");

		$this->FooterLinks = $result->result();

		$result = $this->db->query("SELECT * FROM footer_section order by id asc");

		$this->Section = $result->result();

				

		$this->load->view('new_footer_links.php');

	}

	

	public function update_section()

	{

		$result = $this->db->query("SELECT * FROM footer_section order by id asc");

		$this->Section = $result->result();

		$this->load->view('new_footer_section');

	}

	

	private function _submit_validate_section()

	{		

		$this->form_validation->set_rules('name1', 'Section 1', 'required');		
		$this->form_validation->set_rules('name2', 'Section 2', 'required');		
		$this->form_validation->set_rules('name3', 'Section 3', 'required');		
		$this->form_validation->set_rules('name4', 'Section 4', 'required');		

		return $this->form_validation->run();

	}

	

	public function update_save_section()

	{

		if ($this->_submit_validate_section() === FALSE) {

			$this->update_section();
			return;

		}

		
		$Section = array(
			'name'      => $this->input->post('name1')
		);
		
		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id1'));
		$this->db->update('footer_section', $Section);
		$this->db->trans_complete();

		

		$Section = array(
			'name'      => $this->input->post('name2')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id2'));
		$this->db->update('footer_section', $Section);
		$this->db->trans_complete();		

		

		$Section = array(
			'name'      => $this->input->post('name3')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id3'));
		$this->db->update('footer_section', $Section);
		$this->db->trans_complete();
		

		$Section = array(
			'name'      => $this->input->post('name4')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id4'));
		$this->db->update('footer_section', $Section);
		$this->db->trans_complete();

		

		redirect('footer/success', 'refresh');		

		

	}

	

	public function addnew()

	{

		$result = $this->db->query("SELECT * FROM footer_section order by id asc");

		$this->Section = $result->result();

		$this->load->view('new_footer_link_addnew');

	}

	

	private function _submit_validate()

	{		

		$this->form_validation->set_rules('is_active', 'Publish', 'required');		
		$this->form_validation->set_rules('section_id', 'Section', 'required');		
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('position', 'Order', 'required');
		return $this->form_validation->run();

	}

	

	public function save()

	{

		if ($this->_submit_validate() === FALSE) {

			$this->addnew();

			return;

		}

		

		$Menu = array(

			'name'      => $this->input->post('title'),

			'url'    => $this->input->post('url'),

			'position'        => $this->input->post('position'),

			'section_id'        => $this->input->post('section_id'),

			'is_active'        => $this->input->post('is_active')

			

		);

		

		$this->db->trans_start();

		$this->db->insert('footer_links', $Menu);

		$this->db->trans_complete();

		

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();

			log_message('error', 'Footer->save() 交易錯誤');

		}

		

		redirect('footer/success', 'refresh');	

	}



	public function success()

	{

		$this->message = "Successfully Saved";

		$this->index();

	}



	public function _publish()

	{		

		$Publish = array('is_active' => $this->input->post('publish_state'));	

		$this->db->where('id', $_POST['id']);

		$this->db->update('footer_links', $Publish);

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

					$this->db->query('delete from footer_links where id = ' . $id);	

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

		$this->db->from('footer_links');		

		$this->db->where('id', $this->input->post('id'));

		$this->FooterLinks = $this->db->get()->result();

		

		$result = $this->db->query("SELECT * FROM footer_section order by id asc");

		$this->Section = $result->result();

		

		$this->load->view('new_footer_link_update');

	}



	private function _update_submit_validate()

	{		

		$this->form_validation->set_rules('is_active', 'Publish', 'required');		
		$this->form_validation->set_rules('section_id', 'Section', 'required');		
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('position', 'Order', 'required');

		

		return $this->form_validation->run();

	}

	



	public function _update_save()

	{

		if ($this->_update_submit_validate() === FALSE) {

			$this->_update($_POST['id']);

			return;

		}

		

		$Menu = array(

			'name'      => $this->input->post('title'),

			'url'    => $this->input->post('url'),

			'position'        => $this->input->post('position'),

			'section_id'        => $this->input->post('section_id'),

			'is_active'        => $this->input->post('is_active')

			

		);

		

		$this->db->trans_start();

		$this->db->where('id', $this->input->post('id'));

		$this->db->update('footer_links', $Menu);

		$this->db->trans_complete();

		

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();

			log_message('error', 'Footer->save() 交易錯誤');

		}

		

		redirect('footer/success', 'refresh');		

		

	}
	
	
	public function text()

	{

		

		if ($_POST) {

			
			if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.

				$this->_remove_text($_POST['id']);

			} else if ($_POST['action'] == "publish" && $_POST['id'] != "") {	// Publish or unPublish

				$this->_publish_text($_POST['id']);

			} else if ($_POST['action'] == "update" && $_POST['id'] != "") {	// Change Update Mode

				$this->_update_text($_POST['id']);

				return;

			} else if ($_POST['action'] == "update_save" && $_POST['id'] != "") {	//	Save Update Data.

				if ($this->_update_save_text() == false) {

					return;

				}

			}

		}

		

		$result = $this->db->query("SELECT * FROM footer_text order by id asc");

		$this->Text = $result->result();

				

		$this->load->view('new_footer_text.php');

	}
	
	public function textAdd()

	{

		$this->load->view('new_footer_text_addnew');

	}
	
	private function _submit_validate_text()

	{		

		$this->form_validation->set_rules('is_active', 'Publish', 'required');		
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('text', 'Text', 'required');
		return $this->form_validation->run();

	}

	
	public function saveText()

	{

		if ($this->_submit_validate_text() === FALSE) {

			$this->textAdd();

			return;

		}

		

		$Menu = array(

			'url'    => $this->input->post('url'),
			'text'        => $this->input->post('text'),
			'is_active'        => $this->input->post('is_active')

		);

		

		$this->db->trans_start();

		$this->db->insert('footer_text', $Menu);

		$this->db->trans_complete();

		

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();

			log_message('error', 'Footer->saveText() 交易錯誤');

		}

		

		redirect('footer/text', 'refresh');	

	}
	
	
	public function _remove_text($ndx)

	{

		$numrows = 0;

		if (!empty($ndx)) {

			$ids = explode(',', $ndx);

			if (is_array($ids) && (count($ids) >= 1)) {

				$this->db->trans_start();

				foreach ($ids as $id) {

					$this->db->query('delete from footer_text where id = ' . $id);	

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

	public function _update_text()

	{

		$this->db->select('*');

		$this->db->from('footer_text');		

		$this->db->where('id', $this->input->post('id'));

		$this->Text = $this->db->get()->result();

		$this->load->view('new_footer_text_update');

	}
	
	public function _update_save_text()

	{

		if ($this->_submit_validate_text() === FALSE) {

			$this->_update_text($_POST['id']);

			return;

		}

		

		$Menu = array(
			'url'    => $this->input->post('url'),
			'text'        => $this->input->post('text'),
			'is_active'        => $this->input->post('is_active')

			

		);

		

		$this->db->trans_start();

		$this->db->where('id', $this->input->post('id'));

		$this->db->update('footer_text', $Menu);

		$this->db->trans_complete();

		

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();

			log_message('error', 'Footer->save() 交易錯誤');

		}

		

		redirect('footer/text', 'refresh');		

		

	}

	

}