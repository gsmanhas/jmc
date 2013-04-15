<?php

/**
* 
*/
class Cron extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		// $this->ShoppingCart = new SystemCart();

	}

	public function index()
	{

	}

    function _remap($method){
        $this->$method();
    }

    public function shipping_notify() {

        $data = $this->db->query("SELECT * FROM mail_queue WHERE `for`='shipped_notification'")->result();

        foreach ($data as $el) {
            $id = $el->order_id;

            $query = $this->db->query("SELECT * FROM `order` WHERE id = ?", $id);
            $this->Order = $query->result();
            $this->track_number = $this->Order[0]->track_number;

            $query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);
            $this->OrderList = $query2->result();

            $mailer = new Mailer();
            $mailer->send_invoice2($this->Order[0]->email);
            $this->db->delete('mail_queue', array('id'=>$el->id));
        }

        $this->output->set_status_header(200);
        $this->output->set_output('Cron::Finished');
    }
}