<?php

class shipments_for_a_given_time_period extends CI_Controller 
{
	
	public function index()
	{
		
		$this->load->view('shipments_for_a_given_time_period');
	}
	
	public function search()
	{
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		$this->db->select(
			"DISTINCT DATE_FORMAT(o.order_date, '%m/%d/%Y') as 'order_date'," .
			"DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'order_date2'"
		, FALSE);
				
		$this->db->from("`order` as o");
		// $this->db->join("`order_list` as ol", "o.id = ol.order_id");
		// $this->db->group_by('ol.pid');
		$this->db->order_by('o.order_date', 'asc');

		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}

		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$this->db->where('o.is_delete = 0');
		$this->db->where('o.order_state != 3 AND o.order_state != 5 AND o.order_state != 6');
		// $this->db->where('ol.price > 0.01');
						
		$shipments_report = $this->db->get()->result();
		
		if (count($shipments_report) >= 1) {
			$this->last_query = $this->db->last_query();
			$this->shipments_report = $shipments_report;
			$this->load->view("shipments_for_a_given_time_period_result");
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
	
	public function export()
	{
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		
		$delimiter = "\t";
		$newline = "\r\n";
		$query = $this->db->query($this->input->post('last_query'));
		
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		$this->db->query("DELETE FROM `shipments_for_a_given_time_period`");
		
		
		$Shipping_Charged = $this->db->query(
			"SELECT " .
			"SUM(calculate_shipping) as 'price', DATE_FORMAT(?, '%m/%d/%Y') as 'curr_date'" .
			"FROM `order` " .
			"WHERE " .
			"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND (?) " .
			"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
			"AND is_delete = 0 AND freeshipping = 0 " .
			"ORDER BY order_date ASC"
			, array($release_date, $release_date, $expiry_date))->result();
					
		//	$this->input->post('release_date')
		$arr = array(
			"date"     => $Shipping_Charged[0]->curr_date,
			"UPC"      => "WEBSHIP",
			"quantity" => 1,
			"price"    => (float)round($Shipping_Charged[0]->price, 2)
		);
		
		$this->db->insert('shipments_for_a_given_time_period', $arr);
		
		
		foreach ($query->result() as $report) {
			
			$result = $this->db->query(
				"SELECT " .
				"DATE_FORMAT(". $report->order_date .", '%m/%d/%Y') as 'order_date'," .
				"(SELECT sku FROM product WHERE id = pid) as 'sku', pid, sum(qty) as 'qty'," .
				"price " .
				"FROM order_list " .
				"WHERE order_id in(" .
				"SELECT id FROM `order` as o WHERE order_date LIKE '" . $report->order_date2 . "%' " .
				"AND `o`.`order_state` != 3 AND o.order_state != 5 AND o.order_state != 6 " .
				"AND is_delete = 0 " .
				"ORDER BY order_date ASC " .
				")" .
				" GROUP BY pid, price " .
				" ORDER BY sku ASC"
			, FALSE)->result();
						
			foreach ($result as $item) {
				$arr = array(
					"date"     => $report->order_date,
					"UPC"      => $item->sku,
					"quantity" => $item->qty,
					"price"    => $item->price
				);
				$this->db->insert('shipments_for_a_given_time_period', $arr);
			}
		}
		
		$query = "SELECT `date`, UPC, quantity, price FROM shipments_for_a_given_time_period";
        $Reporting = new Reporting();
		// echo $this->dbutil->csv_from_result($query);
		// echo $this->dbutil->csv_from_result($query, $delimiter, $newline); 
		if ($Reporting->export_shipments_for_a_given_time_period($query))
		{
			$this->update_message = "Unable to write the file";
		}
		else
		{
			$this->update_message = "File written!";
		}
		
		$this->index();
	}
	
}