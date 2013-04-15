<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Admin
{	
	function __construct()
	{
		$this->is_signin();
		$this->_init();
		// $this->_enable_profiler(TRUE);
	}
	
	private function _init()
	{
		$CI =& get_instance();
		
		//	Load Backend Menu data
		$CI->db->select('*')->from('sys_menus');
		$Query = $CI->db->get();
		$CI->menus = $Query->result();
		
		//	前台的人數統計 (舊的)
		// $CI->db->select('count(`session_id`) as `count`');
		// $Query = $CI->db->from('ci_sessions_frontend')->get()->result();		
		// $CI->ONLINE_FRONTEND_COUNT = $Query[0]->count;
		
		// //	前台的人數統計		
		// $query = $CI->db->query('SELECT * FROM ci_sessions_frontend group by ip_address');
		// $CI->ONLINE_FRONTEND_COUNT = $query->num_rows();
		
		//	前台的人數統計 換了...
		$query = $CI->db->query('SELECT * FROM sessions');
		$CI->ONLINE_FRONTEND_COUNT = $query->num_rows();
		
		
		//	後台的人數統計 (舊)
		// $CI->db->select('count(`session_id`) as `count`');
		// $Query = $CI->db->from('ci_sessions_backend')->get()->result();
		// $CI->ONLINE_BACKEND_COUNT = $Query[0]->count;
		
		//	前台的人數統計		
		$query = $CI->db->query('SELECT * FROM ci_sessions_backend group by ip_address');
		$CI->ONLINE_BACKEND_COUNT = $query->num_rows();
		
		
	}
	
	public function _enable_profiler($trun_no = FALSE)
	{
		$CI =& get_instance();
		$CI->output->enable_profiler($trun_no);
	}
	
	public function is_signin()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		if ($CI->uri->segment(1, 0) != "signin") {
			if ($CI->session->userdata('accout_type') != "manager" && $CI->session->userdata('logged_in') == FALSE) {
				redirect('signin', 'refresh');
			}	
		}
	}
	
	public function signout()
	{
		$CI =& get_instance();
		$CI->session->sess_destroy();
		redirect('signin', 'refresh');
	}
		
}
