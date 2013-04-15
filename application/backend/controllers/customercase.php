<?php

/**
* 
*/
class Customercase extends CI_Controller
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
		
		$Query = $this->db->query("SELECT id, uid, first_name, last_name, email, comments, created_at, " .
			"DATE_FORMAT(cs.created_at, '%Y-%m-%d') as 'odate', " .
			"DATE_FORMAT(cs.created_at, '%p') as 'oapm', " .
			"DATE_FORMAT(cs.created_at, '%T') as 'otime'" .
			", (SELECT title FROM customer_cases_status WHERE id = status) as 'status', (SELECT name FROM customer_cases_catalog WHERE id = case_id) as 'case_catalog' FROM `customer_case` as cs WHERE is_delete = 0 ORDER BY `created_at` desc"
		, FALSE);
		$this->CustomerCases = $Query->result();
		
		$this->load->view('customercase');
	}
	
	public function addnew()
	{
		$this->CaseCatalogs = $this->db->query("SELECT * FROM customer_cases_catalog WHERE is_delete = 0 and publish = 1 ORDER BY ordering asc")->result();	
		$this->CaseStatus   = $this->db->query("SELECT * FROM customer_cases_status ORDER BY id ASC")->result();
		$this->load->view('customercase_addnew');
	}
	
	public function submit()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->addnew();
			return;
		}
		
		$CustomerCase = array(
			'case_id'           => $this->input->post('services_options'),
			// 'uid'               => $this->session->userdata('user_id'),
			'first_name'        => $this->input->post('first_name'),
			'last_name'         => $this->input->post('last_name'),
			'email'             => $this->input->post('email'),
			'comments'          => $this->input->post('comments'),
			'use_jmc_cosmetics' => (isset($_POST['use_jmc_cosmetics'])) ? $this->input->post('use_jmc_cosmetics') : "0",
			'is_register'       => (isset($_POST['is_register'])) ? $this->input->post('is_register') : "0",
			'status'            => $this->input->post('cases_status'),
			'created_at'  => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('customer_case', $CustomerCase);
		$this->db->trans_complete();
		
		$this->_sendVaildationMail();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'customer_case->save() 交易錯誤');
		}
		
		redirect('customercase/success', 'refresh');
		
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Customer Cases";
		$this->index();
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('services_options', 'Please select the type of question you have and fill out the form below.', 'required');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('comments', 'Comments', 'required');	
		return $this->form_validation->run();
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return;
		}
		
		$CustomerCase = array(
			'case_id'           => $this->input->post('services_options'),
			'first_name'        => $this->input->post('first_name'),
			'last_name'         => $this->input->post('last_name'),
			'email'             => $this->input->post('email'),
			'comments'          => $this->input->post('comments'),
			'status'            => $this->input->post('cases_status'),
			'use_jmc_cosmetics' => (isset($_POST['use_jmc_cosmetics'])) ? $this->input->post('use_jmc_cosmetics') : "0",
			'is_register'       => (isset($_POST['is_register'])) ? $this->input->post('is_register') : "0",
			'updated_at'  => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('customer_case', $CustomerCase);
		$this->db->trans_complete();
				
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'customer_case->save() 交易錯誤');
		}
		
		$this->update_message = "1 records updated";
		
		return true;
		
	}
	
	public function _update($ndx)
	{
		
		$result = $this->db->query("SELECT * FROM customer_case WHERE is_delete = 0 and id = " . $ndx);
		$this->CustomerCase = $result->result();
		
		$this->CaseCatalogs = $this->db->query("SELECT * FROM customer_cases_catalog WHERE is_delete = 0 and publish = 1 ORDER BY ordering asc")->result();
		
		$this->CaseStatus   = $this->db->query("SELECT * FROM customer_cases_status ORDER BY id ASC")->result();
		
		$this->load->view('customercase_update');
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update customer_case set is_delete = 1, updated_at = \'' . 
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
	
	public function _sendVaildationMail()
	{		
		$this->email->from($this->config->item('mailfrom'), 'Josie Maran');
		$this->email->to($this->input->post('email'));
		$this->email->bcc($this->config->item('mailbcc'));

		$this->email->subject('Contact Us Details for ' . $this->input->post('first_name') .  ' at Josie Maran.');
		$this->email->message(
			'Hello ' . $this->input->post('first_name') . " " . $this->input->post('last_name') . "," . br(2) .
			'Thanks for you Contact Us.' .
			$this->input->post('comments')
		);

		$this->email->send();
	}
	
}