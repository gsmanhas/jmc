<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signin extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('signin');
	}
	
	public function submit()
	{
		
		if ($this->_submit_validate() === FALSE) {
			
			$this->index();
			return;
		}
		
		// echo "string";
		redirect('home', 'refresh');
		
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
		
		if (count($u) >= 1) {
			if ($this->encrypt->decode($u[0]->password) == $this->input->post('password')) {
				$data = array(
					'id'           => $u[0]->id,
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
	
	public function addUser()
	{
		$u = array(
			'username' => 'Iesha@josiemaran.com',
			'password' => $this->encrypt->encode('Makeup123'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->insert('manager', $u);
	}

	public function updateUser()
	{
		 
		echo $this->encrypt->encode('finance432'); die;
		
		
		$u = array(
			'password' => $this->encrypt->encode('finance123'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);

        $this->db->where("username", "mike@josiemarancosmetics.com");

		$this->db->update('users', $u);
		
		
	}
	
}

/* End of file signin.php */
/* Location: ./application/backend/controllers/signin.php */