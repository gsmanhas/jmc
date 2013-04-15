<?php

/**
* 
*/
class Reviews extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
	}
	
	public function sendmessage()
	{
		if ($this->session->userdata('username') == '') {
			echo json_encode(array('error_message' => "You don't have permission to post a review. Please sign in first."));
		} else {
						
			$Review = array(
				'pid'        => $this->input->post('pid'),
				'uid'        => $this->session->userdata('user_id'),
				'title'      => $this->input->post('title'),
				'rate'       => $this->input->post('rate'),
				'message'    => $this->input->post('message'),
				'created_at' => unix_to_human(time(), TRUE, 'us')
			);
			
			$this->db->insert('product_review', $Review);
			
			$query = $this->db->query("SELECT name FROM product WHERE id = ?", $this->input->post('pid'));
			$result = $query->result();
			
			$mailer = new Mailer();
			$this->DETAILS = $this->input->post('message');
			if (count($result) >= 1) {
				$this->PRODUCT_NAME = $result[0]->name;
			} else {
				$this->PRODUCT_NAME = '';
			}			
			$mailer->send_review($this->session->userdata('email'));
			
			sleep(3);
			
			echo json_encode(array(
				'error_message' => '',
				'success' => 'Your post will now be sent to Josie Maran for review.<br>' .
							 'Josie Maran reserves the right to restrict posts and comments from our review, Thank You!<br>'
			));
		}
	}
	
}