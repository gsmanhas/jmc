<?php

/**
*
*/
class Giftvouchers extends CI_Controller
{
    private $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	function __construct()
	{
        parent::__construct();
        $this->load->library("Acl");

        if(!$this->acl->checkAccess(__CLASS__, $this->session->userdata('id'))) {
            show_error("Sorry, you don't have access to this page.");
            exit;
        }
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
			} else if ($_POST['action'] == "send") {	//	Save Update Data.
				if ($this->_send() == false) {
					return;
				}
			}
		}

		$result = $this->db->query("SELECT * FROM gift_voucher WHERE is_delete = 0 ORDER BY id asc");
		$this->gifts = $result->result();

		$this->load->view('giftvouchers');

	}

	public function addnew()
	{
		$this->load->view('giftvouchers_addnew');
	}

	public function save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('giftvouchers_addnew');
			return;
		}

		$GiftVouchers = array(
            'gift_voucher_image_small'=> $this->input->post('small_image') != 'Click here to browse' ? $this->input->post('small_image') : '',
            'gift_voucher_image_big'=> $this->input->post('big_image') != 'Click here to browse' ? $this->input->post('big_image') : '',
            'gift_voucher_balance'=> $this->input->post('gift_voucher_balance'),
			'gift_voucher_code'   => $this->input->post('gift_voucher_code'),
			'gift_voucher_value'  => $this->input->post('gift_voucher_value'),
			'gift_voucher_type'   => $this->input->post('gift_voucher_type'),
			'recipient_name'      => $this->input->post('recipient_name'),
			'recipient_email'     => $this->input->post('recipient_email'),
			'send_message'        => $this->input->post('send_message'),
			'enabled'             => $this->input->post('enabled'),
			'created_at'          => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->insert('gift_voucher', $GiftVouchers);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'GiftVouchers->save() 交易錯誤');
		}

		redirect('giftvouchers/success', 'refresh');
	}

	private function _submit_validate()
	{
		$this->form_validation->set_rules('enabled', 'enabled');
		$this->form_validation->set_rules('gift_voucher_type', 'Gift Voucher Type', 'trim|required');
		$this->form_validation->set_rules('gift_voucher_balance', 'Gift Voucher Balance', 'trim|numeric|required');
		return $this->form_validation->run();
	}

	private function _update_submit_validate()
	{
		$this->form_validation->set_rules('enabled', 'enabled');
        $this->form_validation->set_rules('gift_voucher_type', 'Gift Voucher Type', 'trim|required');
        $this->form_validation->set_rules('gift_voucher_balance', 'Gift Voucher Balance', 'trim|numeric|required');
		return $this->form_validation->run();
	}

	function voucher_code_check()
	{
		$query = $this->db->query(
			"SELECT id FROM gift_voucher WHERE gift_voucher_code = '" . $this->input->post('gift_voucher_code') . "'" .
			" AND is_delete = 0"
		);
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('voucher_code_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function voucher_code2_check()
	{
		$query = $this->db->query(
			"SELECT id FROM gift_voucher WHERE gift_voucher_code = '" . $this->input->post('gift_voucher_code') . "'" .
			" AND id !=" . $this->input->post('id') .
			" AND is_delete = 0"
		);
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('voucher_code2_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function success()
	{
		$this->message = "Successfully Saved Gift Vouchers";
		$this->index();
	}

	public function _publish()
	{
		$Enabled = array('enabled' => $this->input->post('publish_state'));
		$this->db->where('id', $_POST['id']);
		$this->db->update('gift_voucher', $Enabled);
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
					$this->db->query('update gift_voucher set is_delete = 1, updated_at = \'' .
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

	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM gift_voucher WHERE is_delete = 0 and id = " . $ndx);
		$this->gift = $result->result();
		$this->load->view('giftvouchers_update');
	}

	public function _update_save()
	{
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}

		$GiftVouchers = array(
            'gift_voucher_image_small'=> $this->input->post('small_image') != 'Click here to browse' ? $this->input->post('small_image') : '',
            'gift_voucher_image_big'=> $this->input->post('big_image') != 'Click here to browse' ? $this->input->post('big_image') : '',
			'gift_voucher_balance'=> $this->input->post('gift_voucher_balance'),
			'gift_voucher_code'   => $this->input->post('gift_voucher_code'),
			'gift_voucher_value'  => $this->input->post('gift_voucher_value'),
			'gift_voucher_type'   => $this->input->post('gift_voucher_type'),
			'recipient_name'      => $this->input->post('recipient_name'),
			'recipient_email'     => $this->input->post('recipient_email'),
			'send_message'        => $this->input->post('send_message'),
			'enabled'             => $this->input->post('enabled'),
			'updated_at'          => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('gift_voucher', $GiftVouchers);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'GiftVouchers->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}

    private function _send() {
        $this->load->view("giftvouchers_send_manually");
    }

    public function send() {

        $id = $this->input->post('id');

        if(!empty($id)) {

            $query = $this->db->query("SELECT * FROM gift_voucher WHERE id = ?", $id);

            $item = $query->row();
        } else {
            $item = new stdClass();
            $item->gift_voucher_balance = '';
        }

        $OrderVoucherDetails = array(
            'voucher_id' => $id,
            'code' => $this->get_random_string($this->chars, 5),
            'qty' => 1,
            'price' => !empty($id) ? $item->gift_voucher_balance : $this->input->post('voucher_balance'),
            'balance' => !empty($id) ? $item->gift_voucher_balance : $this->input->post('voucher_balance'),
            'title' => 'JOSIE MARAN eGift Card $' . !empty($id) ? $item->gift_voucher_balance : $this->input->post('voucher_balance'),
            'to'=>$this->input->post('to'),
            'from'=>$this->input->post('from'),
            'recipient_email'=>$this->input->post('recipient_email'),
            'message'=>$this->input->post('message'),
            'gift_voucher_type' => 'manually_created',
            'created_at' => unix_to_human(time(), TRUE, 'us'),
            'created_by' => $this->session->userdata("username"),
            'note' => $this->input->post('note')
        );

        require_once(APPPATH . '/libraries/Mailer.php');

        $this->item = $OrderVoucherDetails;
        $Mailer = new Mailer();

        $Mailer->send_voucher($this->input->post('recipient_email'));

        $this->db->insert('order_voucher_details', $OrderVoucherDetails);

        redirect("giftvouchers");
    }

    private  function get_random_string($valid_chars, $length) {
        $random_string = "";

        $num_valid_chars = strlen($valid_chars);

        for ($i = 0; $i < $length; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);

            $random_char = $valid_chars[$random_pick-1];
            $random_string .= $random_char;
        }

        return $random_string;
    }



    function resend() {
        $id = $this->uri->segment(3);

        $item = $this->db->query("SELECT * FROM order_voucher_details WHERE id=?", $id);
        $item = $item->row();

        $this->item = $item;

        require_once(APPPATH . '/libraries/Mailer.php');

        $Mailer = new Mailer();
        $Mailer->resend_voucher($item->recipient_email);


    }

}



/**
* 

class Giftvouchers extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->admin->is_login();
		
		//	載入後台的選單資訊
		$this->db->select('*')->from('sys_menus');
		$query = $this->db->get();
		$this->menus = $query->result();
		if ($this->session->userdata('ip_address') == "0.0.0.0") {
			$this->output->enable_profiler(TRUE);
		}
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
		
		$result = $this->db->query("SELECT * FROM gift_voucher WHERE is_delete = 0 ORDER BY id asc");
		$this->gifts = $result->result();
		
		$this->load->view('admin/giftvouchers');
		
	}
	
	public function addnew()
	{
		$this->load->view('admin/giftvouchers_addnew');
	}
	
	public function save()
	{			
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('admin/giftvouchers_addnew');
			return;
		}
		
		$GiftVouchers = array(
			'gift_voucher_code'   => $this->input->post('gift_voucher_code'),
			'gift_voucher_value'  => $this->input->post('gift_voucher_value'),
			'gift_voucher_type'   => $this->input->post('gift_voucher_type'),
			'recipient_name'      => $this->input->post('recipient_name'),
			'recipient_email'     => $this->input->post('recipient_email'),
			'send_message'        => $this->input->post('send_message'),
			'enabled'             => $this->input->post('enabled'),
			'created_at'          => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('gift_voucher', $GiftVouchers);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'GiftVouchers->save() 交易錯誤');
		}
		
		redirect('/admin/giftvouchers/success', 'refresh');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('enabled', 'enabled');
		$this->form_validation->set_rules('gift_voucher_code', 'Gift Voucher Code', 'trim|required|min_length[6]|max_length[64]|alpha_numeric|callback_voucher_code_check');
		$this->form_validation->set_rules('gift_voucher_value', 'Gift Voucher Value', 'trim|required|numeric');
		$this->form_validation->set_rules('gift_voucher_type', 'Gift Voucher Type', 'trim|required');
		$this->form_validation->set_rules('recipient_name', 'Recipient Name', 'trim|required');
		$this->form_validation->set_rules('recipient_email', 'Recipient Email', 'trim|required|valid_email');
		return $this->form_validation->run();
	}
	
	private function _update_submit_validate()
	{
		$this->form_validation->set_rules('enabled', 'enabled');
		$this->form_validation->set_rules('gift_voucher_code', 'Gift Voucher Code', 'trim|required|min_length[6]|max_length[64]|alpha_numeric|callback_voucher_code2_check');
		$this->form_validation->set_rules('gift_voucher_value', 'Gift Voucher Value', 'trim|required|numeric');
		$this->form_validation->set_rules('gift_voucher_type', 'Gift Voucher Type', 'trim|required');
		$this->form_validation->set_rules('recipient_name', 'Recipient Name', 'trim|required');
		$this->form_validation->set_rules('recipient_email', 'Recipient Email', 'trim|required|valid_email');
		return $this->form_validation->run();
	}
	
	function voucher_code_check()
	{
		$query = $this->db->query(
			"SELECT id FROM gift_voucher WHERE gift_voucher_code = '" . $this->input->post('gift_voucher_code') . "'" .
			" AND is_delete = 0"
		);
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('voucher_code_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function voucher_code2_check()
	{
		$query = $this->db->query(
			"SELECT id FROM gift_voucher WHERE gift_voucher_code = '" . $this->input->post('gift_voucher_code') . "'" .
			" AND id !=" . $this->input->post('id') .
			" AND is_delete = 0"
		);
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('voucher_code2_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Gift Vouchers";
		$this->index();
	}
	
	public function _publish()
	{		
		$Enabled = array('enabled' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('gift_voucher', $Enabled);
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
					$this->db->query('update gift_voucher set is_delete = 1, updated_at = \'' . 
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

	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM gift_voucher WHERE is_delete = 0 and id = " . $ndx);
		$this->gift = $result->result();
		$this->load->view('admin/giftvouchers_update');
	}
	
	public function _update_save()
	{
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$GiftVouchers = array(
			'gift_voucher_code'   => $this->input->post('gift_voucher_code'),
			'gift_voucher_value'  => $this->input->post('gift_voucher_value'),
			'gift_voucher_type'   => $this->input->post('gift_voucher_type'),
			'recipient_name'      => $this->input->post('recipient_name'),
			'recipient_email'     => $this->input->post('recipient_email'),
			'send_message'        => $this->input->post('send_message'),
			'enabled'             => $this->input->post('enabled'),
			'updated_at'          => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('gift_voucher', $GiftVouchers);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'GiftVouchers->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;	
	}
	
}*/