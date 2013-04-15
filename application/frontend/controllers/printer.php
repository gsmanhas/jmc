<?php

/**
* 
*/
class Printer extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}

	public function _remap()
	{
		$this->index();
	}
	
	public function index()
	{
		// echo $this->uri->segment(2, 0);
		
		$id = $this->uri->segment(2, 0);
        if($id == 'bulkprint') {
            $this->bulkprint();
            exit;
        }
				
		$query = $this->db->query("SELECT * FROM `order` WHERE id = ?", $id);
		$this->Order = $query->result();
		
		if($query->num_rows() > 0) {
			
			$this->db->query("update `order` set is_print = 'Y', printed_date = '".date('Y-m-d H:i:s')."' WHERE id = ?", $id);
		}
		
		
		$query = $this->db->query("SELECT " .
		"id, (select state from state as s where s.id = t.state_id) as `state`," .
		"state_id, " .
		"tax_code, tax_rate " .
		"FROM " .
		"tax_codes as t " .
		"where is_delete = 0 ORDER BY `state` ASC");
		$this->continental = $query->result();
		
		$query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);
		$this->OrderList = $query2->result();

       $this->Voucher = null;
		
        $query = $this->db->query("SELECT * FROM order_rel_voucher WHERE order_id = ?", $this->Order[0]->order_no);
        if($query->num_rows() > 0) {		
            $this->Voucher = $query->row();
        } else {
			$query = $this->db->query("SELECT * FROM order_rel_voucher WHERE order_id = ?", $id);
			if($query->num_rows() > 0) {
				$this->Voucher = $query->row();
			}
        }

        if ($this->Order[0]->discount_id != 0) {
            $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();
        }
		
		if (count($this->Order[0]->track_number) >= 1) {
			$this->track_number = $this->Order[0]->track_number;
		} else {
			$this->track_number = '';
		}
		
		$this->load->view('printer');
		
	}

    public function bulkprint() {
        $ids = explode(";", $this->input->get("ids"));

        $this->load->library('pdf');

        $this->pdf->SetSubject('JOSIE MARAN invoices - ' . unix_to_human(time()));
        $this->pdf->SetHeaderData("logo.png", 30);
        $this->pdf->setPrintFooter(false);
        foreach ($ids as $id) {
            $query = $this->db->query("SELECT * FROM `order` WHERE id = ?", $id);
            $this->Order = $query->result();
			
			if($query->num_rows() > 0) {
				$this->db->query("update `order` set is_print = 'Y', printed_date = '".date('Y-m-d H:i:s')."' WHERE id = ?", $id);
			}
			

            $query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);
            $this->OrderList = $query2->result();

            $query = $this->db->query("SELECT * FROM order_rel_voucher WHERE order_id = ?", $id);
            if($query->num_rows() > 0) {
                $this->Voucher = $query->row();
            } else {
                $this->Voucher = null;
            }

            if (count($this->Order[0]->track_number) >= 1) {
                $this->track_number = $this->Order[0]->track_number;
            } else {
                $this->track_number = '';
            }

            if ($this->Order[0]->discount_id != 0) {
                $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();
            }

            $html = $this->load->view('printer_bulk', null, true);
            $this->pdf->AddPage();
            $this->pdf->writeHTML($html, true, false, true, false, '');
            $this->pdf->lastPage();
        }

        $this->pdf->Output('invoice_'.unix_to_human(time()).'.pdf', 'I');
    }
	
}