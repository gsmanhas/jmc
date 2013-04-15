<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Mailer
{	
	
	private $CI;
	
	public function __construct()
	{
		$this->init();
	}
	
	private function init()
	{
		
		$this->CI =& get_instance();
		
		//	這個設定檔需要來自 DB
		$email_config = array(
			'charset'   => 'utf-8',
			'wordwrap'  => TRUE,
			'mailtype'  => 'html',
			'priority'  => 3,
			'smtp_host' => 'mail.sixspokemedia.com',
			'smtp_user' => 'hhuang@sixspokemedia.com',
			'smtp_pass' => 'lit89dmz%%',
			'mailfrom'  => 'developer@sixspokemedia.com',
			'mailbcc'   => 'developer@sixspokemedia.com',
			'cc'        => 'developer@sixspokemedia.com'
		);
				
		$this->CI->email->initialize($email_config);
			
	}
	
	// //	Send Mail Test.
	// public function sendMail()
	// {
	// 	$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran');
	// 	$this->CI->email->to("hhuang@sixspokemedia.com");
	// 	$this->CI->email->bcc($this->CI->config->item('mailbcc'));
	// 	$this->CI->email->subject('Account Details for ' . "QQ" .  ' at Josie Maran.');
	// 	$this->CI->email->message($this->CI->load->view('emails/account_update', '', TRUE));
	// 	$this->CI->email->send();
	// }
	
	public function send_invoice($mail2)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		
		// $query = $this->CI->db->query("SELECT * FROM mails WHERE id = 3")->result();
		// if (count($query) >= 1) {
		// 	$this->CI->email->bcc($query[0]->mail_list);
		// }
		
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));
		// $this->CI->email->bcc($this->CI->config->item('order'));
		$this->CI->email->subject('Your Order with Josie Maran');
		$this->CI->email->message(
			$this->CI->load->view('emails/invoice', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function send_invoice2($mail2)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));
		// $this->CI->email->bcc($this->CI->config->item('order'));
		$this->CI->email->subject('Your Josie Maran Order has Shipped');
		$this->CI->email->message(
			$this->CI->load->view('emails/invoice2', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function send_order($mail2)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));
		// $this->CI->email->bcc($this->CI->config->item('order'));
		$this->CI->email->subject('Your Order with Josie Maran');
		$this->CI->email->message(
			$this->CI->load->view('emails/invoice3', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function send_review($mail)
	{
		$this->CI->email->from($mail);
		$this->CI->email->to($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		
		$query = $this->CI->db->query("SELECT * FROM mails WHERE id = 8")->result();
		
		if (count($query) >= 1) {
			$this->CI->email->bcc($query[0]->mail_list);
		}
		
		// $this->CI->email->bcc($this->CI->config->item('bcc'));
		$this->CI->email->subject('New Customer Product Review');
		$this->CI->email->message(
			$this->CI->load->view('emails/admin/new_review', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function send_customer_services($mail)
	{
		$this->CI->email->from($mail);
		$this->CI->email->to($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
				
		switch ($this->CI->input->post('services_options')) {
			case "1" :
				// My Josie Maran Order
				$this->CI->email->subject('Customer Inquiry Notification (My Josie Maran Order)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 3)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
			case "2" :
				// My Josie Maran Registration and Password
				$this->CI->email->subject('Customer Inquiry Notification (My Josie Maran Registration and Password)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 6)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
			case "3" :
				// General questions and comments
				$this->CI->email->subject('Customer Inquiry Notification (General questions and comments)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 2)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
			case "4" :
				//	Distribution Inquiries
				$this->CI->email->subject('Customer Inquiry Notification (Distribution Inquiries)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 1)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
			case "5" :
				//	Product Recommendations
				$this->CI->email->subject('Customer Inquiry Notification (Product Recommendations)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 5)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
			case "6" :
				//	Technical Support
				$this->CI->email->subject('Customer Inquiry Notification (Technical Support)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 7)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
			case "7" :
				//	Press Inquiry
				$this->CI->email->subject('Customer Inquiry Notification (Press Inquiry)');
				$BCC = $this->CI->db->query("SELECT * FROM mails WHERE id = ?", 4)->result();
				if (count($BCC) >= 1) {
					$this->CI->email->bcc($BCC[0]->mail_list);
				}
			break;
		}
		
				
		// $this->CI->email->bcc($this->CI->config->item('mailbcc'));
		// $this->CI->email->bcc($this->CI->config->item('recommend'));
		
		// $this->CI->email->subject('Customer Inquiry Notification');
		$this->CI->email->message(
			$this->CI->load->view('emails/admin/customer_case', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function retrieve_password($mail2, $customer_name)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));
		// $this->CI->email->bcc($this->CI->config->item('reg_pwd'));
		$this->CI->email->subject('Account Details for ' . $customer_name .  ' at Josie Maran.');
		$this->CI->email->message(
			$this->CI->load->view('emails/retrieve_password', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function account_update($mail2, $customer_name)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));		
		$this->CI->email->subject('Updates on Your Josie Maran Account');
		$this->CI->email->message(
			$this->CI->load->view('emails/account_update', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function account_activation($mail2, $customer_name)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));
		// $this->CI->email->bcc($this->CI->config->item('reg_pwd'));
		$this->CI->email->subject('Welcome to Josie Maran ');
		$this->CI->email->message(
			$this->CI->load->view('emails/account_activation', '', TRUE)
		);
		$this->CI->email->send();
	}
	
	public function send_wishlist($mail2, $subject)
	{
		$this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
		$this->CI->email->to($mail2);
		$this->CI->email->bcc($this->CI->config->item('mailbcc'));
		$this->CI->email->subject('Josie Maran Wish List from ' . $this->CI->session->userdata('firstname'));
		$this->CI->email->message(
			$this->CI->load->view('emails/wishlist', '', TRUE)
		);
		$this->CI->email->send();
	}

    public function  send_voucher($email) {
        $this->CI->email->from($this->CI->config->item('mailfrom'), 'Josie Maran Customer Service');
        $this->CI->email->to($email);
        $this->CI->email->bcc($this->CI->config->item('mailbcc'));
        $this->CI->email->subject('Your Josie Maran eGift Card');
        $this->CI->email->message(
            $this->CI->load->view('emails/voucher', '', TRUE)
        );
        $this->CI->email->send();
    }
		
}