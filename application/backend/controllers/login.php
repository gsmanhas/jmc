<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if ($this->session->userdata('ip_address') == "0.0.0.0") {
			$this->output->enable_profiler(TRUE);
		}		
		// $this->load->model('admin/AdminMenus', 'AdminMenu');
	}

	function index()
	{
		$this->load->view('admin/login');
	}
	
	public function submit()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		redirect(base_url()."admin/home");
		
	}
	
	private function _submit_validate() {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_authenticate');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_message('authenticate','Invalid login. Please try again.');
		return $this->form_validation->run();
	}
	
	public function authenticate()
	{
		$this->db->select('*');
		$this->db->from('manager');
		$this->db->where('username', $this->input->post('username'));
		// $this->db->where('password', $this->encrypt->encode($this->input->post('password')));
		$u = $this->db->get()->result();

		// print_r($u);
		
		if (count($u) >= 1) {
			if ($this->encrypt->decode($u[0]->password) == $this->input->post('password')) {
				$data = array(
					'username'     => $u[0]->username,
					'logged_in'    => TRUE,
					'account_type' => 'manager'
				);
				$this->session->set_userdata($data);
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
		
	}
	
	// public function addUser()
	// {
	// 	$u = array(
	// 		'username' => 'walter@josiemarancosmetics.com',
	// 		'password' => $this->encrypt->encode('jmc123'),
	// 		'created_at' => unix_to_human(time(), TRUE, 'us')
	// 	);
	// 	
	// 	$this->db->insert('manager', $u);
	// }
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */