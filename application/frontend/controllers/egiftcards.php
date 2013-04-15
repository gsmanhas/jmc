<?php
/**
* 
*/
class Egiftcards extends MY_Controller
{
	
	function __construct()
	{	
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function index()
	{
        if(trim($this->uri->segment(2, 0)) == 'balance') {
            $this->balance();
            return;
        }

        if($this->uri->segment(2, 0) == 0) {
            $query = $this->db->query(
                'SELECT egc.* ' .
                ' FROM gift_voucher as egc WHERE is_delete = 0 AND enabled = 1'
            );

            $this->cards = $query->result();


            $this->load->view('egiftcards_list');
        } else {
            $id = $this->uri->segment(2, 0);
            $query = $this->db->query(
                'SELECT egc.* ' .
                ' FROM gift_voucher as egc WHERE is_delete = 0 AND enabled = 1 AND id=' . $id
            );

            $this->card = $query->result();
            if(count($this->card) == 0) {
                show_404();
                return ;
            }


            $this->load->view('egiftcard_details');
        }
    }

    public function balance() {
        $this->load->helper('captcha');
        if($this->input->post('code')) {
            $code = $this->input->post('code');
            $captcha = $this->input->post('captcha');

            if(strtolower($captcha) == strtolower($this->session->userdata("captcha"))) {
                $query = $this->db->query('SELECT balance FROM order_voucher_details WHERE code=?', $code);
                $result = $query->result();
                if(count($result) > 0) {
                    $this->voucher_message = "Your eGift Card / Voucher Balance: $" . $result[0]->balance;
                }else {
                    $this->voucher_message = "Your card wasn't found.";
                }
            } else {
                $this->voucher_message = "Security code doesn't match.";
            }

        } else {
            $this->voucher_message = "Please specify your eGift Card / Voucher code.";
        }

        $vals = array(
            'img_path'	 => './captcha/',
            'img_url'	 => $this->config->item('base_url') . '/captcha/',
            'img_width'	 => 165,
            'img_height' => 50,
            'expiration' => 7200
            );;

        $this->cap = create_captcha($vals);
        $this->session->set_userdata("captcha", $this->cap['word']);


        $this->load->view('egiftcard_balance');

    }

	public function _remap()
	{
        $this->index();
	}
}

