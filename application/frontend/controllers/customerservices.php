<?php
class Customerservices extends MY_Controller {

	function __construct()
	{		
		parent::__construct();
	}

	public function _remap()
	{
		if (strtolower($this->uri->segment(2, 0)) == "submit") {
			$this->submit();
		} else {
			$this->index();
		}
		
	}

	public function index()
	{
		
		$this->CaseCatalogs = $this->db->query("SELECT * FROM customer_cases_catalog WHERE is_delete = 0 and publish = 1 ORDER BY ordering asc")->result();
		$this->load->view('customer-services');
	}

	private function _submit_validate()
	{
		$this->form_validation->set_rules('services_options', 'Please select the type of question you have and fill out the form below.', 'required');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('comments', 'Comments', 'required');
		$this->form_validation->set_message('required', 'required');
		return $this->form_validation->run();
	}

	public function submit()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		$CustomerCase = array(
			'case_id'           => $this->input->post('services_options'),
			'uid'               => $this->session->userdata('user_id'),
			'first_name'        => $this->input->post('first_name'),
			'last_name'         => $this->input->post('last_name'),
			'email'             => $this->input->post('email'),
			'comments'          => $this->input->post('comments'),
			'use_jmc_cosmetics' => (isset($_POST['use_jmc_cosmetics'])) ? $this->input->post('use_jmc_cosmetics') : "0",
			'is_register'       => (isset($_POST['is_register'])) ? $this->input->post('is_register') : "0",
			'created_at'  => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('customer_case', $CustomerCase);
		$this->db->trans_complete();
		
		// $this->_sendVaildationMail();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'customer_case->save() 交易錯誤');
		}
		
		$Query = $this->db->query(
			"SELECT name FROM customer_cases_catalog WHERE id = ? AND is_delete = 0 and publish = 1 ORDER BY ordering asc", $this->input->post('services_options')
		)->result();
		
		$this->SUBJECT = $Query[0]->name;
		$this->DETAILS = $this->input->post('comments');
		$this->USE_JMC_COSMETICS = ($this->input->post('use_jmc_cosmetics') == 1) ? "yes" : "no";
		$this->IS_REGISTER = ($this->input->post('is_register') == 1) ? "yes" : "no";
		
		$mailer = new Mailer();
		$mailer->send_customer_services($this->input->post('email'));
		
		redirect('thanks-for-contact', 'refresh');
		
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
		// $this->email->print_debugger()
	}

}
